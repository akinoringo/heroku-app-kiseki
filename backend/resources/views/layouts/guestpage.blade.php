{{-- <div class="img-wrapper" style="position: relative;">
  <img src="/images/app-image4.png" style="object-fit: cover; width: 100%;">
  <h3 class="text-center" style="position: absolute; top: 30px; left: 0; right: 0; margin: auto; font-size: 8em; color: #ff6347;">
    <span style="font-family: 'Pattaya', sans-serif; letter-spacing: 10px;">Kiseki</span>
  </h3>
  <h3 class="text-center" style="position: absolute; top: 180px; left: 0; right: 0; margin: auto; font-size: 4em; color: #ff6347;">
    <span style="font-family: 'Pattaya', sans-serif; letter-spacing: 3px;">For your achievement</span>
  </h3>  
</div> --}}
<div class="container mt-2 text-center border-bottom mb-2">
    <div class="jumbotron py-5">
      <h1 class="display-4">
        <span style="font-family: 'Pattaya', sans-serif; letter-spacing: 3px;">Kiseki</span>
        <span>とは？</span>
      </h1>
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
    <h3 class="text-center my-4 pb-2 border-bottom">
      <span style="font-family: 'Pattaya', sans-serif; letter-spacing: 3px;">Kiseki</span> の特徴
    </h3>
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
    <h3 class="text-center my-4 pb-2 border-bottom">
      <span style="font-family: 'Pattaya', sans-serif; letter-spacing: 3px;">Kiseki</span> の使い方
    </h3>
    <div class="row">
      <div class="col-lg-4 col-md-6 text-center mb-2">
        <i class="fas fa-3x fa-pen text-primary mb-4"></i>
        <h5>1. 目標を入力</h5>
        <p class="text-muted">
          目標と目標達成期限を入力
        </p>
      </div>
      <div class="col-lg-4 col-md-6 text-center mb-2">
        <i class="fas fa-3x fa-database text-primary mb-4"></i>
        <h5>2. 軌跡を記録</h5>
        <p class="text-muted">
          日々の軌跡(内容/時間など)を記録<br> 
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