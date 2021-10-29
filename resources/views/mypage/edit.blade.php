<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>
    @yield('title')
  </title>

 
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/css/mdb.min.css" rel="stylesheet">
  <!-- style -->
  <link href="{{ asset('css/common.css') }}" rel="stylesheet">
</head>

<body>
  <div class="common-wrapper">
    @include('layouts.nav')
    @include('layouts.flash')
    <div class="container">
      <div class="row">
        <div class="mx-auto col col-12 col-sm-11 col-md-9 col-lg-7 col-xl-6">
          <div class="card mt-3">
            <div class="card-body">
              <h2 class="h3 card-title text-center mt-2">プロフィール編集</h2>

              @include('layouts.error_card_list')

              <div class="card-text">
                <form method="POST" action="{{ route('mypage.update') }}" enctype="multipart/form-data">
                  @csrf

                  <div class="text-center">
                    <span class="image-form image-picker text-center">
                      <input type="file" name="image" class="d-none" accept="image/png,image/jpeg, image/jpg, image/gif" id="image">
                      <label for="image" class="d-inline-block">
                        @if(!empty($user->image))
                        <img src="{{$user->image}}" class="rounded-circle" style="object-fit: cover; width: 200px; height: 200px;">
                        @else
                        <img src="/images/prof.png" class="rounded-circle" style="object-fit: cover; width: 200px; height: 200px;">
                        @endif
                      </label>
                    </span>
                  </div>


                  <div class="form-group">
                    <label for="name">Name</label>
                  @if (Auth::id() == 2)
                    <input class="form-control" type="text" id="name" name="name" required value="{{ old('name', $user->name) }}">                  
                  @else
                    <input class="form-control" type="text" id="name" name="name" required value="{{ old('name', $user->name) }}">
                  @endif

                  </div>
                  <div class="form">
                    <label for="introduction">About</label>
                    <textarea class="form-control" type="text" id="introduction" name="introduction">{{ old('introduction', $user->introduction) }}</textarea>

                  </div>                                  
                  <button class="btn btn-block bg-dark mt-2 mb-2 text-white" type="submit">更新する</button>
                </form>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  @include('layouts.footer')

  <script src="{{ mix('js/app.js') }}"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.4/umd/popper.min.js"></script>
  <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.8.11/js/mdb.min.js"></script>
  <script>
  document.querySelector('.image-picker input')
        .addEventListener('change', (e) => {
            const input = e.target;
            const reader = new FileReader();
            reader.onload = (e) => {
                input.closest('.image-picker').querySelector('img').src = e.target.result
            };
            reader.readAsDataURL(input.files[0]);
        });    
  </script>

</body>

</html>