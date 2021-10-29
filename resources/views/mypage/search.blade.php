@if(isset($goals[0]))
<form method="GET" action="{{route('mypage.show', ['id' => $user->id ])}}" class="form-inline my-2 my-lg-0">
	@csrf
  <input class="form-control mr-sm-2" name="search" type="search" placeholder="検索" aria-label="Search">
  <button class="btn btn-outline-success py-2 my-2 my-sm-0" type="submit">検索</button>
</form>
@endif