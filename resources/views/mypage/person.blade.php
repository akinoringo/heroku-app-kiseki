<div class="card mt-3">
  <div class="card-body">
    <div class="d-flex flex-row">
      <a href="{{ route('mypage.show', ['id' => $person->id]) }}" class="text-dark">
	      @if(!empty($person->image))
	      <img src="{{$person->image}}" class="rounded-circle" style="object-fit: cover; width: 50px; height: 50px;">
	      @else
	      <img src="/images/prof.png" class="rounded-circle" style="object-fit: cover; width: 50px; height: 50px;">
	      @endif 
      </a>
	    <h2 class="h5 card-title m-0 ml-3 pt-2">
	      <a href="{{ route('mypage.show', ['id' => $person->id]) }}" class="text-dark">{{ $person->name }}</a>
	    </h2>      
      @if( Auth::id() !== $person->id )
        <follow-button
          class="mr-2 ml-auto mt-1"
          :initial-followed-by='@json($person->isFollowedBy(Auth::user()))'
          :authorized='@json(Auth::check())'
          endpoint="{{ route('follow', ['name' => $person->name]) }}"
        >
        </follow-button>
      @endif
    </div>

  </div>
</div>

