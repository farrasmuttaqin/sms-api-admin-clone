@extends('layouts.main',['title'=> 'SMS API ADMIN | INVOICE PAGE'])

@push('breadcrumb')
<li class="uk-active" style="color:black;font-weight:bold;">@lang('app.invoice_management')</li>
@endpush

@section('content')

    <div style="padding-top:30px;padding-bottom:10px;">
        <h3 style="color:#272727;"> Invoice Management <span style="font-weight: bold;"></span> </h3>
    </div>

    @include('components.alert-danger', ['autoHide' => false])
    @include('components.alert-success', ['autoHide' => false])

    <ul role="tablist" class="nav nav-tabs bs-adaptive-tabs" id="myTab" tabindex="1">
        <li class="active" role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab1-tab" href="#tab1"><label><img src="{{url('images/icon/icon-money.png')}}"/> &nbsp Invoice History</label></a></li>
        <li role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab2-tab" href="#tab2"><label><img src="{{url('images/icon/icon-profile.png')}}"/> &nbsp Invoice Profile</label></a></li>
        <li role="presentation" style="padding:2px 0px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab3-tab" href="#tab3"><label><img src="{{url('images/icon/icon-setting.png')}}"/> &nbsp Settings</label></a></li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div aria-labelledby="tab1-tab" id="tab1" class="tab-pane fade in active" role="tabpanel">
            
            <div class= "c-div-1">
                <div class = "c-div-2">
                    <h3 id = "arrow" style="color:white;">Create Invoice History<i class="arrow-down las la-angle-down"></i></h3>
                    <form id="invoice-history-form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('create.invoice') }}" autocomplete="off" style="display:none;">
                        @csrf

                        <hr style="color:white;"></hr>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Invoice Profile</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select name="added_invoice_profile_id" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_profile')</option>
                                    @foreach ($all_invoice_profiles as $profile)
                                        <option value="{{$profile->INVOICE_PROFILE_ID}}">{{$profile->COMPANY_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="uk-width-1-6 c-input-div-1"></div>
                            <div class="uk-width-1-8" style="padding-right:10px;"><h6 class = "h-header">Invoice Number</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="number" value="{{$setting->LAST_INVOICE_NUMBER}}" name="added_invoice_number" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Invoice Date</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="text" id="added_invoice_date2" value="{{old('added_invoice_date')}}" name="added_invoice_date" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                            <div class="uk-width-1-6 c-input-div-1"></div>
                            <div class="uk-width-1-8" style="padding-right:10px;"><h6 class = "h-header">Term of Payment</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="number" id="added_term_of_payment2" value="{{$setting->PAYMENT_PERIOD}}" name="added_term_of_payment" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Due Date</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="text" id="added_due_date2"  value="{{old('added_due_date')}}" name="added_due_date" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" readonly required/></div>
                            <div class="uk-width-1-6 c-input-div-1"></div>
                            <div class="uk-width-1-8" style="padding-right:10px;"><h6 class = "h-header">Ref Number</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="text" id="added_ref_number2" name="added_ref_number"  value="{{old('added_ref_number')}}" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" /></div>
                        </div>
                
                        <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                            <button type="submit" class="uk-button uk-float-right" style="color:black;font-weight:500;">Save</button>
                            <button id="cancel" type="reset" class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:20px;">Cancel</button>
                        </div>

                        <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                        </div>
                    </form>          
                </div>
            </div>
            <br><br>
            <table id="invoice_history_table" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td" style="width: 5%;">Invoice Number</th>
                        <th class = "c-th-td" style="width: 25%;">Company Name</th>
                        <th class = "c-th-td" style="width: 10%;">Invoice Date</th>
                        <th class = "c-th-td" style="width: 10%;">Due Date</th>
                        <th class = "c-th-td" style="width: 10%;">Status</th>
                        <th class = "c-th-td" style="width: 10%;">Type</th>
                        <th class = "c-th-td" style="width: 30%;">Action(s)</th>
                    </tr>
                </thead>
                <tbody style="text-align: center;">
                    @foreach($all_history as $history)
                    <tr data-history="@if ($history->STATUS == 0) Unlocked @else Locked @endif">
                        <td class = "c-th-td" style="width: 5%;">{{$history->INVOICE_NUMBER}}</td>

                        <?php 
                            $temp = explode("_",$history->FILE_NAME);
                        ?>

                        <td class = "c-th-td" style="width: 25%;">
                            @if (empty($temp[1]))
                                No Data
                            @else
                                {{$temp[1]}}
                            @endif
                        </td>
                        <td class = "c-th-td" style="width: 10%;">{{$history->START_DATE}}</td>
                        <td class = "c-th-td" style="width: 10%;">{{$history->DUE_DATE}}</td>
                        <td class = "c-th-td" style="width: 10%;">
                            @if ($history->STATUS == 0)
                                Unlocked
                            @else
                                Locked
                            @endif
                        </td>
                        <td class = "c-th-td" style="width: 10%;">
                            {{$history->INVOICE_TYPE}}
                        </td>
                        <td class = "c-th-td" style="width: 30%;">

                            @if ($history->STATUS == 0)

                                <a href="{{route('preview.invoice',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}" target="_blank">
                                    <button 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-preview.png')}}"/></button>
                                </a>
                                &nbsp
                                <a href="{{route('invoice.index.edit',['HISTORY_ID'=> $history->INVOICE_HISTORY_ID])}}">
                                    <button 
                                        class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-edit.png')}}"/></button>
                                </a>
                                &nbsp
                                <a href="{{route('invoice.lock',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}" onclick="return confirm('Lock this invoice?');">
                                    <button 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-lock.png')}}"/></button>
                                </a>
                                &nbsp
                                <a href="{{route('invoice.delete',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}" onclick="return confirm('Delete this invoice?');">
                                    <button 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-remove.png')}}"/></button>
                                </a>

                            @else

                                <a href="{{route('preview.invoice',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}" target="_blank">
                                    <button 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-preview.png')}}"/></button>
                                </a>

                                &nbsp
                                <a href="{{route('download.invoice',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}">
                                    <button 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-download.png')}}"/></button>
                                </a>

                                &nbsp

                                <a href="{{route('copy.invoice',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}" onclick="return confirm('Copy this invoice?');">
                                    <button 
                                        class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-copy.png')}}"/></button>
                                </a>

                                &nbsp

                                <a href="{{route('revise.invoice',['HISTORY_ID'=>$history->INVOICE_HISTORY_ID])}}" onclick="return confirm('Revise this invoice?');">
                                    <button 
                                        class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-revise.png')}}"/></button>
                                </a>

                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div aria-labelledby="tab2-tab" id="tab2" class="tab-pane" role="tabpane2">
            <div class= "c-div-1">
                <div class = "c-div-2">
                    <h3 id = "arrow2" style="color:white;">Create Invoice Profile<i class="arrow-down las la-angle-down"></i></h3>
                    <form id="invoice-profile-form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('profile.create') }}" autocomplete="off" style="display:none;">
                        @csrf

                        <hr style="color:white;"></hr>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Client</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select name="added_client" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_client')</option>
                                    @foreach ($all_clients as $client)
                                        <option value="{{$client->CLIENT_ID}}">{{$client->COMPANY_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="uk-width-1-6 c-input-div-1"></div>
                            <div class="uk-width-1-8" style="padding-right:10px;"><h6 class = "h-header">Payment Detail</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select name="added_payment_detail" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_bank')</option>
                                    @foreach ($all_banks as $bank)
                                        <option value="{{$bank->INVOICE_BANK_ID}}">{{$bank->BANK_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Approved Name</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="text" value="{{old('added_approved_name')}}" name="added_approved_name" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;"/></div>
                            <div class="uk-width-1-6 c-input-div-1"></div>
                            <div class="uk-width-1-8"><h6 class = "h-header">Is Auto Generate?</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <input type="radio" name="added_auto_generate" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" name="added_auto_generate" value="0" required/> <label for="No" class="radio-label">No</label>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8" style="padding-right:10px;"><h6 class = "h-header">Approved Position</h6></div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="text" value="{{old('added_approved_position')}}" name="added_approved_position" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;"/></div>
                            
                        </div>
                
                        <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                            <button type="submit" class="uk-button uk-float-right" style="color:black;font-weight:500;">Save</button>
                            <button id="cancel" type="reset" class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:20px;">Cancel</button>
                        </div>

                        <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                        </div>
                    </form>          
                </div>
            </div>
            <br><br>
            <table id="invoice-profile-table" class="display" class = "c-table-1" style="color:black;text-align:center;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Customer ID</th>
                        <th class = "c-th-td">Company Name</th>
                        <th class = "c-th-td">Payment Detail</th>
                        <th class = "c-th-td">Auto Generate</th>
                        <th class = "c-th-td">Status</th>
                        <th class = "c-th-td">Action(s)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($all_invoice_profiles as $profile)
                    <tr data-profile="@if ($profile->ARCHIVED_DATE == null) Unarchived @else Archived @endif">
                        <td class = "c-th-td">{{$profile->CONTACT_NAME}}</td>
                        <td class = "c-th-td">{{$profile->COMPANY_NAME}}</td>
                        <td class = "c-th-td">{{$profile->BANK_NAME}}</td>
                        <td class = "c-th-td">{{$profile->AUTO_GENERATE}}</td>
                        <td class = "c-th-td">
                        @if ($profile->ARCHIVED_DATE == null) 
                            Unarchived
                        @else 
                            Archived
                        @endif
                        </td>
                        <td class = "c-th-td" style="text-align: center;">
                            <button 
                                data-toggle="modal" 
                                data-target="#history_create" 
                                class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-add-file.png')}}"/></button>
                            &nbsp
                            <a href="{{route('invoice.index.history',['PROFILE_ID'=>$profile->INVOICE_PROFILE_ID])}}">
                                <button id="changeDetails" 
                                    data-toggle="modal" 
                                    data-target="#user_edit" 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-list.png')}}"/></button>
                            </a>
                            &nbsp
                            <a href="{{route('invoice.index.profile',['PROFILE_ID'=>$profile->INVOICE_PROFILE_ID])}}">
                                <button id="changeDetails" 
                                    class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-edit.png')}}"/></button>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div aria-labelledby="tab3-tab" id="tab3" class="tab-pane" role="tabpane3">
            <div class="uk-form-row uk-text-left" style="margin-top:30px;margin-bottom:20px;">
                    <button
                    data-toggle="modal" 
                    data-target="#setting_edit" 
                    data-edited_setting_id="{{$setting->SETTING_ID}}" 
                    data-edited_term_of_payment="{{$setting->PAYMENT_PERIOD}}" 
                    data-edited_last_invoice_number="{{$setting->LAST_INVOICE_NUMBER}}" 
                    data-edited_invoice_number_prefix="{{$setting->INVOICE_NUMBER_PREFIX}}" 
                    data-edited_authorized_name="{{$setting->AUTHORIZED_NAME}}" 
                    data-edited_authorized_position="{{$setting->AUTHORIZED_POSITION}}" 
                    data-edited_note_message="{{$setting->NOTE_MESSAGE}}" 
                    class="uk-button uk-float-right" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-edit.png')}}"/>&nbsp Edit</button>
            </div>
            <table style="width:100%;text-align:left;color:black;border-collapse:collapse;margin-top:30px;" >
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Term of Payment</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$setting->PAYMENT_PERIOD}}</td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Last Invoice Number</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$setting->LAST_INVOICE_NUMBER}}</td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Invoice Number Prefix</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$setting->INVOICE_NUMBER_PREFIX}}</td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Authorized Name</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        {{$setting->AUTHORIZED_NAME}}
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Authorize Position</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        {{$setting->AUTHORIZED_POSITION}}
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Note Message</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        {!!$setting->NOTE_MESSAGE!!}
                    </td>
                </tr>
                
            </table>
            <div style="padding-top:30px;">
                <h3 style="color:#272727;"> Bank Accounts </h3>
            </div>
            <br>
            <table id="invoice-setting-table" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Bank Name</th>
                        <th class = "c-th-td">Account Name</th>
                        <th class = "c-th-td">Account Number</th>
                        <th class = "c-th-td">Address</th>
                        <th class = "c-th-td" style="text-align: center;">
                            <button
                                data-toggle="modal" 
                                data-target="#bank_create" 
                                class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-add.png')}}"/>&nbsp Add New</button>
                        </th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($all_banks as $bank)
                    <tr>
                        <td class = "c-th-td">{{$bank->BANK_NAME}}</td>
                        <td class = "c-th-td">{{$bank->ACCOUNT_NAME}}</td>
                        <td class = "c-th-td">{{$bank->ACCOUNT_NUMBER}}</td>
                        <td class = "c-th-td">{{$bank->ADDRESS}}</td>
                        <td class = "c-th-td" style="text-align: center;">
                            <button 
                                data-toggle="modal" 
                                data-target="#bank_edit" 
                                data-bank_id="{{$bank->INVOICE_BANK_ID}}" 
                                data-bank_name="{{$bank->BANK_NAME}}" 
                                data-account_name="{{$bank->ACCOUNT_NAME}}" 
                                data-account_number="{{$bank->ACCOUNT_NUMBER}}" 
                                data-address="{{$bank->ADDRESS}}" 
                                class="uk-button" style="color:black;font-weight:500;"><img src="{{url('images/icon/icon-edit.png')}}"/></button>
                            &nbsp
                            <a href="{{route('bank.delete',['BANK_ID'=>$bank->INVOICE_BANK_ID])}}" onclick="return confirm('are you sure delete this bank?');">
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
        </div>
       
    </div>

    <script>
        $(document).ready(function() {
            $('#invoice_history_table').DataTable();

            var images1 = "{{url('images/icon/icon-download-all.png')}}";

            var extraToolbar = '<div class="uk-form-row uk-text-left" style="margin-top:-3px;float:right;margin-left:20px;"><button data-toggle="modal" data-target="#download_all_invoices" class="uk-button uk-float-right" style="color:black;font-weight:500;margin-left:20px;border-radius:3px;padding:10px;margin-top:3px;"><img src="'+images1+'"/>&nbsp Download All Invoices</button><table style="float:left;"><tr><td><select id="search_by_history_status" class="status-filter" autocomplete="off"><option value=""> Show All</option><option value="unlocked"> Show Unlocked</option><option value="locked"> Show Locked</option></select></td></tr></table></div>';            

            $("#invoice_history_table_filter").append(extraToolbar);

            $('#invoice-profile-table').DataTable();

            var extraToolbar2 = '<table style="float:right;margin-left:20px;margin-top:-3px;"><tr><td><select id="search_by_archived_status" class="status-filter"><option value=""> Show All</option> <option value="Archived"> Show Only Archieved Client</option> <option value="Unarchived"> Show Only non Archieved Client</option> </select> </td> </tr> </table>';

            $("#invoice-profile-table_filter").append(extraToolbar2);

            $('#invoice-setting-table').DataTable();

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#invoice-history-form').stop().slideToggle();
            });

            $('#arrow2').click(function(e){
                e.preventDefault();
                $('#invoice-profile-form').stop().slideToggle();
            });

            var table = $('#invoice_history_table').DataTable();

            $('#search_by_history_status').change(function() {

                $.fn.dataTable.ext.search.push(
                    function( settings, data, dataIndex ) {
                        var search_by_status = $('#search_by_history_status').val();
                        
                        var status = data[4];

                        if (search_by_status == "unlocked"){
                            return $(table.row(dataIndex).node()).attr('data-history') == ' Unlocked ';
                        }else if (search_by_status == 'locked'){
                            return $(table.row(dataIndex).node()).attr('data-history') == ' Locked ';
                        }else{
                            return true;
                        }
                    }
                );

                table.draw();
            });

            var table2 = $('#invoice-profile-table').DataTable();

            $('#search_by_archived_status').change(function() {

                $.fn.dataTable.ext.search.push(
                    function( settings, data, dataIndex ) {
                        var search_by_status = $('#search_by_archived_status').val();
                        
                        var status = data[4];

                        if (search_by_status == "Archived"){
                            return $(table2.row(dataIndex).node()).attr('data-profile') == ' Archived ';
                        }else if (search_by_status == 'Unarchived'){
                            return $(table2.row(dataIndex).node()).attr('data-profile') == ' Unarchived ';
                        }else{
                            return true;
                        }
                    }
                );

                table2.draw();
            });

            $('#added_invoice_date2').datepicker({ dateFormat: 'yy-mm-dd' }).val();
            $('#added_invoice_date2').datepicker('setDate', new Date());

            changeDate();
            
            $('#added_term_of_payment2').keyup(function(){
                
                changeDate();
                
            })

            $('#added_invoice_date2').change(function() {
                
                changeDate();
                
            });

            function changeDate(){
                var invoiceDate = $('#added_invoice_date2').val();
                var input = $('#added_term_of_payment2').val();
                
                if (input){
                    var date = new Date(invoiceDate);
                    var newdate = new Date(invoiceDate);

                    newdate.setDate(newdate.getDate() + parseInt(input));

                    var dd = newdate.getDate();
                    var mm = newdate.getMonth() + 1;
                    var y = newdate.getFullYear();

                    counter = String(dd).length;
                    counterMonth = String(mm).length;

                    if (counter == 1) dd='0'+dd;
                    if (counterMonth == 1) mm='0'+mm;

                    var formattedDate = y + '-' + mm + '-' + dd;
                    
                    $('#added_due_date2').val(formattedDate);
                }
            }
            
        } );

        
    </script>
@endsection