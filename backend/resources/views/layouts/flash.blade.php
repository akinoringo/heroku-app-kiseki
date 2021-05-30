@if (session('flash_message'))
    <div class="flash_message alert-{{session('color')}} text-center py-3 my-0 mb30">
        {{ session('flash_message') }}
    </div>
    @if (session()->exists('badge_message'))
    <div class="flash_message alert-{{session('badge_color')}} text-center py-3 my-0 mb30">
        {{ session('badge_message') }}
    </div> 
    @endif

    @if (session()->exists('sns_message'))
    <div class="flash_message alert-info text-center py-3 my-0 mb30">
        {{ session('sns_message') }}        
        <social-sharing id="social" url="http://app-kiseki.com/"
                          title="『Kiseki ~日々の軌跡を綴ろう~ 』で{{session('share_content')}} 「{{session('share_message')}}」"
                          description="テスト"
                          hashtags={{session('share_hashtags')}}
                          inline-template>      
          <network network="twitter">
            <button type="button" class="btn bg-info p-2 shadow-none btn-ignore">
              <i class="fab fa-x fa-twitter"></i>
            </button>
          </network>   
                                 
        </social-sharing>
    </div> 
    @endif    

    @if (session()->exists('deadline_message'))
    <div class="flash_message alert-{{session('deadline_color')}} text-center py-3 my-0 mb30">
        {{ session('deadline_message') }}
    </div>  
    @endif      
@endif