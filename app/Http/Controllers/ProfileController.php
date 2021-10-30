<?php

namespace App\Http\Controllers;

use App\Models\Effort;
use App\Models\Goal;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Services\EffortService;
use App\Services\GoalService;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\App;

class ProfileController extends Controller
{

	protected $effort_service;
	protected $goal_service;
  
	public function __construct(EffortService $effort_service, GoalService $goal_service)
	{
		// Serviceクラスからインスタンスを作成
		$this->GoalService = $goal_service;
		$this->EffortService = $effort_service;		
	}	

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

		// ユーザーの目標をすべて取得
		$goals = $this->GoalService->getAllGoalsOfAUser($user);

		// 未削除の軌跡をすべて取得
		$efforts = $this->EffortService->getAllEffortsOfAUser($user);

		return view('mypage.show', compact('user', 'goals', 'efforts', 'id'));

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
		* 画像をリサイズして保存する
		* @param UoloadFile $file
		* @return String
	*/		
	private function saveImage(UploadedFile $file):string
	{
		$tempPath = $this->makeTempPath();
		Image::make($file)->fit(200, 200)->save($tempPath);

		// if (App::environment('production')) {
		$disk = Storage::disk('public');
		$path = $disk->put('images', new File($tempPath)); //publicディレクトリの	imagesフォルダに保存。	
		$path = '/storage/'.$path; 	

		return $path;	
		// }

		// if (App::environment('production')) {
		// 	$disk = Storage::disk('s3');
		// 	$filePath = $disk->putFile('images/profile', new File($tempPath), 'public'); //s3のimages/profileディレクトリの	imagesフォルダに保存。	
		// 	$path = $disk->url($filePath);		
		// }

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
