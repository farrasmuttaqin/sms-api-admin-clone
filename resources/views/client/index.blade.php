@extends('layouts.main',['title'=> 'SMS API ADMIN | CLIENT PAGE'])

@push('breadcrumb')
    <li class="uk-active" style="color:black;font-weight:bold;">@lang('app.client_management')</li>
@endpush

@section('content')
    
    <div style="padding-top:30px;">
        <h3 style="color:#272727;"> Client List </h3>
    </div>

    <div class= "c-div-1">
        @include('components.alert-danger', ['autoHide' => false])
        @include('components.alert-success', ['autoHide' => false])

        <div class = "c-div-2">
            <h3 id = "arrow" style="color:white;"><img src="{{url('images/icon/icon-client.png')}}"/> &nbsp Client Registration <i class="arrow-down las la-angle-down"></i></h3>
            <form id = "client_form" class="form-index uk-form uk-margin-top" method="POST" action="{{ route('client.create') }}">
                @csrf

                <hr style="color:white;"></hr>

                <h5 class = "h-header">Company</h5>
                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Company Name</h6></div>
                    <div class="uk-width-1-6 c-input-div-1"><input type="text" value="{{old('added_company_name')}}" name="added_company_name" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required /></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Company URL</h6></div>
                    <div class="uk-width-1-6 c-input-div-1"><input type="text" value="{{old('added_company_url')}}" name="added_company_url" maxlength="50" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required /></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Country</h6></div>
                    <div class="uk-width-1-6 c-input-div-1">
                        <select name="added_country" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                            <option value="">@lang('app.no_country')</option>
                            @foreach ($all_countries as $country)
                                <option value="{{$country->COUNTRY_CODE}}">{{$country->COUNTRY_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h5 class = "h-header">Contact</h5>
                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Contact Name</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" ><input type="text" value="{{old('added_contact_name')}}" name="added_contact_name" maxlength="32" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Contact Email</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" ><input type="email" value="{{old('added_contact_email')}}" name="added_contact_email" maxlength="32" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Contact Phone</h6></div>
                    <div class="uk-width-1-6 c-input-div-1"><input type="text" value="{{old('added_contact_phone')}}" name="added_contact_phone" maxlength="32" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" pattern="(\()?(\+62|62|0)(\d{2,3})?\)?[ .-]?\d{2,4}[ .-]?\d{2,4}[ .-]?\d{2,4}" title=" Only Accept Indonesia Phone Number" required/><br><label> &nbsp *example: 6281345235523</label></div>
                </div>
                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Customer ID</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" ><input type="text" value="{{old('added_customer_id')}}" name="added_customer_id" maxlength="32" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Contact Address</h6></div>
                    <div class="uk-width-1-2 c-input-div-1" >
                        <textarea type="text" name="added_contact_address" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;height:90px;" required>{{old('added_contact_address')}}</textarea>
                    </div>
                </div>
        
                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                    <button title="Press to register new client" class="uk-button uk-float-right" style="color:black;font-weight:500;">Create</button>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                </div>
            </form> 
        </div>
    </div>

    <div style="margin-top:30px;">
    </div>

    <table id="client_table" class="display" class = "c-table-1" style="color:black;text-align:center;">
        <thead class = "c-thead-1">
            <tr>
                <th class = "c-th-td">Company Name</th>
                <th class = "c-th-td">Country</th>
                <th class = "c-th-td">Contact Name</th>
                <th class = "c-th-td">Contact Phone</th>
                <th class = "c-th-td">Status</th>
                <th class = "c-th-td">Action(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($all_clients as $client)
                <tr data-client="@if ($client->ARCHIVED_DATE == null) Unarchived @else Archived @endif">
                    <td class = "c-th-td">{{$client->COMPANY_NAME}}</td>
                    <td class = "c-th-td">{{$client->COUNTRY_NAME}}</td>
                    <td class = "c-th-td">{{$client->CONTACT_NAME}}</td>
                    <td class = "c-th-td">{{$client->CONTACT_PHONE}}</td>
                    <td class = "c-th-td">
                        @if ($client->ARCHIVED_DATE == null) 
                            Unarchived
                        @else 
                            Archived
                        @endif
                    </td>
                    <td class = "c-td-action">

                    <a href="{{route('client.change.status',['CLIENT_ID' => $client->CLIENT_ID])}}" onclick="return confirm('Change {{$client->COMPANY_NAME}} status?');" >
                        <img 
                            title="Press to change client status"
                            style="width:25px;height:25px;cursor:pointer;" 

                            @if ($client->ARCHIVED_DATE == null) src="{{url('images/icon/icon-unarchived.png')}}" @else src="{{url('images/icon/icon-archived.png')}}" @endif

                            />
                    </a>

                    &nbsp &nbsp

                    <img title="Press to see client detail"
                        data-toggle="modal" 
                        data-target="#client_details" 
                        data-client_id = "{{$client->CLIENT_ID}}" 
                        data-company_name = "{{$client->COMPANY_NAME}}" 
                        data-country = "{{$client->COUNTRY_NAME}}" 
                        data-company_url = "{{$client->COMPANY_URL}}" 
                        data-customer_id = "{{$client->CUSTOMER_ID}}" 

                        data-contact_name = "{{$client->CONTACT_NAME}}" 
                        data-contact_email = "{{$client->CONTACT_EMAIL}}" 
                        data-contact_phone = "{{$client->CONTACT_PHONE}}" 
                        data-contact_address = "{{$client->CONTACT_ADDRESS}}" 

                        @if ($client->CREATED_BY)
                            data-created_by = "{{$client->ADMIN_DISPLAY_NAME}}" 
                            data-created_at = "{{$client->CREATED_AT}}" 
                        @else
                            data-created_by = "" 
                            data-created_at = "" 
                        @endif
                        
                        @if ($client->UPDATED_BY)
                            data-updated_by = "{{$client->ADMIN_DISPLAY_NAME}}" 
                            data-updated_at = "{{$client->UPDATED_AT}}" 
                        @else
                            data-updated_by = "" 
                            data-updated_at = "" 
                        @endif

                        style="width:25px;height:25px;cursor:pointer;" 
                        src="{{url('images/icon/circle-detail.png')}}" />

                    &nbsp &nbsp

                    <img title="Press to update client"
                        data-toggle="modal" 
                        data-target="#client_edit" 
                        data-client_edit_id = "{{$client->CLIENT_ID}}" 
                        data-company_name = "{{$client->COMPANY_NAME}}" 
                        data-country = "{{$client->COUNTRY_CODE}}" 
                        data-company_url = "{{$client->COMPANY_URL}}" 
                        data-customer_id = "{{$client->CUSTOMER_ID}}" 

                        data-contact_name = "{{$client->CONTACT_NAME}}" 
                        data-contact_email = "{{$client->CONTACT_EMAIL}}" 
                        data-contact_phone = "{{$client->CONTACT_PHONE}}" 
                        data-contact_address = "{{$client->CONTACT_ADDRESS}}" 
                        
                        id="edit_client{{$client->CLIENT_ID}}"

                        style="width:25px;height:25px;cursor:pointer;" 
                        src="{{url('images/icon/circle-pencil.png')}}" />
                    &nbsp &nbsp
                    
                    <a id="delete_client{{$client->CLIENT_ID}}" href="{{ route('client.delete',['CLIENT_ID' => $client->CLIENT_ID]) }}" 
                        onclick="return confirm('are you sure delete this client?');">
                        <img title="Press to delete client"
                            style="width:25px;height:25px;cursor:pointer;"
                            src="{{url('images/icon/circle-delete.png')}}" />
                    </a>&nbsp &nbsp

                    <a href="{{ route('client.user_detail',['CLIENT_ID' => $client->CLIENT_ID]) }}">
                        <img title="Press to see users of this client" class = "c-img" src="{{url('images/icon/circle-user.png')}}" />
                    </a>&nbsp &nbsp

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#client_table').DataTable();

            var extraToolbar = '<table style="float:right;margin-top:-3px;margin-left:20px;"><tr><td><select id="search_by_archived_status" class="status-filter"><option value=""> Show All</option><option value="Archived"> Show Only Archieved Client</option><option value="Unarchived"> Show Only non Archieved Client</option></select></td></tr></table>';

            $("div.dataTables_filter").append(extraToolbar);

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#client_form').stop().slideToggle();
            });
            
            var table = $('#client_table').DataTable();

            $('#search_by_archived_status').change(function() {

                $.fn.dataTable.ext.search.push(
                    function( settings, data, dataIndex ) {
                        var search_by_status = $('#search_by_archived_status').val();
                        
                        var status = data[4];

                        if (search_by_status == "Archived"){
                            return $(table.row(dataIndex).node()).attr('data-client') == ' Archived ';
                        }else if (search_by_status == 'Unarchived'){
                            return $(table.row(dataIndex).node()).attr('data-client') == ' Unarchived ';
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