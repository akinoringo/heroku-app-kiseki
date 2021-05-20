@if (session('flash_message'))
    <div class="flash_message alert-{{session('color')}} text-center py-3 my-0 mb30">
        {{ session('flash_message') }}
    </div>
    @if (session()->exists('badge_message'))
    <div class="flash_message alert-{{session('badge_color')}} text-center py-3 my-0 mb30">
        {{ session('badge_message') }}
    </div> 
    @endif
    @if (session()->exists('deadline_message'))
    <div class="flash_message alert-{{session('deadline_color')}} text-center py-3 my-0 mb30">
        {{ session('deadline_message') }}
    </div>  
    @endif      
@endif