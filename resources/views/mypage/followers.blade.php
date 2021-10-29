@extends('layouts/app')

@section('content')
  
  <div class="container">
  	@include('mypage.profile')
  	@foreach($followers as $person)
  		@include('mypage.person')
		@endforeach
  </div>
@endsection