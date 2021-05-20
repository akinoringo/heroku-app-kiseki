@csrf
<div class="form-group mt-4">
  <label>タイトル</label>
  <span class="small ml-2">目標を簡潔に記入してください ※50字以内</span>
  <input type="text" name="title" class="form-control" placeholder="(例)フルマラソン完走" required value="{{ $goal->title ?? old('title') }}">
</div>
<div class="form-group">
  <label>内容</label>
  <span class="small ml-2">
  	目標の詳細と目標達成に向けて取り組みたいことを記入してください ※500字以内
  </span>
  <textarea name="content" required class="form-control" rows="8" placeholder="(例)9月24日のフルマラソンで完走する。それに向けて、毎日1時間は運動する">{{ $goal->content ?? old('content') }}</textarea>
</div>
<div class="form-group">
  <label>目標達成期限</label>
  <span class="small ml-3">目標の達成期限を入力してください</span>
  <input type="date" name="deadline" class="form-control" placeholder="20210924" required value="{{ $goal->deadline ?? old('deadline') }}">
</div>
{{-- <div class="form-group">
  <label>目標継続時間 [時間]</label>
  <span class="small ml-3">10以上の整数を入力してください</span>
  <input type="text" name="goal_time" class="form-control" required value="{{ $goal->goal_time ?? old('goal_time') }}">
</div> --}}