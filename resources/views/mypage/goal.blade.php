    <h3 class="h4 mt-2">
      <a class="text-dark" href="{{ route('goals.show', ['goal' => $goal]) }}">
        {{ $goal->title }}
      </a>
    </h3>
    <div>
      {{ $goal->content }}
    </div>