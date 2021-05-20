@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="card mt-3">
          <div class="card-body pt-0">
            @include('layouts.error_card_list')
            <div class="card-text">
              <form method="POST" action="{{ route('efforts.update', ['effort' => $effort]) }}">
                @method('PATCH')
                @include('efforts.form')
                <button type="submit" class="btn bg-dark text-white btn-block">更新する</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection