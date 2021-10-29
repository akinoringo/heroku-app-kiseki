@extends('layouts.app')

@section('content')
  <div class="container">
    @include('goals.card')

    <div class="px-4 mt-2">
    	<h3 class="text-center my-3 font-weight-bold">軌跡一覧</h3>
    	@foreach ($efforts as $effort)
    	 @include('efforts.card')
  	 	@endforeach
  	 	{{ $efforts->links()}}
    </div>

  </div>
@endsection