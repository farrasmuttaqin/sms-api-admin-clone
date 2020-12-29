@extends('layouts.main',['title'=> 'SMS API ADMIN | MANAGE CREDIT PAGE'])

@push('breadcrumb')
<li class="uk-active" style="color:black;font-weight:bold;">@lang('app.credit_management')</li>
@endpush

@section('content')

    <div style="padding-top:30px;">
        <h3 style="color:#272727;"> Manage Credit </h3>
    </div>

    <div>
        @include('components.alert-danger', ['autoHide' => false])
        @include('components.alert-success', ['autoHide' => false])
        <table style="width:100%;text-align:left;color:black;border-collapse:collapse;" >
            <tr style="border-bottom:2px solid white;">
                <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Username</td>
                <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->USER_NAME}}</td>
            </tr>
            <tr style="border-bottom:2px solid white;">
                <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Client Company</td>
                <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->COMPANY_NAME}}</td>
            </tr>
            <tr style="border-bottom:2px solid white;">
                <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Cobrander ID (User)</td>
                <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->COBRANDER_NAME}}</td>
            </tr>
            <tr style="border-bottom:2px solid white;">
                <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Balance</td>
                <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">@currency($user->CREDIT)</td>
            </tr>
        </table>
        <br>
        <button 
            data-toggle="modal" 
            data-target="#credit_deduct" 
        
        class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:15px;">Deduct</button>
        <button 
            data-toggle="modal" 
            data-target="#credit_top_up" 
        
        class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:15px;">Top Up</button>

        <a href="{{Request::url()}}" >
            <button class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:15px;">Refresh</button>
        </a>
    </div>

    <div style="padding-top:30px;">
        <h4 style="color:#272727;font-weight:bold;"> Credit History </h4>
    </div>

    <table id="credit_table" class="display" class = "c-table-1" style="color:black;">
        <thead class = "c-thead-1">
            <tr>
                <th class = "c-th-td">Fill Date</th>
                <th class = "c-th-td">Reference</th>
                <th class = "c-th-td">Beginning</th>
                <th class = "c-th-td">Mutation</th>
                <th class = "c-th-td">Balance</th>
                <th class = "c-th-td">Value</th>
                <th class = "c-th-td">Payment Date</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($all_credits as $credit)
                <tr>
                    <td class = "c-th-td">{{$credit->CREATED_DATE}}</td>
                    <td class = "c-th-td">{{$credit->TRANSACTION_REF}}</td>
                    <td class = "c-th-td">@currency($credit->PREVIOUS_BALANCE)</td>
                    <td class = "c-th-td">@currency($credit->CREDIT_AMOUNT)</td>
                    <td class = "c-th-td">@currency($credit->CURRENT_BALANCE)</td>
                    <td class = "c-th-td">@currency($credit->CREDIT_PRICE)</td>
                    <td class = "c-th-td" style="text-align: center;">{{$credit->PAYMENT_DATE}}</td>
                    <td class = "c-td-action">
                    <img 
                        data-toggle="modal" 
                        data-target="#credit_detail" 
                        data-reference_code="{{$credit->TRANSACTION_REF}}" 
                        data-requested_by="{{$credit->CREDIT_REQUESTER}}" 
                        data-credit_mutation="{{$credit->CREDIT_AMOUNT}}" 
                        data-price="{{$credit->CREDIT_PRICE}}" 

                        @if($credit->CURRENCY_CODE == "rp")
                            data-currency="Rupiah [IDR]" 
                        @endif

                        data-payment_method="{{$credit->PAYMENT_METHOD}}" 
                        
                        @if(!empty($credit->PAYMENT_DATE))
                            data-payment_status="Paid"
                        @else
                            data-payment_status="Not Paid"
                        @endif

                        data-payment_date="{{$credit->PAYMENT_DATE}}" 
                        data-created_date="{{$credit->CREATED_DATE}}" 
                        data-updated_date="{{$credit->UPDATED_DATE}}" 
                        data-created_by="{{$credit->ADMIN_DISPLAY_NAME}}" 

                        @if(!empty($credit->UPDATED_DATE))
                            data-updated_by="{{$credit->ADMIN_DISPLAY_NAME}}"
                        @else
                            data-updated_by=""
                        @endif

                        data-remark="{{$credit->TRANSACTION_REMARK}}" 

                        style="width:25px;height:25px;cursor:pointer;"
                        src="{{url('images/icon/circle-detail.png')}}" />

                    @if (empty($credit->PAYMENT_DATE) && $credit->PAYMENT_METHOD != 'unspecified')

                        &nbsp &nbsp

                        <img 
                            data-toggle="modal" 
                            data-target="#credit_edit" 
                            data-edited_transaction_ref = "{{$credit->TRANSACTION_REF}}"
                            data-edited_requested_by ="{{$credit->CREDIT_REQUESTER}}"
                            data-edited_credit ="{{$credit->CREDIT_AMOUNT}}"
                            data-edited_price ="{{$credit->CREDIT_PRICE}}"
                            data-edited_currency ="{{$credit->CURRENCY_CODE}}"
                            data-edited_payment_method ="{{$credit->PAYMENT_METHOD}}"
                            data-edited_information="{{$credit->TRANSACTION_REMARK}}" 
                            data-edited_credit_transaction_id="{{$credit->CREDIT_TRANSACTION_ID}}" 

                            style="width:25px;height:25px;cursor:pointer;"
                            src="{{url('images/icon/circle-pencil.png')}}" />

                        &nbsp &nbsp

                    @endif

                    @if (empty($credit->PAYMENT_DATE) && $credit->PAYMENT_METHOD != 'unspecified')
                    <img
                        data-toggle="modal" 
                        data-target="#credit_payment_acknowledgement" 

                        data-ack_credit_transaction_id="{{$credit->CREDIT_TRANSACTION_ID}}"
                        data-ack_transaction_ref="{{$credit->TRANSACTION_REF}}" 
                        data-ack_requested_by="{{$credit->CREDIT_REQUESTER}}" 
                        data-ack_credit="@currency($credit->CREDIT_AMOUNT)" 
                        data-ack_price="@currency($credit->CREDIT_PRICE)" 
                        data-ack_currency="{{$credit->CURRENCY_CODE}}" 
                        
                        data-ack_payment_method="{{$credit->PAYMENT_METHOD}}" 
                        data-ack_information="{{$credit->TRANSACTION_REMARK}}"                         
                        style="width:25px;height:25px;cursor:pointer;" 
                        src="{{url('images/icon/circle-wallet.png')}}" />
                    @endif

                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        
        $(document).ready(function() {
            var table = $('#credit_table').DataTable();
        } );
    </script>
@endsection