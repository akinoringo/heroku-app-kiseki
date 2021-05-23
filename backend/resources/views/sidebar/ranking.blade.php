<div class="card mb-4">
  <ul class="list-group list-group-flush">
  	<li class="list-group-item text-white bg-primary">積み上げ数ランキング ({{date('n月')}})</li>
  	@foreach ($ranked_users as $ranked_user)
  		<li class="list-group-item">
  			{{ $ranked_user['rank']}}位 : 
  			<a class="text-dark font-weight-bold" href="{{route('mypage.show', ['id' => $ranked_user['id']])}}">
  				{{ $ranked_user['name'] }} さん
  			</a>
  			({{ $ranked_user['efforts_count'] }} 回)
  		</li>
  	@endforeach
  </ul>
</div>