@extends('layouts.app')

@section('content')

@guest

@include('layouts.guestpage')

@endguest

<div class="container pt-2">
  <div class="row">

    <!-- Sidebar -->
    <div class="col-lg-4">
      @include('sidebar.sns')
      @include('sidebar.ranking')
    </div>

    <!-- Sidebarここまで -->
    <div class="col-lg-8">
      <ul class="nav nav-pills mb-3 mt-2" id="pills-tab" role="tablist">
        <li class="nav-item text-center">
          <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">
            みんなの軌跡
          </a>    
        </li>
        <li class="nav-item text-center">
          <a class="nav-link" id="pills-second-tab" data-toggle="pill" href="#pills-second" role="tab" aria-controls="pills-second" aria-selected="false">
            みんなの目標
          </a>
        </li>          
        @if (Auth::check() && isset($efforts_follow[0]))
        <li class="nav-item text-center">
          <a class="nav-link" id="pills-third-tab" data-toggle="pill" href="#pills-third" role="tab" aria-controls="pills-third" aria-selected="false">
            フォロー中の軌跡
          </a>
        </li>  
        @endif
      </ul>

      <div class="tab-content" id="pills-tabContent">
        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
        @include('efforts.search')
        @foreach($efforts as $effort) 
          @include('efforts.card')
        @endforeach
        {{$efforts->appends(request()->query())->links()}}      
        </div>
        <div class="tab-pane fade" id="pills-second" role="tabpanel" aria-labelledby="pills-second-tab">
        @foreach($goals as $goal) 
          @include('goals.card')
        @endforeach
        {{$goals->appends(request()->query())->links()}}
        </div>        
        @if (Auth::check() && isset($efforts_follow[0]))    
        <div class="tab-pane fade" id="pills-third" role="tabpanel" aria-labelledby="pills-third-tab">
        @foreach($efforts_follow as $effort) 
          @include('efforts.card')
        @endforeach
        {{$efforts_follow->appends(request()->query())->links()}}
        </div>
        @endif
      </div>
  </div>
</div>

@endsection
