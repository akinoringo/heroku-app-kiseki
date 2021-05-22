@extends('layouts.app')

@section('content')

@guest
<div class="container mt-2 text-center">
    <div class="jumbotron py-5">
      <h1 class="display-4">Kisekiとは？</h1>
      <p class="lead">
        目標達成のための日々の頑張り(軌跡)を記録するアプリです。
    　</p>
      <div class="container text-center mb-4">
        <img src="images/stepup.svg" style=" width: 60%;">
      </div> 
      <p>
        まずはユーザー登録して目標を登録してみよう！
      </p>
      <a class="btn btn-primary btn-lg" href="{{route('register')}}" role="button">ユーザー登録</a>
      <div class="my-0">
        <a href="{{ route('login') }}" class="card-text text-muted">ログインはこちら</a>
      </div>      
    </div>
    <h3 class="text-center my-4 pb-2 border-bottom">Kisekiの特徴</h3>
    <div class="row">
      <div class="col-lg-4 col-md-6 text-center">
        <img src="images/team.png" style="width: 79%;">
        <h5>みんなと一緒に頑張れる</h5>
        <p class="text-muted">
          みんなの軌跡を見ることができる。<br>
          日々の継続時間や目標達成数も<br>
          見ることができる。  
        </p>
      </div>
      <div class="col-lg-4 col-md-6 text-center">
        <img src="images/motivation.png" style="width: 60%;">
        <h5>モチベーションを維持できる</h5>
        <p class="text-muted">
          継続時間や日数に応じて、<br>
          バッジを獲得できるから、<br>
          モチベーションを維持できる。 
        </p>        
      </div>
      <div class="col-lg-4 col-md-6 text-center">
        <img src="images/function.png" style="width: 55%;">
        <h5>どんどん機能が増える</h5>
        <p class="text-muted">
          目標達成をサポートする機能を<br>
          どんどん追加予定。
        </p>         
      </div>
    </div>
    <h3 class="text-center my-4 pb-2 border-bottom">Kisekiの使い方</h3>
    <div class="row">
      <div class="col-lg-4 col-md-6 text-center mb-2">
        <i class="fas fa-3x fa-pen text-primary mb-4"></i>
        <h5>1. 目標を入力</h5>
        <p class="text-muted">
          目標と目標継続時間を入力
        </p>
      </div>
      <div class="col-lg-4 col-md-6 text-center mb-2">
        <i class="fas fa-3x fa-database text-primary mb-4"></i>
        <h5>2. 軌跡を記録</h5>
        <p class="text-muted">
          日々の軌跡と取組時間を記録<br> 
        </p>        
      </div>
      <div class="col-lg-4 col-md-6 text-center mb-2">
        <i class="fab fa-3x fa-angellist text-primary mb-4"></i>
        <h5>3. 楽しみながら目標達成</h5>
        <p class="text-muted">
          軌跡に応じてバッジを獲得したり、<br>
          ほかの仲間からいいねを貰えるから、<br>
          楽しみながら目標を達成できる
        </p>         
      </div>
    </div>    
</div>

@endguest

<div class="container pt-2">
  <div class="row">
    <div class="col-lg-4">
      @include('sidebar.ranking')
    </div>
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
