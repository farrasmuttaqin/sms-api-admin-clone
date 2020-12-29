<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <link rel="icon" href="<?php echo public_path('images/logo/icon.png'); ?>">
    <link href="{{public_path('dist/css/invoice.css')}}" rel="stylesheet">

</head>
<body>
    <main>
        <header class="clearfix">
            <div id="logo">
                <img height="68" width="200" src="{{public_path('images/icon/firstwap.png')}}">
            </div>
            <div id="company">
                <h2 class="name">PT. FIRST WAP INTERNATIONAL</h2>
                <div>Gedung Millennium Centennial Center, 3rd Floor</div>
                <div>JL. Jend. Sudirman Kav 25 Karet Setiabudi</div>
                <div>Jakarta Selatan DKI Jakarta 12920</div>
                <div>Phone : +62-21-229-50041</div>
                <div>Fax : +62-21-229-50042</div>
                <div>Website: <a href="www.1rstwap.com">www.1rstwap.com</a></div>
            </div>
        </header>
        <br>
        <div id="title">
            INVOICE
            @if ($profile->INVOICE_TYPE != "ORIGINAL" && $profile->INVOICE_TYPE != "FINAL")
                <br> <font style="font-size:12px;"> ({{$profile->INVOICE_TYPE}}) </font>
            @endif
        </div>
        <br>
        <div id="details" class="clearfix">
            <div id="client">
                <div class="to">BILL TO:</div>
                <h2 class="name">{{$profile->COMPANY_NAME}}</h2>
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
                            <?php
                                $subTotal = $subTotal + ($unitPrice[$o] * $qtyArr[$o])
                            ?>
                        </tr>
                    @endfor
                    <?php
                        $start += $counterEachProduct[$i];
                    ?>
                @endfor
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
    </main>
    <div class="footer">
        <div id="payment">
            <b><u>Payment Details:</u></b>
            <table border="0" cellspacing="0" cellpadding="2" id="bank-acount">
                <tr>
                    <td width="99">Bank Name</td>
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
        <br><br>

       <!--  @if($no<=4) style="position:absolute;" @elseif($no>4) style="position:relative;" @endif  -->
        <div id="authorized" class="clearfix" @if($no<=5) style="position:absolute;" @elseif($no==6) style="page-break-before:always;position:relative;" @elseif($no>6) style="position:relative;" @endif>
            @if (!empty($profile->APPROVED_NAME))
            <div class="signature-item" style="float: left;">
                <div class="title">
                    Approved by,
                </div>
                <div class="signature">
                </div>
                <div class="signature-name" style="padding-bottom:10px;">
                    {{$profile->APPROVED_NAME}} &nbsp;
                </div>
                <div class="signature-position">
                    {{$profile->APPROVED_POSITION}} &nbsp;
                </div>
            </div>
            @endif
            <div class="signature-item" style="float: right;">
                <div class="title">
                    Authorized Signature,
                </div>
                <div class="signature">
                </div>
                <div class="signature-name" style="padding-bottom:10px;">
                    {{$setting->AUTHORIZED_NAME}} &nbsp;
                </div>
                <div class="signature-position">
                    {{$setting->AUTHORIZED_POSITION}} &nbsp;
                </div>
            </div>
        </div>
        <div id="notices" @if ($no<2) style="margin-top:180px;" @elseif($no>=2 && $no<=5) style="page-break-before: always;" @elseif($no>5) style="margin-top:100px; @endif">
            <b>Notes:</b>
            <div class="notice">
                {{$setting->NOTE_MESSAGE}}
            </div>
        </div>
    </div>
</body>
