@extends('layouts/app')

@section('content')
  
  <div class="container">
  	@include('mypage.profile')
  	@foreach($followings as $person)
  		@include('mypage.person')
		@endforeach
  </div>
@endsection