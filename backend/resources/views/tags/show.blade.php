@extends('layouts.app')

@section('content')
  <div class="container">

    <div class="card mt-3">
      <div class="card-body">
        <h2 class="h4 card-title m-0">
          <span class="font-weight-bold">{{ $tag->hashtag }}</span>
        </h2>
      </div>
    </div>


    <ul class="nav nav-pills mb-3 mt-2" id="pills-tab" role="tablist">
      <li class="nav-item text-center">
        <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
          目標({{ $tag->goals->count() }}件)
        </a>    
      </li>
      <li class="nav-item text-center">
        <a class="nav-link" id="pills-second-tab" data-toggle="pill" href="#pills-second" role="tab" aria-controls="pills-second" aria-selected="false">
          軌跡({{ $effortsOnTag->count() }}件)
        </a>
      </li>  
    </ul>

    <div class="tab-content" id="pills-tabContent">
      <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
      @foreach($tag->goals as $goal) 
        @include('goals.card')
      @endforeach
      </div>  
      <div class="tab-pane fade" id="pills-second" role="tabpanel" aria-labelledby="pills-second-tab">
      @foreach($effortsOnTag as $effort) 
        @include('efforts.card')
      @endforeach
      </div>
    </div>  
  </div>

@endsection