<form method="GET" action="{{route('mypage.show', ['id' => $user->id])}}" class="form-inline my-2 my-lg-0">
	@csrf
	@if (isset($cleared_goals[0]) && $goal_label !== '1')
	<button class="btn btn-link text-black p-0" type="submit" name="label" value=1>
		<i class="fas fa-chevron-circle-right mr-1"></i>達成済みの目標をチェックする
	</button>
	@elseif (isset($cleared_goals[0]) && $goal_label === '1')
	<button class="btn btn-link text-black p-0" type="submit" name="label" value=0>
		<i class="fas fa-chevron-circle-right mr-1"></i>進行中の目標に戻る
	</button>
	@endif  
</form>