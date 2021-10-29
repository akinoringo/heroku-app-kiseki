<div class="card mb-1">
  <div class="card-body d-flex flex-row">
    <a href="{{ route('mypage.show', ['id' => $comment->user->id]) }}" class="text-dark">     
      @if(!empty($comment->user->image))
      <img src="{{$comment->user->image}}" class="rounded-circle mr-2" style="object-fit: cover; width: 50px; height: 50px;">
      @else
      <img src="/images/prof.png" class="rounded-circle mr-2" style="object-fit: cover; width: 50px; height: 50px;">
      @endif     
    </a>
    <div>
      <div class="font-weight-bold"><a class="text-dark" href="{{route('mypage.show', ['id' => $comment->user->id ])}}">{{$comment->user->name}}</a></div>
      <div class="font-weight-lighter">{{ $comment->created_at->format('Y/m/d H:i') }}</div>
    </div>

  </div>

  <div class="card-body pt-0">
    
    <div class="card-text">
      {{ $comment->content }}
    </div>
  </div>

</div>