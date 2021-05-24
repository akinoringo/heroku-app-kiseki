<div class="card my-3 pt-4 pb-2 border-light border-top-0 border-right-0 border-left-0 rounded-0" >
  <div class="row no-gutters">
    <div class="col-md-4 text-center">
      <a href="{{ route('mypage.show', ['id' => $user->id]) }}" class="text-dark">      
        @if(!empty($user->image))
        <img src="{{$user->image}}" class="rounded-circle" style="object-fit: cover; width: 200px; height: 200px;">
        @else
        <img src="/images/prof.png" class="rounded-circle" style="object-fit: cover; width: 200px; height: 200px;">
        @endif
      </a>      
    </div>
    <div class="col-md-8">
      <div class="card-body">

        @if( Auth::id() === $user->id )
        <div class="dropdown text-right">
          <a data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            ▼  
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" href="{{ route("mypage.edit", ['id' => $user->id]) }}">
              プロフィールを編集する
            </a>
          </div>
        </div>
        @endif        

        <h5 class="card-title">Name</h5>
        <p class="card-text">{{ $user->name }}</p>
        <h5 class="card-title">About</h5>
        <p class="card-text mb-4">{{$user->introduction}}</p>
        <div class="card-text d-flex">
          <a href="{{route('followings', ['name' => $user->name])}}" class="text-muted mt-2 mr-2">
            {{ $user->count_followings}} フォロー
          </a>
          <a href="{{route('followers', ['name' => $user->name])}}" class="text-muted mt-2">
            {{ $user->count_followers}} フォロワー
          </a>          

        @if( Auth::id() !== $user->id )
          <follow-button
            class="ml-4 mr-auto"
            :initial-followed-by='@json($user->isFollowedBy(Auth::user()))'
            :authorized='@json(Auth::check())'
            endpoint="{{route('follow', ['name' => $user->name])}}"
          >
          </follow-button>
        @endif        
        </div>
        <div class="mt-3">
        @if ( $user->efforts_time_badge === 1)
          <img src="/images/badge_perseverance.svg" class="rounded-circle" style="object-fit: cover; width: 80px; height: 80px;">
        @endif
        @if ( $user->stacking_days_badge === 1)
         <img src="/images/badge_continuity.svg" class="rounded-circle" style="object-fit: cover; width: 80px; height: 80px;">
        @endif
        @if ( $user->goal_clear_badge === 1)
         <img src="/images/badge_achieve.svg" class="rounded-circle" style="object-fit: cover; width: 80px; height: 80px;">   
        @endif                               
        </div>         

      </div>     
    </div>
  </div>


</div>

