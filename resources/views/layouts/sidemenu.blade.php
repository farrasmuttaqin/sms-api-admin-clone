<aside id="sidebar">
    <div class="sidebar_header">
        <span class="uk-text-contrast uk-text-large">Menu</span>
        <div class="uk-clearfix"></div>
    </div>
    <div class="sidebar_menu">
        <ul class="uk-nav uk-nav-parent-icon" data-uk-nav>
            <li class="{{request()->segment(1) === null ? 'uk-active' : ''}}">
                <a href="/"><img src="{{url('images/icon/icon-client.png')}}"/> &nbsp Client Management</a>
            </li>
            
            <li class="{{ request()->segment(1) === 'user' ? 'uk-active' : ''}}">
                <a href="{{route('user')}}"><img src="{{url('images/icon/icon-user.png')}}"/> &nbsp User Management</a>
            </li>
            
            
            <li class="{{ request()->segment(1) === 'billing' ? 'uk-active' : ''}}">
                <a href="{{route('billing')}}"><img src="{{url('images/icon/icon-history.png')}}"/> &nbsp Billing Management</a>
            </li>

            <li class="{{ request()->segment(1) === 'invoice' ? 'uk-active' : ''}}">
                <a href="{{route('invoice')}}"><img width="16px" src="{{url('images/icon/icon-invoice.png')}}"/> &nbsp Invoice Management</a>
            </li>

            <li class="{{ request()->segment(1) === 'cobrander' || request()->segment(1) === 'agent' ? 'uk-active' : ''}}">
                <a href="#!" id ="dropdown-c-management-1"><img src="{{url('images/icon/icon-virtualnumber.png')}}"/> &nbsp Cobrander <i style="font-size:16px;" class="arrow-down las la-angle-down"></i></a>
                <ul id ="dropdown-c-management-2" class="uk-nav uk-nav-parent-icon" {{ request()->segment(1) === 'cobrander' || request()->segment(1) === 'agent' ? 'style=display:block;' : 'style=display:none;'}} >
                    <li>
                        <a {{request()->segment(1) === 'agent' ? 'style=font-weight:bold;' : ''}} href="{{route('agent')}}"> Agent </a>
                    </li>
                    <li>
                        <a {{request()->segment(1) === 'cobrander' ? 'style=font-weight:bold;' : ''}} href="{{route('cobrander')}}"> Cobrander </a>
                    </li>
                </ul>
                
            </li>
            
        </ul>
    </div>
</aside>

<script>
    $(document).ready(function() {
        $('#dropdown-c-management-1').click(function(e){
            e.preventDefault();
            $('#dropdown-c-management-2').stop().slideToggle();
        });
    } );
</script>