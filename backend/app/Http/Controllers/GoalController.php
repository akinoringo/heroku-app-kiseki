<?php

namespace App\Http\Controllers;

use App\Models\Goal;
use App\Models\User;
use App\Http\Requests\GoalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

class GoalController extends Controller
{
  // GoalPolicyでCRUD操作を制限
	public function __construct()
	{
		$this->authorizeResource(Goal::class, 'goal');
	}

	/**
		* 目標詳細画面の表示
		* @param Goal $goal
		* @return  \Illuminate\Http\Response
	*/
	public function show(Goal $goal)
	{
		return view('goals.show', [
			'goal' => $goal,
			'user' => Auth::user()
		]);
	}	

	/**
		* 目標作成フォームの表示
		* @param Request $request
		* @return  \Illuminate\Http\Response
	*/
	public function create() {
		$user = Auth::user();

		// ユーザーに紐づく目標を取得し、ステータスが未達成(statu:0)の目標数をカウント。
		$number = $this->GoalCount($user);

		// 未達成の目標が上限(３つ)の場合は、新たに作成不可。
		if ($number !== 3){

			return view('goals.create');
		} else {

			return redirect()
				->route('mypage.show', ['id' => Auth::user()->id])
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
		$goal->fill($request->all());

		$goal->user_id = $request->user()->id;
		$goal->save();

		return redirect()
						->route('mypage.show', ['id' => Auth::user()->id])
						->with([
							'flash_message' => '目標を登録しました。',
							'color' => 'success',
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

			return view('goals.edit', ['goal' => $goal]);			
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
		$goal->fill($request->all())->save();
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

			$goal->delete();

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
			$user = $goal->user;

			$goal->status = 1;
			$goal->save();		

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

	/**
		* 未達成の目標数をカウントする
		* @param Goal $goal
		* @return  int $number
	*/
	private function GoalCount(User $user) {
		$number = Goal::where('user_id', $user->id)
			->where(function($goals) {
				$goals->where('status', 0);
		})->count();

		return $number;		
	}	
}
