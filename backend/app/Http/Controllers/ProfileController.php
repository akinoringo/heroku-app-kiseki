<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\App;

class ProfileController extends Controller
{
	/**
		* マイページの表示
		* プロフィール、目標および軌跡を表示
		* @param Request $request
		* @param User $user
		* @param Goal $goal
		* @param Effort $effort
		* @return  \Illuminate\Http\Response
	*/	
	public function show($id, Request $request) {

		// viewから受け渡された$idに対応するユーザーの取得
		$id = (int)$id;
		$user = User::find($id);

		// リクエストから検索条件と目標のステータス(0：未クリア、1：クリア済み)の取得
		$search = $request->search;
		$goal_label = $request->label;		

		// $userと$goal_labelに対応する目標を配列として取得
		// $goalひとつひとつに紐づく$effortを配列として取得
		$goals = Goal::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(5);
		$efforts = Effort::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(5);

		// 達成済みの目標を配列で取得
		$cleared_goals = $this->goalsGet($user, 1);

		return view('mypage.show', compact('user', 'goals', 'efforts', 'goal_label', 'search', 'cleared_goals', 'id'));

	}

	/**
		* プロフィールの編集画面表示
		* @return  \Illuminate\Http\Response or \Illuminate\Http\RedirectResponse
	*/	
	public function edit($id) {
		if ($id == Auth::user()->id){
			return view('mypage.edit')->with('user', Auth::user());	
		} else {
			return redirect()->back()->with([
				'flash_message' => '他のユーザーのプロフィールは編集できません。',
				'color' => 'danger'
			]);
		}
		
	}

	/**
		* プロフィールの更新
		* @param ProfileRequest $request
		* @return \Illuminate\Http\RedirectResponse
	*/	
	public function update(ProfileRequest $request) {
		$user = Auth::user();

		$user->name = $request->input('name');
		$user->introduction = $request->input('introduction');

		// リクエストに画像があれば、画像を保存し、imageカラムに画像のパス/名前を保存する
		if ($request->has('image')){
			$fileName = $this->saveImage($request->file('image'));
			$user->image = $fileName;
		}

		$user->save();

		return redirect()->route('mypage.show', ['id' => $user->id])
			->with([
				'flash_message' => 'プロフィールを更新しました。',
				'color' => 'success'
			]);
	}

	/**
		* ユーザーのフォロー
		* @param ProfileRequest $request
		* @return Array
	*/	
	public function follow(Request $request, string $name)
	{
		$user = User::where('name', $name)->first();

		if ($user->id === $request->user()->id)
		{
			return abort('404', 'Cannot follow yourself.');
		}

		$request->user()->followings()->detach($user);
		$request->user()->followings()->attach($user);

		return ['name' => $name];
	}

	/**
		* ユーザーのフォロー取り消し
		* @param ProfileRequest $request
		* @return Array
	*/	
	public function unfollow(Request $request, string $name)
	{
		$user = User::where('name', $name)->first();

		if ($user->id === $request->user()->id)
		{
			return abort('404', 'Cannot follow yourself.');
		}

		$request->user()->followings()->detach($user);

		return ['name' => $name];
	}	

	/**
		* フォローしているユーザーの表示
		* @return \Illuminate\Http\Response
	*/	
	public function followings(string $name)
	{
		$user = User::where('name', $name)->first();

		$followings = $user->followings->sortByDesc('created_at');

		return view('mypage.followings', [
			'user' => $user,
			'followings' => $followings,
		]);
	}

	/**
		* フォロワーの表示
		* @return \Illuminate\Http\Response
	*/	
	public function followers(string $name)
	{
		$user = User::where('name', $name)->first();

		$followers = $user->followers->sortByDesc('created_at');

		return view('mypage.followers', [
			'user' => $user,
			'followers' => $followers,
		]);		
	}

	/**
		* ユーザーの(未達成or達成済みの)目標を全て取得する
		* @param Goal $goal
		* @return Builder
	*/		
	private function goalsGet($user, $goal_label)
	{
		if ($goal_label == 1) {
			$goals = Goal::where('user_id', $user->id)
				->where(function($goals){
					$goals->where('status', 1);
				})->get();
		} else {
			$goals = Goal::where('user_id', $user->id)
				->where(function($goals){
					$goals->where('status', 0);
				})->get();			
		}

		return $goals;
	}

	/**
		* 目標に紐づく軌跡を配列で取得する
		* @param Goal $goal
		* @param Effort $effort
		* @return Array
	*/
	private function effortsGet($goals, $search)
	{

		$efforts = [];

		foreach ($goals as $goal) {
			$efforts[] = Effort::orderBy('created_at', 'DESC')
				->where('goal_id', $goal->id)
				->where('status', 0)
				->where(function($query) use ($search) {
									$query->orwhere('title', 'like', "%{$search}%")
												->orwhere('content', 'like', "%{$search}%");
					})->paginate(3);
		}

		return $efforts;
	}
	
	/**
		* 画像をリサイズして保存する
		* @param UoloadFile $file
		* @return String
	*/		
	private function saveImage(UploadedFile $file):string
	{
		$tempPath = $this->makeTempPath();
		Image::make($file)->fit(200, 200)->save($tempPath);

		if (App::environment('local')) {
			$path = Storage::disk('public')
				->putFile('images', new File($tempPath)); //publicディレクトリの	imagesフォルダに保存。	
			$path = '/storage/'.$path; 	

			return $path;	
		}

		if (App::environment('production')) {
			$disk = Storage::disk('s3');
			$filePath = $disk->putFile('images/profile', new File($tempPath), 'public'); //s3のimages/profileディレクトリの	imagesフォルダに保存。	
			$path = $disk->url($filePath);		
		}

		return $path;
	}

	/**
		* 一時的なファイルパスを生成してパスを返す
		* @return String
	*/		
	private function makeTempPath():string
	{
		$tmp_fp = tmpfile();
		$meta = stream_get_meta_data($tmp_fp);
		return $meta["uri"];
	}
}
