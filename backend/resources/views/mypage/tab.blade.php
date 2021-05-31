<ul class="nav nav-pills mb-3 mt-2" id="pills-tab" role="tablist">
  <li class="nav-item text-center">
    <a class="nav-link active" id="pills-efforts-tab" data-toggle="pill" href="#pills-efforts" role="tab" aria-controls="pills-efforts" aria-selected="true">
      軌跡一覧
    </a>
  </li>   
  <li class="nav-item text-center">
    <a class="nav-link" id="pills-goals-tab" data-toggle="pill" href="#pills-goals" role="tab" aria-controls="pills-goals" aria-selected="true">
      目標一覧
    </a>
  </li>   
  <li class="nav-item text-center">
    <a class="nav-link" id="pills-graph-tab" data-toggle="pill" href="#pills-graph" role="tab" aria-controls="pills-graph" aria-selected="true">
      積み上げグラフ
    </a> 
  </li>   
</ul>

<div class="tab-content" id="pills-tabContent">
  <div class="tab-pane fade show active" id="pills-efforts" role="tabpanel" aria-labelledby="pills-efforts-tab">
    @foreach ($efforts as $effort)
      @include('efforts.card')
    @endforeach
    {{ $efforts->links() }}
  </div>   
  <div class="tab-pane fade" id="pills-goals" role="tabpanel" aria-labelledby="pills-goals-tab">
    @foreach ($goals as $goal)
      @include('goals.card')
    @endforeach    
  </div> 
  <div class="tab-pane fade graph-area-responsive" id="pills-graph" role="tabpanel" aria-labelledby="pills-graph-tab">
    @if ($goals->isNotEmpty())
    <div>         
      <div class="graph-area">        
        <effort-chart userid='@json($id)'></effort-chart>
      </div>
    </div>
    @endif
  </div>    
</div>