<!DOCTYPE html>
<html lang="en" class="uk-height-1-1">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <link rel="icon" href="{{url('images/logo/icon.png')}}">
    <link href="{{url('dist/css/login.css')}}" rel="stylesheet">
    <title>{!! $title ?? env('APP_NAME',"SMS API ADMIN") !!}</title>
    
    <style>
        #background{
              position: fixed; 
              top: 0; 
              left: 0; 
              right: 0;
                
              /* Preserve aspet ratio */
              min-height: 100%;
        }
    </style>

</head>

<body class="login-page uk-height-1-1">

    <div id="background" class="section-content uk-height-1-1" style="background-image: url({{ url('images/bg/bg-login2.jpg') }});">
        @yield('content')
    </div>

    <div class="section-footer uk-text-small">
        <img src="{{ asset('images/logo/firstwap.svg') }}" alt=""> &copy; {{ date('Y') }} - <a style="color:black;" href="mailto:techsupport@1rstwap.com">techsupport@1rstwap.com</a> | +62 21 2295 0041
    </div>


</body>

</html>