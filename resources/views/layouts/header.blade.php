<header id="header_main">
    <nav class="uk-navbar uk-height-1-1">
        <div class="brand-logo uk-height-1-1">
            <a href="/">
                <img style='height:50px;width:135px;' src="{{asset('images/logo/logo.png')}}">
            </a>
        </div>
        @if($user = auth()->user())
        <div class="uk-navbar-profile uk-navbar-flip">
            <ul class="uk-navbar-nav">
                <li data-uk-dropdown="{mode:'click',pos:'bottom-left'}" aria-haspopup="true" aria-expanded="false" class="uk-height-1-1">
                    <a href="#!" class="user_action_image" id="dropdown-logout1">
                        <img class="md-user-image" src="{{url('images/avatars/avatar.png')}}" alt="">
                        <p>Welcome, <b>{{ $user->ADMIN_DISPLAY_NAME }}</b></p>
                    </a>
                    <div id="dropdown-logout2" style="background-color:rgb(0,69,131);display:none;" >
                        <ul class="uk-nav js-uk-prevent">
                            <li>
                                <a style="color:white;padding:20px;" href="{{ route('auth.logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                Logout
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
        @endif
    </nav>
</header>

<script>
    $(document).ready(function() {
        $('#dropdown-logout1').click(function(e){
            e.preventDefault();
            $('#dropdown-logout2').stop().slideToggle();
        });
    } );
</script>