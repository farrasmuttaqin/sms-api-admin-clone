<!DOCTYPE html>
<html lang="en" class="uk-height-1-1">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{url('images/logo/icon.png')}}">

    <link rel="stylesheet" href="{{url('ui-kit/lineAwesome/css/line-awesome.min.css')}}">
    <script type="text/javascript" src="{{url('dist/js/jquery-3.5.1.js')}}"></script>

    <script type="text/javascript" src="{{url('dist/js/bootstrap.js')}}"></script>

    <link href="{{url('dist/css/select2.css')}}" rel="stylesheet" />

    <script type="text/javascript" src="{{url('dist/js/select2.js')}}"></script>
    
    <link href="{{url('dist/css/custom_basic.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{url('dist/js/custom_basic.js')}}"></script>

    <link href="{{url('dist/css/datepicker.css')}}" rel="stylesheet">
    <script type="text/javascript" src="{{url('dist/js/date-picker.js')}}"></script>

    <link href="{{url('dist/css/custom_advance.css')}}" rel="stylesheet">

    <link href="{{url('dist/css/invoice.css')}}" rel="stylesheet">


    <title>{!! $title ?? env('APP_NAME',"SMS API ADMIN") !!}</title>
    @stack('styles')
</head>

<body class="sidebar_active uk-height-1-1">
    @include('layouts.header')

    <div class="section-container">
        <div class="section-content">
            <div class="content">
                @include('layouts.section-head')
                @yield('content')
            </div>
        </div>
        <div class="section-footer uk-text-small">
          <img src="{{ asset('images/logo/firstwap.svg') }}" alt=""> &copy; {{ date('Y') }} - <a href="mailto:techsupport@1rstwap.com">techsupport@1rstwap.com</a> | +62 21 2295 0041
        </div>
        @include('layouts.sidemenu')
    </div>
    @include('components.lang')

    @if (request()->segment(1) === null)
        @include('client.modal')
    @elseif (request()->segment(1) === 'cobrander')
        @include('cobrander.modal_cobrander')
    @elseif (request()->segment(1) === 'agent')
        @include('cobrander.modal_agent')
    @elseif (request()->segment(1) === 'user' && request()->segment(2) == 'detail')
        @include('user.modal.modal_client_details')
    @elseif (request()->segment(1) === 'user' && request()->segment(2) == 'edit')
        @include('user.modal.modal_virtual')
        @include('user.modal.modal_sender')
        @include('user.modal.modal_user')
        @include('user.modal.modal_client_details')
    @elseif (request()->segment(1) === 'user')
        @include('user.modal.modal_download_billing_reports')
        @include('user.modal.modal_create_billing')
    @elseif (request()->segment(1) === 'billing')
        @include('billing.modal.modal_billing_profile')
        @include('billing.modal.modal_report_group')
        @include('billing.modal.modal_tiering_group')
    @elseif (request()->segment(1) === 'credit')
        @include('user.modal.modal_credit')
    @elseif (request()->segment(1) == 'invoice' && request()->segment(2) === 'profile')
        @include('invoice.modal.modal_invoice_profile')
        @include('invoice.modal.modal_invoice_product')
        @include('invoice.modal.modal_invoice_history')
    @elseif (request()->segment(1) == 'invoice' && request()->segment(2) === 'edit')
        @include('invoice.modal.modal_invoice_history')
        @include('invoice.modal.modal_invoice_product_crud')
    @elseif (request()->segment(1) === 'invoice')
        @include('invoice.modal.modal_invoice_bank')
        @include('invoice.modal.modal_invoice_setting')
        @include('invoice.modal.modal_invoice_history')
        @include('invoice.modal.modal_download_all_invoice')
    @endif

    <script>
    $(document).ready(function(){
        $("#btn-swap").on("click", function() {
            "0px" == $("#sidebar").css("left") ? ($("#sidebar").animate({
                left: "-210px"
            }, 250), $(".section-content,.section-footer").animate({
                left: "0"
            }, 50)) : ($("#sidebar").animate({
                left: "0"
            }, 300), $(".section-content").animate({
                left: "210px"
            }, 50), $(".section-footer").animate({
                left: "210px"
            }, 250)), setTimeout(function() {
                $(window).trigger("resize")
            }, 250)
        }), setTimeout(function() {
            $(".uk-alert.uk-alert-hide").hide("slow")
        }, 5e3)
    });
    </script>

</body>

</html>