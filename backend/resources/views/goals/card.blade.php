<div class="card mt-3">
  <div class="card-body d-flex flex-row">
    <a href="{{ route('mypage.show', ['id' => $goal->user->id]) }}" class="text-dark">     
      @if(!empty($goal->user->image))
      <img src="{{$goal->user->image}}" class="rounded-circle mr-2" style="object-fit: cover; width: 50px; height: 50px;">
      @else
      <img src="/images/prof.png" class="rounded-circle mr-2" style="object-fit: cover; width: 50px; height: 50px;">
      @endif
    </a>
    <div>
      <div class="font-weight-bold"><a class="text-dark" href="{{route('mypage.show', ['id' => $goal->user->id ])}}">{{$goal->user->name}}</a></div>
      <div class="font-weight-lighter">{{ $goal->created_at->format('Y/m/d H:i') }}</div>
    </div>
    @if (\Carbon\Carbon::parse($goal->deadline)->lt('now') && $goal->status === 0 && \Carbon\Carbon::parse($goal->deadline)->diffInDays('now') < 7)
    <img src="/images/logo-skull2.png" class="rounded-circle ml-5" style="object-fit: cover; width: 50px; height: 50px;">
    <div class="rounded ml-2 card-text text-center bg-danger text-white pt-1 px-2">
      <span style="font-size: 12px;">達成期限まであと、</span><br>
      <h6 class="font-weight-bold">{{ \Carbon\Carbon::parse($goal->deadline)->diffInDays('now') }}日だ</h6>
    </div>
    @elseif (\Carbon\Carbon::parse($goal->deadline)->gte('now') && $goal->status === 0)
    <img src="/images/logo-skull2.png" class="rounded-circle ml-5" style="object-fit: cover; width: 50px; height: 50px;">
    <div class="rounded ml-2 card-text text-center bg-danger text-white pt-1 px-2">
      <span style="font-size: 12px;">達成期限を</span><br>
      <h6 class="font-weight-bold">過ぎているぞ</h6>
    </div>
    @elseif ($goal->status === 1)
    <img src="/images/logo-smile.png" class="rounded-circle ml-5" style="object-fit: cover; width: 50px; height: 50px;">
    <div class="rounded ml-2 card-text text-center bg-success text-white pt-1 px-2">
      <span style="font-size: 12px;">目標達成</span><br>
      <h6 class="font-weight-bold">おめでとう!!</h6>
    </div>
    @endif

  @if( Auth::id() === $goal->user_id && $goal->status === 0)
    <!-- dropdown -->
      <div class="ml-auto card-text">
        <div class="dropdown">
          <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ▼
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route("goals.edit", ['goal' => $goal]) }}">
              <i class="fas fa-pen mr-1"></i>目標を編集する
            </a>
            @if ($goal->efforts()->count() > 4)          
            <div class="dropdown-divider"></div>
            <a class="dropdown-item text-success" data-toggle="modal" data-target="#modal-clear-{{ $goal->id }}">
              <i class="fas fa-check-square mr-1 text-success"></i>目標を達成済にする
            </a>
            @endif
            <div class="dropdown-divider"></div>            
            <a class="dropdown-item text-danger" data-toggle="modal" data-target="#modal-delete-{{ $goal->id }}">
              <i class="fas fa-trash-alt mr-1"></i>目標を削除する
            </a>
          </div>
        </div>
      </div>
      <!-- dropdown -->
      <!-- modal -->
      <div id="modal-clear-{{ $goal->id }}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('goals.clear', ['goal' => $goal]) }}">
              @csrf
              <div class="modal-body">
                {{ $goal->title }}を達成済みにします。よろしいですか？
              </div>
              <div class="modal-footer justify-content-between">
                <a class="btn btn-outline-grey" data-dismiss="modal">キャンセル</a>
                <button type="submit" class="btn btn-success">達成済にする</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- modal -->      

      <!-- modal -->
      <div id="modal-delete-{{ $goal->id }}" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="閉じる">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form method="POST" action="{{ route('goals.destroy', ['goal' => $goal]) }}">
              @csrf
              @method('DELETE')
              <div class="modal-body">
                {{ $goal->title }}を削除します。よろしいですか？
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

@foreach($goal->tags as $tag)
  <!-- $loop->first内の処理はforeach内ではじめの一回だけ行われる -->
  @if($loop->first)
  <div class="card-body pt-0 pb-2 pl-3">
    <div class="card-text line-height">
  @endif
      <a href="{{ route('tags.show', ['name' => $tag->name]) }}" class="p-1 mt-1 text-muted">
        {{ $tag->hashtag }}
      </a>
  @if($loop->last)
    </div>
  </div>
  @endif
@endforeach

  <div class="card-body pt-0">
    <h3 class="h4 card-title">
      <a class="text-dark" href="{{ route('goals.show', ['goal' => $goal]) }}">
        {{ $goal->title }}
      </a>
      @if ($goal->status === 1)
      <i class="fas fa-check-square ml-2 text-success"></i>
      <span class="text-success">達成済み</p>
      @endif      
    </h3>
    <div class="card-text mb-3">
      {{ $goal->content }}
    </div>
    @if ($goal->deadline)
    <div class="card-text mt-1">
      <span class="mr-2 border">目標達成期限</span>
      {{ date("Y/m/d", strtotime($goal->deadline)) }}
    </div>    
    @endif
    @if ($goal->efforts_time !== 0)
    <div class="card-text mt-1">
      <span class="mr-2 border">総継続時間</span>
      {{ $goal->efforts_time }} 時間
    </div>    
    @endif

  </div>
</div>