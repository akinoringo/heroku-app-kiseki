<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use App\Models\Tag;
use App\Http\Requests\GoalRequest;
use App\Repositories\Goal\GoalRepositoryInterface as GoalRepository;
use App\Services\GoalService;
use App\Services\TagService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class GoalController extends Controller
{
	protected $goal_service;
	protected $tag_service;	
	protected $goal_repository;	

	public function __construct(GoalService $goal_service, TagService $tag_service, GoalRepository $goal_repository)
	{
		// Serviceクラスからインスタンスを作成
		$this->GoalService = $goal_service;
		$this->TagService = $tag_service;
		// RepositoryのInterfaceのインスタンス化
		$this->GoalRepository = $goal_repository;		
		// GoalPolicyでCRUD操作を制限
		$this->authorizeResource(Goal::class, 'goal');		
	}

	/**
		* 目標作成フォームの表示
		* @param Request $request
		* @return  \Illuminate\Http\Response
	*/
	public function create() {

		// 自身の未達成の目標数をカウント。
		$user = Auth::user();
		$number = $this->GoalService->countGoalsOnProgress($user);

		// 未達成の目標数が上限に達していない場合、新たに作成可能
		if ($number !== 3){

			// タグの自動補完のために、すべてのタグ名を取得
			$allTagNames = $this->TagService->getAllTagNames();			

			return view('goals.create', ['allTagNames' => $allTagNames]);

		} else { // 未達成の目標数が上限に達していない場合、新たに作成可能	

			return redirect()
				->route('mypage.show', ['id' => $user->id])
				->with([
				'flash_message' => '同時に登録できる目標は3つまでです。',
				'color' => 'danger'
			]);
		}

	}

	/**
		* 目標の登録
		* @param GoalRequest $request
		* @param Goal $goal
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function store(GoalRequest $request, Goal $goal) {

		// フォームリクエストで取得した情報をフィルターして保存
		$this->GoalRepository->storeGoal($request, $goal);

	  $request->tags->each(function ($tagName) use ($goal) {
	      $tag = Tag::firstOrCreate(['name' => $tagName]);
	      $goal->tags()->attach($tag);
	  });			

		return redirect()
						->route('mypage.show', ['id' => Auth::user()->id])
						->with([
							'flash_message' => '目標を登録しました。軌跡を5つ以上登録すると、目標を達成済に変更することができます。',
							'color' => 'success',
						]);
	}

	/**
		* 目標詳細画面の表示
		* @param Goal $goal
		* @return  \Illuminate\Http\Response
	*/
	public function show(Goal $goal)
	{
		$efforts = Effort::where('goal_id', $goal->id)
			->orderBy('created_at', 'desc')
			->paginate(3);

		return view('goals.show', [
			'goal' => $goal,
			'efforts' => $efforts,
		]);
	}		

	/**
		* 目標の編集画面
		* @param GoalRequest $request
		* @param Goal $goal
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function edit(Goal $goal)
	{
		if ($goal->status === 0){

			// Vue Tags Inputでは、タグ名にtextというキーが必要という仕様
      $tagNames = $goal->tags->map(function ($tag) {
          return ['text' => $tag->name];
      });				

      $allTagNames = Tag::all()->map(function ($tag) {
          return ['text' => $tag->name];
      });			

			return view('goals.edit', [
				'goal' => $goal,
				'tagNames' => $tagNames,
				'allTagNames' => $allTagNames,
			]);	

		} else {		

			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => 'クリア済みの目標は編集できません',
								'color' => 'danger'
							]);			
		}

	}

	/**
		* 目標の更新
		* @param GoalRequest $request
		* @param Goal $goal
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function update(GoalRequest $request, Goal $goal)
	{
		// $requestの内容を$goalに保存
		$this->GoalRepository->updateGoal($request, $goal);

    $goal->tags()->detach();

    $request->tags->each(function ($tagName) use ($goal) {
        $tag = Tag::firstOrCreate(['name' => $tagName]);
        $goal->tags()->attach($tag);
    });

		return redirect()
						->route('mypage.show', ['id' => Auth::user()->id])
						->with([
							'flash_message' => '目標を編集しました。',
							'color' => 'success'			
						]);
	}	

	/**
		* 目標の削除
		* @param Goal $goal
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function destroy(Goal $goal)
	{
		if ($goal->status === 0){

			$this->GoalRepository->destroy($goal);

			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => '目標を削除しました。',
								'color' => 'success'			
							]);
		} else {

			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => 'クリア済みの目標は削除できません',
								'color' => 'danger'
							]);			
		}
	}	

	/**
		* 目標のクリア処理
		* @param Goal $goal
		* @return  \Illuminate\Http\RedirectResponse
	*/
	public function clear(Goal $goal)
	{
		if ($goal->efforts()->count() > 4)
		{
			$this->GoalRepository->clear($goal);		

			$user = $goal->user;

			if ($goal->status == 1 && $user->goal_clear_badge == 0) {
				$user->goal_clear_badge = 1;
				session()->flash('badge_message', 'おめでとうございます。達成力の称号を取得しました。');
				session()->flash('badge_color', 'primary');		
				$user->save();		
			}	

			return redirect()
							->route('mypage.show', ['id' => Auth::user()->id])
							->with([
								'flash_message' => 'おめでとうございます。目標を達成しました。',
								'color' => 'success'			
							]);			
		} else {
			return redirect()
							->route('goals.show', ['goal' => $goal])
							->with([
								'flash_message' => '軌跡を5件以上登録しないと、目標を達成済みにはできません。',
								'color' => 'danger'			
							]);				
		}


	}		
	
}
