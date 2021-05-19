@extends('layouts.app')

@section('content')
  <div class="container">
    @include('efforts.card')

    <div class="row p-3">   
      <div class="col-12">   
        @foreach ($effort->comments as $comment)
         @include('comments.card')
        @endforeach    	
      </div>

      <!-- コメント機能 -->
      @if ( Auth::check() )
      <div class="col-12 mt-2">
        <div class="card pt-2">
          <div class="card-body pt-0">
            @include('layouts.error_card_list')
            <div class="card-text">
              <form method="POST" action="{{route('comments.store')}}">
                @csrf
                <input type="hidden" name="effort_id" value="{{$effort->id}}">
								<div class="form-group mt-2">

								  <textarea name="content" required class="form-control" rows="3" placeholder="200文字以内で入力してください"></textarea>
								</div>
                <button type="submit" class="btn bg-primary mb-0 text-white">コメントする</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      @endif
      <!-- コメント機能ここまで -->

    </div>     
  </div>

@endsection