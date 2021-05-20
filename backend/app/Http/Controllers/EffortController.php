<?php

namespace App\Http\Controllers;

use App\Effort;
use App\Goal;
use App\User;
use App\Http\Requests\EffortRequest;
use App\Services\BadgeService;
use App\Services\DayService;
use App\Services\EffortService;
use App\Services\GoalService;
use App\Services\TimeService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EffortController extends Controller
{
	protected $badge_service;
	protected $day_service;
	protected $effort_service;
	protected $goal_service;
	protected $time_service;
  
	public function __construct(BadgeService $badge_service, DayService $day_service, EffortService $effort_service, GoalService $goal_service, TimeService $time_service)
	{
		// Serviceクラスからインスタンスを作成
		$this->BadgeService = $badge_service;
		$this->DayService = $day_service;
		$this->EffortService = $effort_service;
		$this->GoalService = $goal_service;
		$this->TimeService = $time_service;	

		// EffortPolicyでCRUD操作を制限
		$this->authorizeResource(Effort::class, 'effort');
	}

	/**
		* 軌跡一覧の表示
		* @param Request $request
		* @param Effort $effort
		* @return  \Illuminate\Http\Response
	*/
	public function index(Request $request) {

		// 検索語の取得
		$search = $request->search;

		// 全ての軌跡を検索語でソートして作成順に並び替えて取得
		$efforts = $this->EffortService->getEffortsAll($search);

		// フォロー中の人の軌跡を検索語でソートして作成順に並び替えて取得
		if (Auth::check()) {
			$efforts_follow = $this->EffortService->getEffortsFollow($search);
			
			return view('home', compact('efforts', 'efforts_follow'));				
		} else {
			// 誰もフォローしていない場合はnullを代入
			$efforts_follow = null;
			return view('home', compact('efforts', 'efforts_follow'));
		}

	}

	/**
		* 軌跡詳細画面の表示
		* @param Effort $effort
		* @return  \Illuminate\Http\Response
	*/
	public function show(Effort $effort)
	{
		return view('efforts.show', [
			'effort' => $effort,
		]);
	}	

	/**
		* 軌跡作成フォームの表示
		* @param Request $request
		* @param Goal $goal
		* @return  \Illuminate\Http\Response
	*/
	public function create(){
		// 自身の未達成の目標を取得
		$goals = $this->GoalService->myGoalsGet();

		// 未達成の目標がない場合は、マイページへリダイレクト
		if (!isset($goals[0])) {

			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => 'まずは目標を作成してください',
								'color' => 'danger'
							]);
		}

		return view('efforts.create', compact('goals'));
	}

	/**
		* 軌跡の登録
		* @param EffortRequest $request
		* @param Goal $goal
		* @param Effort $effort
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function store(EffortRequest $request, Effort $effort ){
		// 軌跡に紐づく目標を取得
		$goal = Goal::where('id', $request->goal_id)->get()->first();

		// 昨日および今日の軌跡を取得する。
		[$efforts_yesterday, $efforts_today] = $this->EffortService->getEffortsYesterdayAndToday($goal);

		// 積み上げ日数、継続日数を更新
		$this->DayService->addStackingdays($goal, $efforts_today);
		$this->DayService->updateContinuationdays($goal, $efforts_yesterday, $efforts_today);
		$this->DayService->updateContinuationdaysmax($goal);

		//軌跡の保存処理
		$effort->fill($request->all());
		$effort->goal_id = $request->goal_id;
		$effort->user_id = $request->user()->id;
		$effort->save();

		// 奇跡に紐づく目標の継続時間合計をDBに保存。
		$efforts = $this->EffortService->getEffortsOfGoal($goal);
		$goal->efforts_time = $this->TimeService->sumEffortsTime($efforts);	

		// 目標時間>合計継続時間であれば目標ステータスを1に更新
		// $this->GoalService->updateGoalStatus($goal, $efforts);			
		$goal->save();

		// ログインユーザーを取得
		$user = User::where('id', Auth::user()->id)->first();

		// 積み上げ時間が99時間以上でバッジを獲得
		$this->BadgeService->getEffortsTimeBadge($user, $goal);
		// 積み上げ日数が10日以上でバッジを獲得
		$this->BadgeService->getStackingDaysBadge($user, $goal);	
		// 目標をクリアしたら、バッジを獲得
		$this->BadgeService->getGoalClearBadge($user, $goal);

		$user->save();

		// 目標達成期限を過ぎていた場合はアラートを出す。
		$this->DayService->checkGoalDeadline($goal);

		return redirect()
						->route('mypage.show', ['id' => Auth::user()->id])
						->with([
							'flash_message' => '軌跡を作成しました。',
							'color' => 'success'
						]);		
	}

	/**
		* 軌跡の編集画面
		* @param Effort $effort
		* @return  \Illuminate\Http\Response
	*/
	public function edit(Effort $effort){
		// 自身の未達成の目標を取得
		$goals = $this->GoalService->myGoalsGet();

		// 未達成の目標に紐づく軌跡なら編集可能
		$goal = Goal::where('id', $effort->goal_id)->get()->first();

		if ($goal->status == 0) {
			
			return view('efforts.edit', compact('effort', 'goals'));	
		
		}	

		// 達成済みの目標に紐づく軌跡は編集不可能
		if ($goal->status == 1) {
			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => 'クリア済みの目標なので、軌跡は編集できません。',
								'color' => 'danger'
							]);			
		}
	}	

	/**
		* 軌跡の更新
		* @param EffortRequest $request
		* @param Goal $goal
		* @param Effort $effort
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function update(EffortRequest $request, Effort $effort){

		$effort->fill($request->all())->save();

		// 軌跡に紐づく目標と、目標に紐づく軌跡を全て抽出
		$goal = Goal::where('id', $effort->goal_id)->get()->first();
		$efforts = $this->EffortService->getEffortsOfGoal($goal);

		// 目標に紐づく軌跡の継続時間の合計をDBに保存		
		$goal->efforts_time = $this->TimeService->sumEffortsTime($efforts);
		//goal_time>total(effort_time)であれば目標ステータスを1に更新する。
		// $this->GoalService->updateGoalStatus($goal, $efforts);		
		$goal->save();


		// ログインユーザーを取得
		$user = User::where('id', Auth::user()->id)->first();

		// 積み上げ時間が99時間以上でバッジを獲得
		$this->BadgeService->getEffortsTimeBadge($user, $goal);
		// 積み上げ日数が10日以上でバッジを獲得
		$this->BadgeService->getStackingDaysBadge($user, $goal);	
		// 目標をクリアしたら、バッジを獲得
		$this->BadgeService->getGoalClearBadge($user, $goal);
		$user->save();		

		return redirect()
						->route('mypage.show', ['id' => Auth::user()->id])
						->with([
							'flash_message' => '軌跡を編集しました。',
							'color' => 'success'
						]);			
	}	

	/**
		* 軌跡の削除
		* @param Goal $goal
		* @param Effort $effort
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function destroy(Effort $effort)
	{
		// 軌跡に紐づく目標の取得
		$goal = Goal::where('id', $effort->goal_id)->get()->first();

		// 軌跡に紐づく目標が未達成の場合は、軌跡を削除可能。
		if ($goal->status === 0) {

			// $effortのステータスを削除(1)に変更する。
			$effort->status = 1;
			$effort->save();

			// 消去した$effortに紐づいていた$goalに紐づく軌跡合計時間($efforts_time)を再計算
			$efforts = $this->EffortService->getEffortsOfGoal($goal);
			$goal->efforts_time = $this->TimeService->sumEffortsTime($efforts);
			$goal->save();
		
			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => '軌跡を削除しました。',
								'color' => 'success'
							]);			
		} else {
			// 軌跡に紐づく目標がすでに達成済みの場合は、軌跡を削除不可。
			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => 'クリア済みの目標なので、軌跡は削除できません。',
								'color' => 'danger'
							]);			
		}
	}


	/**
		* 軌跡へのいいね
		* @param Request $request
		* @param Effort $effort
		* @return  array
	*/
	public function like(Request $request, Effort $effort){
		$effort->likes()->detach($request->user()->id);
		$effort->likes()->attach($request->user()->id);

		return [
			'id' => $effort->id,
			'countLikes' => $effort->count_likes,
		];
	}


	/**
		* 軌跡へのいいね解除
		* @param Request $request
		* @param Effort $effort
		* @return  array
	*/
	public function unlike(Request $request, Effort $effort){

		$effort->likes()->detach($request->user()->id);

		return [
			'id' => $effort->id,
			'countLikes' => $effort->count_likes,
		];
	}	

}
