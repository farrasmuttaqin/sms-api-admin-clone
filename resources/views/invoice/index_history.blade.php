@extends('layouts.main',['title'=> 'SMS API ADMIN | INVOICE PAGE'])

@push('breadcrumb')
<li class="uk-active">@lang('app.invoice_management_history')</li>
@endpush

@section('content')

    <div style="padding-top:30px;padding-bottom:10px;text-align:center;">
        <h3 style="color:black;font-weight:bold;"> Invoice History </h3>
    </div>

    @include('components.alert-danger', ['autoHide' => false])
    @include('components.alert-success', ['autoHide' => false])

    <table style="width:100%;text-align:left;color:black;border-collapse:collapse;" >
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Customer ID</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;"></td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Client Name</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$profile->COMPANY_NAME}}</td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">API Users</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$users}}</td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Contact Phone</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                {{$profile->CONTACT_PHONE}}
            </td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Contact Email</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                {{$profile->CONTACT_EMAIL}}
            </td>
        </tr>
        <tr style="border-bottom:2px solid white;">
            <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Contact Address</td>
            <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                {{$profile->CONTACT_ADDRESS}}
            </td>
        </tr>
    </table>
    <br><br>
    <table style="float:left;margin-bottom:10px;">
        <tr>
            <td>
                <select id='search_by_history_status' class="status-filter" autocomplete="off">
                    <option value=''> Show All</option>
                    <option value='unlocked'> Show Unlocked</option>
                    <option value='locked'> Show Locked</option>
                </select>
            </td>
        </tr>
    </table>
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

    <script>
        $(document).ready(function() {
            $('#invoice_history_table').DataTable();

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
        } );
    </script>
@endsection