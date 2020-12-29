@extends('layouts.main',['title'=> 'SMS API ADMIN | USER PAGE'])

@push('breadcrumb')
    <li class="uk-active" style="color:black;font-weight:bold;">@lang('app.user_management')</li>
@endpush

@section('content')
    
    @if (empty($from_client))


    @if (!empty($notExist))
        <div style="color:black;">
            <br>@lang('app.not_ready_user')<br>
            @foreach ($notExist as $notExist)
                <span style="font-weight:bold;"> - {{$notExist}} </span><br>
            @endforeach
        </div>
    @endif

    <div style="padding-top:30px;">
        <h3 style="color:#272727;"> User Management </h3>
    </div>
    <div class= "c-div-1">
        @include('components.alert-danger', ['autoHide' => false])
        @include('components.alert-success', ['autoHide' => false])
        <div class = "c-div-2">
            <h3 id = "arrow" style="color:white;"><img src="{{url('images/icon/icon-user.png')}}"/> &nbsp User Registration <i class="arrow-down las la-angle-down"></i></h3>
            <form id = "user_form" class="form-index uk-form uk-margin-top" method="POST" action="{{ route('user.create') }}" autocomplete="off">
                @csrf

                <hr style="color:white;"></hr>
                <br>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Client</h6></div>
                    <div class="uk-width-1-6 c-input-div-1">
                        <select name="added_client" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                            <option value="">@lang('app.no_client')</option>
                            @foreach ($all_clients as $client)
                                <option value="{{$client->CLIENT_ID}}" {{ (old("added_client") == $client->CLIENT_ID ? "selected":"") }}>{{$client->COMPANY_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="uk-width-1-6" style="text-align:left;"><h6 class = "h-header">Activate</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" style="text-align:left;">
                        <input type="checkbox" id="added_user_activate" name="added_user_activate" value="1" class="uk-width-1-1" />
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Username</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" ><input type="text" id="added_username_id" value="{{old('added_username')}}" name="added_username" maxlength="32" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Password</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" ><input type="text" value="{{old('added_password')}}" name="added_password" maxlength="32" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                    <div class="uk-width-1-6"><h6 class = "h-header">Cobrander ID</h6></div>
                    <div class="uk-width-1-6 c-input-div-1">
                        <input type="text" id="added_cobrander_id" value="{{old('added_cobrander')}}" name="added_cobrander" maxlength="32" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" readonly required/>
                        <!-- <select id="added_cobrander" name="added_cobrander" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                            <option value="">@lang('app.no_cobrander')</option>
                            @foreach ($all_cobranders as $cobrander)
                                    <option value="{{$cobrander->COBRANDER_NAME}}" {{ (old("added_cobrander") == $cobrander->COBRANDER_NAME ? "selected":"") }}>{{$cobrander->COBRANDER_NAME}}</option>
                            @endforeach
                        </select> -->
                    </div>
                </div>

                <div class="uk-grid">
                    
                    <div class="uk-width-1-6" style="text-align:left;"><h6 class = "h-header">Is OJK</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" style="text-align:left;">
                        <input type="radio" id="added_isojk_yes" name="added_isojk" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="added_isojk_no" name="added_isojk" value="0" required/> <label for="No" class="radio-label">No</label>
                    </div>
                    <div class="uk-width-1-6" style="text-align:left;"><h6 class = "h-header">Is Postpaid</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" style="text-align:left;">
                        <input type="radio" id="added_is_postpaid_yes" name="added_is_postpaid" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="added_is_postpaid_no" name="added_is_postpaid" value="0" required/> <label for="No" class="radio-label">No</label>
                    </div>

                    <div class="uk-width-1-6" style="text-align:left;"><h6 class = "h-header">Is Blacklist</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" style="text-align:left;">
                        <input type="radio" id="added_is_bl_yes" name="added_is_bl" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="added_is_bl_no" name="added_is_bl" value="0" required/> <label for="No" class="radio-label">No</label>
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6" style="text-align:left;"><h6 class = "h-header">Status Delivery</h6></div>
                    <div class="uk-width-1-6 c-input-div-1" style="text-align:left;">
                        <input type="radio" id="added_status_delivery_yes" name="added_status_delivery" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="added_status_delivery_no" name="added_status_delivery" value="0" required/> <label for="No" class="radio-label">No</label>
                    </div>
                    <div id="del_1" class="uk-width-1-6" style="text-align:left;display:none;"><h6 class = "h-header">Delivery URL</h6></div>
                    <div id="del_2" class="uk-width-1-6 c-input-div-1" style="text-align:left;display:none;"><input type="text" value="{{old('added_delivery_url')}}" id="added_delivery_url" name="added_delivery_url" maxlength="255" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                </div>
        
                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                    <button title="Press to register new user" class="uk-button uk-float-right" style="color:black;font-weight:500;">Create</button>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                </div>
            </form>     
        </div>
    </div>

    @else

    <div style="padding-top:30px;">
        <h3 style="color:#272727;"> Users of Client <span style='font-weight:bold;'>"{{$company_name}}"</span> </h3>
    </div>

    @endif

    <div style="margin-top:30px;">
    </div>

    <table id="client_table" class="display" class = "c-table-1" style="color:black;text-align:center;">
        <thead class = "c-thead-1">
            <tr>
                <th class = "c-th-td">Account Name</th>
                <th class = "c-th-td">Client Name</th>
                <th class = "c-th-td">Balance</th>
                <th class = "c-th-td">Status</th>
                <th class = "c-th-td">Action(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_users as $user)
            <tr data-user="@if ($user->ACTIVE == 1) Active @else Inactive @endif">
                <td class = "c-th-td">{{$user->USER_NAME}}</td>
                <td class = "c-th-td">{{$user->COMPANY_NAME}}</td>
                <td class = "c-th-td">{{$user->CREDIT}}</td>
                <td class = "c-th-td" style="text-align:center;">
                        @if ($user->ACTIVE == 1) 
                            <span style='color:white;background-color:rgb(0,177,0);padding:4px 15px 6px 15px;border-radius:8px;' >Active</span>
                        @else 
                            <span style='color:white;background-color:rgb(212,0,0);padding:4px 15px 6px 15px;border-radius:8px;' >Inactive</span>
                        @endif
                    </a>
                </td>
                <td class = "c-td-action">

                    <a href="{{route('user.change_status',['USER_ID' => $user->USER_ID, 'ADAPT' => 0])}}" onclick="return confirm('Change {{$user->USER_NAME}} status?');" >
                        <img title="Press to change user status"
                            data-toggle="modal" 
                            data-target="#client_details" 
                            data-client_id = "1" 

                            style="width:25px;height:25px;cursor:pointer;" 

                            @if ($user->ACTIVE == 1) src="{{url('images/icon/circle-inactive.png')}}" @else src="{{url('images/icon/circle-active.png')}}" @endif

                            />
                    </a>

                &nbsp &nbsp

                <a href="{{route('user.detail',['USER_ID' => $user->USER_ID])}}">
                    <img title="Press to see user detail"
                        style="width:25px;height:25px;cursor:pointer;"
                        src="{{url('images/icon/circle-detail.png')}}" />
                </a>&nbsp &nbsp

                <a href="{{route('user.detail_edit',['USER_ID' => $user->USER_ID])}}">
                    <img title="Press to update user"
                        style="width:25px;height:25px;cursor:pointer;"
                        src="{{url('images/icon/circle-pencil.png')}}" />
                </a>&nbsp &nbsp

                <a href="{{route('credit.detail',['USER_ID' => $user->USER_ID])}}">
                    <img title="Press to manage user credit" class = "c-img" src="{{url('images/icon/circle-money.png')}}" />
                </a>&nbsp &nbsp

                <img title="Press to generate user billing report"
                    style="width:25px;height:25px;cursor:pointer;" 
                    data-toggle="modal" 
                    data-target="#generate_billing" 
                    data-username = "{{$user->USER_NAME}}"
                    data-company_name = "{{$user->COMPANY_NAME}}"
                    src="{{url('images/icon/circle-billing.png')}}" />
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        
        $(document).ready(function() {
            var table = $('#client_table').DataTable();

            var images = "{{url('images/icon/icon-download-all.png')}}";

            var extraToolbar = '<div class="uk-form-row uk-text-left" style="float:right;margin:0px 0px 0px 20px;"><button style = "margin-left:20px;padding:10px;border-radius:3px;color:black;" data-toggle="modal" data-target="#download_billing_reports" class="uk-button uk-float-right"><img src="'+images+'"/>&nbsp Download Billing Reports</button> <table style="float:right;margin-top:-3px;"><tr><td><select id="search_by_status" class="status-filter"><option value="">All Status</option><option value="Active">Show only <h2>Active</h2> user</option><option value="Inactive">Show only <h4>Inactive</h4> user</option></select></td></tr></table></div>';

            $("div.dataTables_filter").append(extraToolbar);

            $('#search_by_status').change(function() {

                $.fn.dataTable.ext.search.push(
                    function( settings, data, dataIndex ) {
                        var search_by_status = $('#search_by_status').val();

                        var status = data[3];
                        
                        if (search_by_status == "Active"){
                            return $(table.row(dataIndex).node()).attr('data-user') == ' Active ';
                        }else if (search_by_status == 'Inactive'){
                            return $(table.row(dataIndex).node()).attr('data-user') == ' Inactive ';
                        }else{
                            return true;
                        }
                    }
                );

                table.draw();
            });

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#user_form').stop().slideToggle();
            });

            $('#added_cobrander').select2();
            
            $('#added_status_delivery_no').click(function(){
                $("#del_1").hide() 
                $("#del_2").hide() 
                $("#added_delivery_url").val('')
                $('#added_delivery_url').prop('required', false);
            })

            $('#added_status_delivery_yes').click(function(){
                $("#del_1").show() 
                $("#del_2").show()

                $('#added_delivery_url').prop('required', true);
            })

            $('#added_username_id').keyup(function(){
                $('#added_cobrander_id').val(this.value)
            })
        } );
    </script>
@endsection