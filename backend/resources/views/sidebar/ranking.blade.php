<div class="card mb-4">
  <ul class="list-group list-group-flush">
  	<li class="list-group-item text-white bg-primary">
      <img src="/images/logo-smile.png" class="rounded-circle mr-2" style="object-fit: cover; width: 40px; height: 40px;">
      {{date('n月')}}の積み上げランキングだよ！
    </li>
  	@foreach ($ranked_users as $ranked_user)
  		<li class="list-group-item">
        @if ($ranked_user['rank'] === 1)
        <i class="fas fa-crown" style="color: #e6b422;"></i>
        @elseif ($ranked_user['rank'] === 2)
        <i class="fas fa-crown" style="color: #c9caca;"></i>
        @elseif ($ranked_user['rank'] === 3)
        <i class="fas fa-crown" style="color: #815a2b;"></i>  
        @else
        <i class="fas fa-medal" style="color: #815a2b;"></i>      
        @endif         
  			{{ $ranked_user['rank']}}位 :        
  			<a class="text-dark font-weight-bold" href="{{route('mypage.show', ['id' => $ranked_user['id']])}}">
  				{{ $ranked_user['name'] }} さん
  			</a>         
  			({{ $ranked_user['efforts_count'] }} 回)       
  		</li>
  	@endforeach
  </ul>
</div>