@csrf
<div class="form-group mt-4">
  <label>目標</label>
  <select name="goal_id" class="form-control" required>
  	@foreach($goals as $goal)
  	<option value="{{ $goal->id }}" {{ old('goal_id') == $goal->id ? 'selected' : '' }}>
  		{{$goal->title}}
  	</option>
  	@endforeach
	</select>
</div>

<div class="form-group">
  <label>タイトル</label>
  <span class="small ml-2">50字以内(必須)</span>  
  <input type="text" name="title" class="form-control" required value="{{ $effort->title ?? old('title') }}">
</div>

<div class="form-group">
  <label>取組内容</label>
  <span class="small ml-2">500字以内(必須)</span>
  <textarea name="content" required class="form-control" rows="8" placeholder="取り組んだ内容の詳細を入力してください">{{ $effort->content ?? old('content') }}</textarea>
</div>

<div class="form-group">
  <label>反省点</label>
  <span class="small ml-2">200字以内</span>
  <textarea name="reflection" class="form-control" rows="4" placeholder="反省点や改善点があれば入力してください">{{ $effort->reflection ?? old('reflection') }}</textarea>
</div>

<div class="form-group">
  <label>今後の意気込み</label>
  <span class="small ml-2">200字以内</span>
  <textarea name="enthusiasm" class="form-control" rows="4" placeholder="今後の意気込みなどがあれば入力してください">{{ $effort->enthusiasm ?? old('enthusiasm') }}</textarea>
</div>

<button type="button" class="btn btn-link mb-3 p-0 ml-0" style="font-size: 0.9rem; color: #747373;" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">時間を記録する<i class="fas fa-stopwatch ml-2"></i></button>

<div class="collapse" id="collapseExample">

  <div class="form-group">
    <label>取組時間 [時間]</label>
    <span class="small ml-2">0以上20以下の整数を入力してください</span>
    <input type="text" name="effort_time" class="form-control" value="{{ $effort->effort_time ?? old('effort_time') }}">

  </div>
</div>