<div class="card mt-3">
  <div class="card-body d-flex flex-row">
    <a href="{{ route('mypage.show', ['id' => $effort->user->id]) }}" class="text-dark">     
      @if(!empty($effort->user->image))
      <img src="{{$effort->user->image}}" class="rounded-circle mr-2" style="object-fit: cover; width: 50px; height: 50px;">
      @else
      <img src="/images/prof.png" class="rounded-circle mr-2" style="object-fit: cover; width: 50px; height: 50px;">
      @endif     
    </a>
    <div>
      <div class="font-weight-bold"><a class="text-dark" href="{{route('mypage.show', ['id' => $effort->user->id ])}}">{{$effort->user->name}}</a></div>
      <div class="font-weight-lighter">{{ $effort->created_at->format('Y/m/d H:i') }}</div>
    </div>

  @if( Auth::id() === $effort->user_id && $effort->goal->status === 0)
    <!-- dropdown -->
      <div class="ml-auto card-text">
        <div class="dropdown">
          <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ▼
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route("efforts.edit", ['effort' => $effort]) }}">
              <i class="fas fa-pen mr-1"></i>軌跡を編集する
            </a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $effort->id }}">
              <i class="fas fa-trash-alt mr-1"></i>軌跡を削除する
            </a>
          </div>
        </div>
      </div>
      <!-- dropdown -->

      <!-- modal -->
      <div id="modal-delete-{{ $effort->id }}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('efforts.destroy', ['effort' => $effort]) }}">
              @csrf
              @method('DELETE')
              <div class="modal-body">
                {{ $effort->title }}を削除します。よろしいですか？
              </div>
              <div class="modal-footer justify-content-between">
                <a class="btn btn-outline-grey" data-dismiss="modal">キャンセル</a>
                <button type="submit" class="btn btn-danger">削除する</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- modal -->
  @endif

  </div>
  <div class="card-body pt-0">
    <div class="card-text mb-3">
      <span class="border px-1">目標</span>
      <a class="text-dark" href="{{ route('goals.show', ['goal' => $effort->goal]) }}">
        {{ $effort->goal->title }}
      </a>
    </div>        
    <h3 class="h4 card-title">
      <a class="text-dark" href="{{ route('efforts.show', ['effort' => $effort]) }}">
        {{ $effort->title }}
      </a>
    </h3>
    
    <div class="card-text mb-3">
      {{ $effort->content }}
    </div>
    <div class="card-text mb-1">
      <span class="border px-1 text-dark">継続時間</span>
      {{ $effort->effort_time }}時間
      <span class="border mx-1 px-1 text-dark">連続積み上げ日数</span>
      {{ $effort->goal->continuation_days }}日           
      <span class="border mx-1 px-1 text-dark">合計積み上げ日数</span>
      {{ $effort->goal->stacking_days }}日
    </div>        
  </div>
  <div class="card-body pt-0">
    <div class="card-text d-flex">
      <a class="btn p-1 mb-0 mt-0 mr-2 shadow-none" href="{{ route('efforts.show', ['effort' => $effort]) }}">
        <i class="far fa-comment mr-2"></i>
        {{$effort->comments->count()}}
      </a>
      <effort-like
        :initial-liked-by='@json($effort->isLikedBy(Auth::user()))'
        :initial-count-likes='@json($effort->count_likes)'
        :authorized='@json(Auth::check())'
        endpoint="{{route('efforts.like', ['effort' => $effort])}}"
      >
      </effort-like>
    </div>    
  </div>




</div>