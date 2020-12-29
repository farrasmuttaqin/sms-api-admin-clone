@extends('layouts.main',['title'=> 'SMS API ADMIN | INVOICE PAGE'])

@push('breadcrumb')
<li class="uk-active">@lang('app.invoice_management')</li>
<li class="uk-active" style="color:black;font-weight:bold;">@lang('app.invoice_detail')</li>
@endpush

@section('content')
	
	@include('components.alert-danger', ['autoHide' => false])
    @include('components.alert-success', ['autoHide' => false])

	<div class="uk-form-row uk-text-left" style="margin-top:30px;margin-bottom:20px;">
            <a href="{{route('invoice.delete',['HISTORY_ID'=>$profile->INVOICE_HISTORY_ID])}}" onclick="return confirm('Delete this invoice?');">
                <button 
                class="uk-button uk-float-right" style="color:black;font-weight:500;margin-left: 10px;"><img width="20px" src="{{url('images/icon/icon-remove.png')}}"/></button>
            </a>
            &nbsp

            <a href="{{route('invoice.lock',['HISTORY_ID'=>$profile->INVOICE_HISTORY_ID])}}" onclick="return confirm('Lock this invoice?');">
                <button 
                class="uk-button uk-float-right" style="color:black;font-weight:500;margin-left: 10px;"><img width="20px" src="{{url('images/icon/icon-lock.png')}}"/></button>
            </a>
            &nbsp
            
            <a href="{{route('preview.invoice',['HISTORY_ID'=>$profile->INVOICE_HISTORY_ID])}}" target="_blank">
                <button
                class="uk-button uk-float-right" style="color:black;font-weight:500;margin-left: 10px;"><img width="20px" src="{{url('images/icon/icon-preview.png')}}"/></button>
            </a>

            <?php
                $startDate = strtotime($profile->START_DATE);
                $dueDate = strtotime($profile->DUE_DATE);
                $sec = $dueDate-$startDate;

                $term_of_payment = $sec/86400;
            ?>
            
            <button
            data-toggle="modal" 
            data-target="#history_edit" 
            data-edited_invoice_history_id = "{{$profile->INVOICE_HISTORY_ID}}"
            data-edited_invoice_number = "{{$profile->INVOICE_NUMBER}}"
            data-edited_invoice_date = "{{$profile->START_DATE}}"
            data-edited_term_of_payment = "{{$term_of_payment}}"
            data-edited_due_date = "{{$profile->DUE_DATE}}"
            data-edited_ref_number = "{{$profile->REF_NUMBER}}"
            class="uk-button uk-float-right" style="color:black;font-weight:500;margin-left: 10px;"><img width="20px" src="{{url('images/icon/icon-edit.png')}}"/></button>
    </div>

	<div id="title" style="color:black;">
	    INVOICE
        @if ($profile->INVOICE_TYPE != "ORIGINAL" && $profile->INVOICE_TYPE != "FINAL")
            <br> <font style="font-size:12px;"> ({{$profile->INVOICE_TYPE}}) </font>
        @endif
	</div>
    <br>
	<main style="color:black;">
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">BILL TO:</div>
                <h2 class="name" style="color:black;">{{$profile->COMPANY_NAME}}</h2>
                <div class="address">{{$profile->CONTACT_EMAIL}} | {{$profile->CONTACT_PHONE}}</div>
            </div>
            <div id="invoice">
                <table border="0" cellspacing="0" cellpadding="0">
                    <tr id="customerId">
                        <td class="bold" style="width: 120px;">Customer ID</td>
                        <td class="bold">:</td>
                        <td class="bold">{{$profile->CONTACT_NAME}}</td>
                    </tr>
                    <tr>
                        <td>Invoice No</td>
                        <td>:</td>
                        <td>{{$setting->INVOICE_NUMBER_PREFIX}} {{$profile->INVOICE_NUMBER}}</td>
                    </tr>
                    <tr>
                        <td>Invoice Date</td>
                        <td>:</td>
                        <td>{{$profile->START_DATE}}</td>
                    </tr>
                    <tr>
                        <td>Due Date</td>
                        <td>:</td>
                        <td>{{$profile->DUE_DATE}}</td>
                    </tr>
                    <tr>
                        <td>Term of Payment</td>
                        <td>:</td>
                        <?php
                            $startDate = strtotime($profile->START_DATE);
                            $dueDate = strtotime($profile->DUE_DATE);
                            $sec = $dueDate-$startDate;

                            $term_of_payment = $sec/86400;
                        ?>
                        <td>{{$term_of_payment}} days</td>
                    </tr>
                    <tr>
                        <td>Ref. No</td>
                        <td>:</td>
                        <td>{{$profile->REF_NUMBER}}</td>
                    </tr>
                </table>
            </div>
        </div>
        <br>
        <table class="item" border="0" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th class="no">No.</th>
                    <th class="desc">DESCRIPTION</th>
                    <th class="qty">Quantity</th>
                    <th class="unit">Unit Price (IDR)</th>
                    <th class="total">Amount (IDR)</th>
                    <th class="unit">
                        <a data-toggle="modal" 
                            data-target="#product_create" 
                            href="#" class="form-button" title="Add New Product">
                            <img title="Register" src="{{url('images/icon/icon-add.png')}}" class="form-button-image" alt="" />
                        </a>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $no=1;
                    $subTotal = 0;
                ?>

                @foreach ($all_products as $product)
                    @if(empty($product->REPORT_NAME))
                    <tr>
                        <td class="no">
                            <?php 
                                echo $no++; 
                                $date = $product->START_DATE;
                                $date = date('Y-m-d', strtotime($date. ' - 1 month'));
                                $date = strtotime($date);
                            ?>
                        </td>
                        <td class="desc">
                            {{$product->PRODUCT_NAME}} period 1 - {{cal_days_in_month(CAL_GREGORIAN, date('m',$date), date('Y',$date))}} {{date('F',$date)}}
                        </td>
                        <td class="qty">
                            {{$product->QTY}}
                        </td>
                        <td class="unit">
                            @currency($product->UNIT_PRICE)
                        </td>
                        <td class="total">
                            @currency($product->UNIT_PRICE * $product->QTY)
                        </td>
                        <td class="type-action" style="text-align: center;">
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

                            <a href="{{route('product.delete.history',['PRODUCT_ID'=>$product->PRODUCT_ID, 'HISTORY_ID'=> Request::segment(3)])}}" onclick="return confirm('are you sure delete this product?');">
                                <button 
                                        class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-remove.png')}}"/></button>
                            </a>
                        </td>
                        <?php
                            $subTotal = $subTotal + ($product->UNIT_PRICE * $product->QTY)
                        ?>
                    </tr>
                    @endif
                @endforeach
                <?php
                    $start = 0;
                ?>
                @for($i=0; $i<count($counterEachProduct); $i++)
                    @if ($start != 0)
                        <?php
                            $counterEachProduct[$i] += $counterEachProduct[$i-1];
                        ?>
                    @endif
                    
                    @for($o=$start; $o<$counterEachProduct[$i]; $o++)
                        <tr>
                            <td class="no">
                                <?php echo $no++; ?>
                            </td>
                            <td class="desc">
                                {{$productNamePrefix[$o]}} ({{$productName[$o]}}) period 1 - {{cal_days_in_month(CAL_GREGORIAN, date('m',$productDate[$o]), date('Y',$productDate[$o]))}} {{date('F',$productDate[$o])}}
                            </td>
                            <td class="qty">
                                {{$qtyArr[$o]}}
                            </td>
                            <td class="unit">
                                @currency($unitPrice[$o])
                            </td>
                            <td class="total">
                                @currency($unitPrice[$o] * $qtyArr[$o])
                            </td>
                            <td class="type-action" style="text-align: center;font-size:12px;">
                                    Automatic from Report
                            </td>
                            <?php
                                $subTotal = $subTotal + ($unitPrice[$o] * $qtyArr[$o])
                            ?>
                        </tr>
                    @endfor
                    <?php
                        $start += $counterEachProduct[$i];
                    ?>
                @endfor

                @if($no==1)
                    <tr>
                        <td class="no" colspan="6">
                            No Data
                        </td>
                    </tr>
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td>SUB TOTAL :</td>
                    <td>
                        @currency($subTotal)
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>VAT :</td>
                    <td>
                        <?php $vat = ($subTotal * 0.1); ?>
                        @currency($vat)
                    </td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>TOTAL :</td>
                    <td>
                        @currency($subTotal+$vat)
                    </td>
                </tr>
            </tfoot>
        </table>
        <div class="word-total">
            Amount In Words:
            <?php
                $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            ?>
            <i><b>{{$digit->format($subTotal+$vat)}} rupiah</b></i>
        </div>
        <div id="payment">
            <b><u>Payment Details:</u></b>
            <table border="0" cellspacing="0" cellpadding="2" id="bank-acount" style="width:1000px;">
                <tr>
                    <td>Bank Name</td>
                    <td>:</td>
                    <td>{{$profile->BANK_NAME}}</td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td>:</td>
                    <td>{{$profile->ADDRESS}}</td>
                </tr>
                <tr>
                    <td>Account Name</td>
                    <td>:</td>
                    <td>{{$profile->ACCOUNT_NAME}}</td>
                </tr>
                <tr>
                    <td>Account Number</td>
                    <td>:</td>
                    <td>{{$profile->ACCOUNT_NUMBER}}</td>
                </tr>
            </table>
        </div>
        
        <div id="authorized" class="clearfix {if not $profile.approvedName}text-right{/if}">
            <div class="signature-item" style="float: right; margin-right: 60px;">
                <div class="title">
                    Authorized Signature,
                </div>
                <div class="signature">
                </div>
                <div class="signature-name" style="padding-bottom:5px;" >
                    {{$setting->AUTHORIZED_NAME}} &nbsp;
                </div>
                <div class="signature-position">
                    {{$setting->AUTHORIZED_POSITION}} &nbsp;
                </div>
            </div>
            @if (!empty($profile->APPROVED_NAME))
            <div class="signature-item" style="float: left;margin-left: 60px;">
                <div class="title">
                    Approved by,
                </div>
                <div class="signature">
                </div>
                <div class="signature-name" style="padding-bottom:5px;" > 
                    {{$profile->APPROVED_NAME}} &nbsp;
                </div>
                <div class="signature-position">
                    {{$profile->APPROVED_POSITION}} &nbsp;
                </div>
            </div>
            @endif
        </div>
        <br><br>
        <div id="notices" style="padding-top: 50px;margin-bottom: 100px;">
            <b>Notes:</b>
            <div class="notice">
                {{$setting->NOTE_MESSAGE}}
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#invoice_history_table').DataTable();
            $('#invoice-profile-table').DataTable();
            $('#invoice-setting-table').DataTable();

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#invoice-history-form').stop().slideToggle();
            });

            $('#arrow2').click(function(e){
                e.preventDefault();
                $('#invoice-profile-form').stop().slideToggle();
            });
            
        } );
    </script>
@endsection