@extends('layouts.login',['title' => trans('app.user_login')])

@section('content')
    <div class="uk-vertical-align uk-text-right uk-height-1-1" id="login-content" style='margin-right: 10%;'>
        <div class="uk-vertical-align-middle uk-panel" style="text-align: center;">
            
            <h2 style='color:white;'>WELCOME</h2>

            <img src="{{url('images/logo/user.jpg')}}" style="width:80px;height: 75px;" />

            <h3 style="padding-top:10px;vertical-align:center;height:35px;color:white;background-color: #006680;"> Administration Login</h3>

            <form class="uk-form uk-margin-top" method="POST" action="{{ route('auth.login') }}">
                @include('components.alert-danger')
                @csrf

                <div class="uk-form-row">
                    <input name="username" type="text" value="{{ old('username') }}" placeholder="Username" class="uk-width-1-1 uk-form-small" required="required" />
                </div>

                <div class="uk-form-row">
                    <input name="password" type="password" value="{{ old('password') }}" placeholder="Password" class="uk-width-1-1 uk-form-small" required="required" />
                </div>
                <div class="uk-form-row uk-text-left">
                    <!-- <a href=""><span style='color:white;font-size:12px;'>Forgot Password</span></a> -->
                    <button class="uk-width-1-3 uk-button uk-button-primary uk-button-small uk-float-right" style="font-weight: bold;">Login</button>
                </div>
            </form>

        </div>
    </div>
@endsection