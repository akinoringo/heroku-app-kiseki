@extends('layouts/app')

@section('title', '一覧')

@section('content')
  @include('layouts/nav')
  <div class="container">
    @foreach($goals as $goal) 
      @include('goals.card')
    @endforeach 
  </div>
@endsection