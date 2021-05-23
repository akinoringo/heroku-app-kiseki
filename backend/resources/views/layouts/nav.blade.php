@auth
<nav class="navbar navbar-expand navbar-dark" style="background-color: #008000">

  <a class="navbar-brand font-weight-bold" href="/"><i class="fas fa-shoe-prints fa-rotate-270 mr-2"></i>Kiseki</a>

  <ul class="navbar-nav ml-auto">

    <li class="nav-item">
      <a class="nav-link" href="{{route('goals.create')}}">目標作成</a>
    </li>

    <li class="nav-item">
      <a class="nav-link" href="{{route('efforts.create')}}">軌跡作成</a>
    </li>

    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown"
         aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
        <button class="dropdown-item" type="button"
                onclick="location.href='{{route('mypage.show', ['id' => Auth::user()->id])}}'">
          マイページ
        </button>
        <div class="dropdown-divider"></div>
        <button form="logout-button" class="dropdown-item" type="submit">
          ログアウト
        </button>
      </div>
    </li>  
    <form id="logout-button" method="POST" action="{{route('logout')}}">
      @csrf
    </form>
    <!-- Dropdown -->

  </ul>

</nav>
@endauth

@guest
<!--Navbar-->
<nav class="navbar navbar-dark lighten-4 mb-4" style="background-color: #008000">

  <!-- Navbar brand -->
  <a class="navbar-brand font-weight-bold" href="/"><i class="fas fa-shoe-prints fa-rotate-270 mr-2"></i> Kiseki</a>
  
  <!-- Collapse button -->
  <button class="navbar-toggler toggler-example navbar-dark darken-3" type="button" data-toggle="collapse"
    data-target="#navbarSupportedContent41" aria-controls="navbarSupportedContent41" aria-expanded="false"
    aria-label="Toggle navigation"><span class="white-text"><i class="fas fa-bars fa-1x"></i></span></button>

  <!-- Collapsible content -->
  <div class="collapse navbar-collapse" id="navbarSupportedContent41">

    <!-- Links -->
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">ユーザー登録 <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">ログイン</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('login.guest') }}">ゲストログイン</a>
      </li>
    </ul>
    <!-- Links -->

  </div>
  <!-- Collapsible content -->

</nav>
<!--/.Navbar-->
@endguest