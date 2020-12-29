@extends('layouts.main',['title'=> 'SMS API ADMIN | INVOICE PAGE'])

@push('breadcrumb')
<li class="uk-active">@lang('app.invoice_management_profile')</li>
@endpush

@section('content')

    <div style="padding-top:30px;padding-bottom:10px;text-align:center;">
        <h3 style="color:black;font-weight:bold;"> Invoice Profile</h3>
    </div>

    @include('components.alert-danger', ['autoHide' => false])
    @include('components.alert-success', ['autoHide' => false])

    <div class="uk-form-row uk-text-right" style="margin-bottom:20px;">
        <button 
            data-toggle="modal" 
            data-target="#history_create" 
            class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-add-file.png')}}"/></button>
        &nbsp
        
        <a href="{{route('invoice.index.history',['PROFILE_ID'=>$profile->INVOICE_PROFILE_ID])}}">
            <button 
                class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-list.png')}}"/></button>
        </a>
        &nbsp
        
        <button 
            data-toggle="modal" 
            data-target="#profile_edit" 
            data-profile_id= "{{$profile->INVOICE_PROFILE_ID}}"
            data-client= "{{$profile->CLIENT_ID}}"
            data-payment_detail= "{{$profile->INVOICE_BANK_ID}}"
            data-approved_name= "{{$profile->APPROVED_NAME}}"
            data-approved_position= "{{$profile->APPROVED_POSITION}}"
            data-auto_generate = "{{$profile->AUTO_GENERATE}}"

            class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-edit.png')}}"/></button>
    </div>
    
    <table style="width:100%;text-align:left;color:black;border-collapse:collapse;" >
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Customer ID</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$profile->CONTACT_NAME}}</td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Client Name</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$profile->COMPANY_NAME}}</td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Client Address</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$profile->CONTACT_ADDRESS}}</td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">API Users</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                {{$users}}
            </td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Bank Name</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
            {{$profile->BANK_NAME}}
            </td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Account Name</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
            {{$profile->ACCOUNT_NAME}}
            </td>
        </tr>

        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Account Number</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
            {{$profile->ACCOUNT_NUMBER}}
            </td>
        </tr>

        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Auto Generate</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                @if ($profile->AUTO_GENERATE == 0)
                    No
                @else
                    Yes
                @endif
            </td>
        </tr>

        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Approved Name</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
            {{$profile->APPROVED_NAME}}
            </td>
        </tr>

        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Approved Position</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
            {{$profile->APPROVED_POSITION}}
            </td>
        </tr>
    </table>
    <div style="padding-top:30px;padding-bottom:10px;text-align:center;">
        <h3 style="color:black;font-weight:bold;"> Products for Invoice </h3>
    </div>
    <table id="invoice_history_table" class="display" class = "c-table-1" style="color:black;">
        <thead class = "c-thead-1">
            <tr>
                <th class = "c-th-td">Product Name</th>
                <th class = "c-th-td">Based On Billing Report</th>
                <th class = "c-th-td">Quantity</th>
                <th class = "c-th-td">Unit Price (IDR)</th>
                <th class = "c-th-td" style="text-align: center;">
                    <button  
                        data-toggle="modal" 
                        data-target="#product_create" 
                        class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-add.png')}}"/>&nbsp Add New</button>
                </th>
            </tr>
        </thead>
        <tbody style="text-align: center;">
            @foreach ($all_products as $product)
            <tr>
                <td class = "c-th-td">{{$product->PRODUCT_NAME}}</td>
                <td class = "c-th-td">
                    @if ($product->USE_REPORT == 0)
                        No
                    @else
                        Yes
                    @endif
                </td>
                <td class = "c-th-td">
                    {{$product->QTY}}
                </td>
                <td class = "c-th-td">@currency($product->UNIT_PRICE)</td>
                <td class = "c-th-td" style="text-align: center;">
                    <button 
                        data-toggle="modal" 
                        data-target="#product_edit" 
                        data-edited_product_id= "{{$product->PRODUCT_ID}}"
                        data-edited_product_name= "{{$product->PRODUCT_NAME}}"
                        data-edited_use_period= "{{$product->IS_PERIOD}}"
                        data-edited_unit_price= "{{$product->UNIT_PRICE}}"
                        data-edited_quantity= "{{$product->QTY}}"
                        data-edited_use_report = "{{$product->USE_REPORT}}"
                        data-edited_report_name = "{{$product->REPORT_NAME}}"

                        class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-edit.png')}}"/></button>
                    &nbsp
                    <a href="{{route('product.delete',['PRODUCT_ID'=>$product->PRODUCT_ID, 'INVOICE_PROFILE_ID'=> $profile->INVOICE_PROFILE_ID])}}" onclick="return confirm('are you sure delete this product?');">
                        <button id="changeDetails" 
                            data-toggle="modal" 
                            data-target="#user_edit" 
                            class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-remove.png')}}"/></button>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#invoice_history_table').DataTable();
            
        } );
    </script>
@endsection