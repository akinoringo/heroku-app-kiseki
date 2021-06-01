<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use App\Http\Requests\EffortRequest;
use App\Repositories\Effort\EffortRepositoryInterface as EffortRepository;
use App\Repositories\Goal\GoalRepositoryInterface as GoalRepository;
use App\Services\BadgeService;
use App\Services\DayService;
use App\Services\EffortService;
use App\Services\GoalService;
use App\Services\RankingService;
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
	protected $ranking_service;
	protected $time_service;
	protected $effort_repository;
	protected $goal_repository;
  
	public function __construct(EffortRepository $effort_repository, GoalRepository $goal_repository, RankingService $ranking_service, BadgeService $badge_service, DayService $day_service, EffortService $effort_service, GoalService $goal_service, TimeService $time_service)
	{
		// Serviceクラスからインスタンスを作成
		$this->BadgeService = $badge_service;
		$this->DayService = $day_service;
		$this->EffortService = $effort_service;
		$this->GoalService = $goal_service;
		$this->RankingService = $ranking_service;			
		$this->TimeService = $time_service;	

		// Repositoryクラスからインスタンスを作成
		$this->EffortRepository = $effort_repository;
		$this->GoalRepository = $goal_repository;

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

		// 全ての軌跡を検索語でソートして作成順に並び替えて取得
		$efforts = $this->EffortService->getEffortsWithSearch($request->search);

		// 全ての目標を作成順に並び替えて取得
		$goals = Goal::orderBy('created_at', 'desc')
			->paginate(10, ["*"],'goalspage');

		// 積み上げ回数順でランキング
		$ranked_users = $this->RankingService->rankingEffortsCount();

		// フォロー中の人の軌跡を検索語でソートして作成順に並び替えて取得
		$efforts_follow = $this->EffortService->getEffortsOfFollowee();
		
		return view('home', compact('goals', 'efforts', 'efforts_follow', 'ranked_users'));		

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
		$user = Auth::user();
		$goals = $this->GoalService->getGoalsOnProgress($user);

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
		$goal = $this->GoalRepository->getGoalById($request->goal_id)->first();

		// 積み上げ日数や継続日数を更新
		$this->DayService->updateDays($goal);

		//軌跡の保存処理
		$this->EffortRepository->storeEffort($effort, $request);

		// 目標の継続時間合計を更新。
		$this->EffortService->updateEffortsTime($goal);	

		// 積み上げ時間や日数に応じてバッジを獲得
		$this->BadgeService->updateBadges($goal);

		// 目標達成期限を過ぎていた場合はアラートを出す。
		$this->DayService->checkGoalDeadline($goal);

		$tag_first = $effort->goal->tags->first() ?? null;

		if ($tag_first !== null) {
			foreach ($effort->goal->tags as $tag) {

				if ($tag === $tag_first) {
					$hashtags = $tag_first->name;

				}
				if ($tag !== $tag_first) {

					$hashtags .= "," . $tag->name;
				}			
			}			

		} else {

			$hashtags = "軌跡";

		}
		
		return redirect()
						->route('mypage.show', [
							'id' => Auth::user()->id,
						])
						->with([
							'flash_message' => '軌跡を作成しました。',
							'color' => 'success',
							'sns_message' => '軌跡をシェアしましょう',
							'share_content' => '軌跡を登録しました',
							'share_message' => $effort->title,					
							'share_hashtags' => $hashtags,					
						]);		
	}

	/**
		* 軌跡の編集画面
		* @param Effort $effort
		* @return  \Illuminate\Http\Response
	*/
	public function edit(Effort $effort){
		// 自身の未達成の目標を取得
		$goals = $this->GoalService->getGoalsOnProgress(Auth::user());

		// 軌跡が紐づいている目標を取得
		$goal = $this->GoalRepository->getGoalById($effort->goal_id)->first();

		// 未達成の目標に紐づく軌跡なら編集可能
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

		// 軌跡にリクエスト情報を保存
		$this->EffortRepository->updateEffort($effort, $request);

		// 軌跡に紐づく目標と、目標に紐づく軌跡を全て抽出
		$goal = $this->GoalRepository->getGoalById($effort->goal_id)->first();

		// 目標に紐づく軌跡の合計時間を更新		
		$this->EffortService->updateEffortsTime($goal);

		// 積み上げ時間や日数に応じてバッジを獲得
		$this->BadgeService->updateBadges($goal);		

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
		$goal = $this->GoalRepository->getGoalById($effort->goal_id)->first();

		// 軌跡に紐づく目標が未達成の場合は、軌跡を削除可能。
		if ($goal->status === 0) {

			// $effortのステータスを削除(1)に変更する。
			$this->EffortRepository->destroyEffort($effort);

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
