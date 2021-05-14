@if(isset($goals[0]))
<ul class="nav nav-pills mb-3 mt-2" id="pills-tab" role="tablist">
  @if(isset($goals[0]))
  <li class="nav-item text-center">
    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
      {{$goals[0]->title}}<br>
      {{-- {{$goals[0]->efforts_time}}/{{$goals[0]->goal_time}} [時間] --}}
    </a>

  </li>
  @endif
  @if (isset($goals[1]))
  <li class="nav-item text-center">
    <a class="nav-link" id="pills-second-tab" data-toggle="pill" href="#pills-second" role="tab" aria-controls="pills-second" aria-selected="false">
      {{$goals[1]->title}}<br>
      {{-- {{$goals[1]->efforts_time}}/{{$goals[1]->goal_time}} [時間] --}}
    </a>
  </li>
  @endif
  @if (isset($goals[2]))  
  <li class="nav-item text-center">
    <a class="nav-link" id="pills-third-tab" data-toggle="pill" href="#pills-third" role="tab" aria-controls="pills-third" aria-selected="false">
      {{$goals[2]->title}}<br>
      {{-- {{$goals[2]->efforts_time}}/{{$goals[2]->goal_time}} [時間] --}}
    </a>
  </li>
  @endif
</ul>

<div class="tab-content" id="pills-tabContent">
  @if(isset($efforts[0]))
  <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
    <div class="card">
      <div class="card-body"> 
        <div class="mb-2 h5">
          <a class="text-dark font-weight-bold" href="{{ route('goals.show', ['goal' => $goals[0]]) }}">
            {{ $goals[0]->title }}
          </a>
        </div>                 
        <div class="mb-2">
          {{ $goals[0]->content }}
        </div>  
        @if ($goals[0]->deadline)
        <div class="mb-2">
          <span class="border px-1">目標達成期限</span>
          {{ date("Y/m/d", strtotime($goals[0]->deadline)) }}
        </div>     
        @endif
        @if ($goals[0]->efforts_time)
        <div>
          <span class="border px-1">総継続時間</span>
          {{$goals[0]->efforts_time}} 時間
        </div> 
        @endif         
      </div> 
    </div>        
    @foreach($efforts[0] as $effort)
    @include('efforts.card')
    @endforeach 
    {{$efforts[0]->appends(request()->query())->links()}}
  </div>
  @endif 
  @if(isset($efforts[1]))
  <div class="tab-pane fade" id="pills-second" role="tabpanel" aria-labelledby="pills-second-tab">
    <div class="card">
      <div class="card-body"> 
        <div class="mb-2 h5">
          <a class="text-dark font-weight-bold" href="{{ route('goals.show', ['goal' => $goals[1]]) }}">
            {{ $goals[1]->title }}
          </a>
        </div> 
        <div class="mb-2">
          {{ $goals[1]->content }}
        </div>  
        @if ($goals[1]->deadline)
        <div class="mb-2">
          <span class="border px-1">目標達成期限</span>
          {{ date("Y/m/d", strtotime($goals[1]->deadline)) }}
        </div> 
        @endif 
        @if ($goals[1]->efforts_time)   
        <div>
          <span class="border px-1">総継続時間</span>
          {{$goals[1]->efforts_time}} 時間
        </div>
        @endif       
      </div> 
    </div>       
    @foreach($efforts[1] as $effort)
    @include('efforts.card')
    @endforeach
    {{$efforts[1]->appends(request()->query())->links()}}     
  </div>
  @endif 
  @if(isset($efforts[2])) 
  <div class="tab-pane fade" id="pills-third" role="tabpanel" aria-labelledby="pills-third-tab">
    <div class="card">
      <div class="card-body">
        <div class="mb-2 h5">
          <a class="text-dark font-weight-bold" href="{{ route('goals.show', ['goal' => $goals[2]]) }}">
            {{ $goals[2]->title }}
          </a>
        </div> 
        <div class="mb-2">
          {{ $goals[2]->content }}
        </div> 
        @if ($goals[2]->deadline) 
        <div class="mb-2">
          <span class="border px-1">目標達成期限</span>
          {{ date("Y/m/d", strtotime($goals[2]->deadline)) }}
        </div>      
        @endif
        @if ($goals[2]->efforts_time)
        <div>
          <span class="border px-1">総継続時間</span>
          {{$goals[2]->efforts_time}} 時間
        </div>  
        @endif     
      </div>    
    </div>
    @foreach($efforts[2] as $effort)
    @include('efforts.card')
    @endforeach
    {{$efforts[2]->appends(request()->query())->links()}}  
  </div>
  @endif
</div>
@endif