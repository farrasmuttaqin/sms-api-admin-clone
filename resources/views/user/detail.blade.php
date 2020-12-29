@extends('layouts.main',['title'=> 'SMS API ADMIN | USER PAGE'])

@push('breadcrumb')
<li class="uk-active">@lang('app.user_management')</li>
<li class="uk-active" style="color:black;font-weight:bold;">@lang('app.view_detail')</li>
@endpush

@section('content')

    <div style="padding-top:30px;padding-bottom:10px;">
        <h3 style="color:#272727;"> View Details of <span style="font-weight: bold;">{{$user->USER_NAME}}</span> </h3>
    </div>

    @include('components.alert-danger', ['autoHide' => false])
    @include('components.alert-success', ['autoHide' => false])

    <ul role="tablist" class="nav nav-tabs bs-adaptive-tabs" id="myTab" tabindex="1">
        <li title="Press to open user detail page" class="active" role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab1-tab" href="#tab1"><label>Account</label></a></li>
        <li title="Press to open sender page" role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab1-tab" href="#tab2"><label>Sender ID</label></a></li>
        <li title="Press to open IP page" role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab1-tab" href="#tab3"><label>IP Restrictions</label></a></li>
        <li title="Press to open VN page" role="presentation" style="padding:2px 0px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab1-tab" href="#tab4"><label>Virtual Numbers</label></a></li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div aria-labelledby="tab1-tab" id="tab1" class="tab-pane fade in @if (empty(Session::get('senderRedirect')) && empty(Session::get('ipRedirect')) && empty(Session::get('vnRedirect'))) active @endif" role="tabpanel">
            <br><br>
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
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Status</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                    @if ($user->ACTIVE == 0)
                        Inactive
                    @else 
                        Active
                    @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Status Delivery</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if ($user->URL_ACTIVE == 0)
                            Inactive
                        @else 
                            Active
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Delivery URL</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        {{$user->DELIVERY_STATUS_URL}}
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Delivery Failed</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if ($user->URL_INVALID_COUNT == 0)
                            0 time(s)
                        @else 
                            ($user->URL_INVALID_COUNT) times(s)
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Delivery Retry</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->URL_LAST_RETRY}}</td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Reply Blacklist</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if ($user->USE_BLACKLIST == 0)
                            Disabled
                        @else 
                            Enable
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Is Postpaid</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if ($user->IS_POSTPAID == 0)
                            No
                        @else 
                            Yes
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Is Blacklist</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if ($user->USE_BLACKLIST == 0)
                            No
                        @else 
                            Yes
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Is OJK</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if ($user->IS_OJK == 0)
                            No
                        @else 
                            Yes
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Last Access</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->DATETIME_TRY}}</td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Created By</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if (!empty($user->CREATED_BY))
                            {{$user->ADMIN_DISPLAY_NAME}}
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Created On</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->CREATED_DATE}}</td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Update By</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">
                        @if (!empty($user->UPDATED_BY))
                            {{$user->ADMIN_DISPLAY_NAME}}
                        @endif
                    </td>
                </tr>
                <tr style="border-bottom:2px solid white;">
                    <td width="20%" style="background-color:rgb(219,219,219);padding:5px 15px 5px 15px;font-weight:bold;">Update On</td>
                    <td width="80%" style="background-color:rgb(233,233,233);padding:5px 15px 5px 15px;">{{$user->UPDATED_DATE}}</td>
                </tr>
            </table>
            <div class="uk-form-row uk-text-left" style="margin-top:30px;margin-bottom:100px;">
            
                @if (empty($only_see))
                    <button title="Press to change user detail"
                    id="changeDetails" 
                    data-toggle="modal" 
                    data-target="#user_edit" 
                    data-user_id = "{{$user->USER_ID}}" 
                    data-client_id = "{{$user->CLIENT_ID}}" 
                    data-cobrander_id = "{{$user->COBRANDER_NAME}}"
                    
                    data-activate = "{{$user->ACTIVE}}"
                    data-status_delivery = "{{$user->URL_ACTIVE}}"

                    data-delivery_url = "{{$user->DELIVERY_STATUS_URL}}"
                    data-ispostpaid = "{{$user->IS_POSTPAID}}"
                    data-isojk = "{{$user->IS_OJK}}"
                    data-isbl = "{{$user->USE_BLACKLIST}}"
                    
                    class="uk-button uk-float-right" style="color:black;font-weight:500;">Change Details</button>

                    <button title="Press to change user status" id="user_status" class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:15px;">
                        @if ($user->ACTIVE == 0)
                            Activate
                        @else
                            Deactivate
                        @endif
                    </button>

                    <button title="Press to change user password" 
                    id="changePassword" 
                    data-toggle="modal" 
                    data-target="#change_password" 
                    data-user_id = "{{$user->USER_ID}}" 
                    data-password = "{{$user->PASSWORD}}"
                    
                    class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:15px;">Change Password</button>
                @elseif (!empty($only_see))
                    <a title="Press to open edit mode" href="{{route('user.detail_edit',['USER_ID' => $user->USER_ID])}}">
                        <button class="uk-button uk-float-right" style="color:black;font-weight:500;">Edit Mode</button>
                    </a>
                @endif
                <button class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:15px;"

                    title="Press to view user client"
                    data-toggle="modal" 
                    data-target="#detail_client" 
                    data-user_name = "{{$user->USER_NAME}}"    
                    data-client_id = "{{$user->CLIENT_ID}}" 
                    data-company_name = "{{$user->COMPANY_NAME}}" 
                    data-country = "{{$user->COUNTRY_NAME}}" 
                    data-company_url = "{{$user->COMPANY_URL}}" 

                    data-contact_name = "{{$user->CONTACT_NAME}}" 
                    data-contact_email = "{{$user->CONTACT_EMAIL}}" 
                    data-contact_phone = "{{$user->CONTACT_PHONE}}" 
                    data-contact_address = "{{$user->CONTACT_ADDRESS}}" 

                    @if ($user->CREATED_BY)
                        data-created_by = "{{$user->ADMIN_DISPLAY_NAME}}" 
                        data-created_at = "{{$user->CREATED_AT}}" 
                    @else
                        data-created_by = "" 
                        data-created_at = "" 
                    @endif
                    
                    @if ($user->UPDATED_BY)
                        data-updated_by = "{{$user->ADMIN_DISPLAY_NAME}}" 
                        data-updated_at = "{{$user->UPDATED_AT}}" 
                    @else
                        data-updated_by = "" 
                        data-updated_at = "" 
                    @endif

                >View Client</button>
            </div>
        </div>
        <div aria-labelledby="tab2-tab" id="tab2" class="tab-pane @if (!empty(Session::get('senderRedirect'))) active @endif" role="tabpanel">
            @if (empty($only_see))
            <div class= "c-div-1">
                <div class = "c-div-2-big">
                    <h3 style="color:white;">Sender Identity Registrations</h3>
                    <form id = "client_form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('sender.create') }}">
                        @csrf
                        <br>
                        <input type="hidden" name="added_user_id" value="{{$user->USER_ID}}" />
                        <div class="uk-grid">
                            <div class="uk-width-1-15"><h6 class = "h-header">Sender Name</h6></div>
                            <div class="uk-width-1-30 c-input-div-1" ><input type="text" value="{{old('added_sender_name')}}" name="added_sender_name" maxlength="20" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                            <div class="uk-width-1-10"></div>
                            <div class="uk-width-1-15" style="text-align:left;"><h6 class = "h-header">Activate</h6></div>
                            <div class="uk-width-1-30 c-input-div-1" style="text-align:left;"><input type="checkbox" id="added_sender_enabled" value="1" name="added_sender_enabled" class="uk-width-1-1" /></div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-15"><h6 class = "h-header">Cobrander ID</h6></div>
                            <div class="uk-width-1-30 c-input-div-1">
                                <select id="added_cobrander_id" name="added_cobrander_id" class="uk-width-1-1" style="height:28px;background-color:rgb(9,136,255);border-color:rgb(9,136,255);color:white;" required>
                                    <option value="">@lang('app.no_cobrander')</option>
                                    @foreach ($all_cobranders as $cobrander)
                                        <option value="{{$cobrander->COBRANDER_NAME}}" {{ (old("added_cobrander_id") == $cobrander->COBRANDER_NAME ? "selected":"") }}>{{$cobrander->COBRANDER_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                
                        <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                            <button title="Press to create new sender" class="uk-button uk-float-right" style="color:black;font-weight:500;">Create</button>
                        </div>

                        <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                        </div>
                    </form>
                </div>
            </div>
            @endif
            <br><br>
            <table id="t1" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Sender Name</th>
                        <th class = "c-th-td">Cobrander ID</th>
                        <th class = "c-th-td">Status</th>
                        @if (empty($only_see))
                        <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_senders as $sender)
                    <tr>
                        <td class = "c-th-td">{{$sender->SENDER_NAME}}</td>
                        <td class = "c-th-td">{{$sender->COBRANDER_NAME}}</td>
                        <td class = "c-th-td" style="text-align:center;">
                            @if($sender->SENDER_ENABLED == 0)
                                <span style='color:white;background-color:rgb(212,0,0);padding:4px 15px 6px 15px;border-radius:8px;' > Inactive </span>
                            @else
                                <span style='color:white;background-color:rgb(0,177,0);padding:4px 15px 6px 15px;border-radius:8px;' > Active </span>
                            @endif
                        </td>
                        @if (empty($only_see))
                        <td class = "c-td-action">
                        <img title="Press to update sender"
                            data-toggle="modal" 
                            data-target="#sender_edit" 
                            data-sender_id = "{{$sender->SENDER_ID}}" 
                            data-sender_enabled = "{{$sender->SENDER_ENABLED}}" 
                            data-user_id = "{{$sender->USER_ID}}" 
                            data-sender_name = "{{$sender->SENDER_NAME}}" 
                            data-cobrander_id = "{{$sender->COBRANDER_NAME}}" 

                            style="width:25px;height:25px;cursor:pointer;" 
                            src="{{url('images/icon/circle-pencil.png')}}" />
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div aria-labelledby="tab3-tab" id="tab3" class="tab-pane @if (!empty(Session::get('ipRedirect'))) active @endif " role="tabpanel">
            @if (empty($only_see))
            <div class= "c-div-1">
                <div class = "c-div-2-big">
                    <h3 style="color:white;">IP Permission</h3>
                    <form class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('ip.create') }}">
                        @csrf

                        <input type="hidden" name="added_user_id" value="{{$user->USER_ID}}" />
                        <br>
                        <div class="uk-grid">
                            <div class="uk-width-1-4"><h6 class = "h-header">Enter Allowed IP Address</h6></div>
                            <div class="uk-width-1-2 c-input-div-1" ><input type="text" value="{{old('added_ip_address')}}" name="added_ip_address" maxlength="18" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                            <div class="uk-width-1-4" style="text-align:left;">
                                <div class="uk-form-row uk-text-left">
                                    <button title="Press to create new IP" type="submit" value="Submit" class="uk-button uk-float-right" style="color:black;font-weight:500;width:75px;">Ok</button>
                                    <button title="Press to reset IP form" type="reset" value="Reset" class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:30px;width:75px;">Cancel</button>
                                </div>
                            </div>
                        </div>
                        <div class="uk-form-row uk-text-left" style="margin-top:60px;">
                        </div>
                    </form>
                </div>
            </div>
            @endif
            <br><br>
            <h4 style="font-weight:bold;text-align: center;">Permitted IP</h4>
            <table align="center" style="width:100%;color:black;border-collapse:collapse;" class="table-permitted-ip" >
                @if (!empty($only_see))
                    @forelse($all_ip as $ip)
                        <tr>
                            <td width="100%" style="padding:5px 15px 5px 15px;font-weight:bold;text-align: center;">{{$ip->IP_ADDRESS}}</td>
                        </tr>
                    @empty
                        <tr>
                            <td width="100%" style="text-align:center;padding:5px 15px 5px 15px;font-weight:bold;">@lang('app.no_ip')</td>
                        </tr>
                    @endforelse
                @else
                    @forelse($all_ip as $ip)
                        <tr>
                            <td width="50%" style="padding:5px 15px 5px 15px;font-weight:bold;text-align:right;">{{$ip->IP_ADDRESS}}</td>
                            <td width="50%" style="padding:5px 15px 5px 15px;text-align:left;">
                                <a title="Press to delete this IP" href="{{route('ip.delete',['IP_ID' => $ip->USER_IP_ID])}}" onclick="return confirm('Delete IP {{$ip->IP_ADDRESS}}?');" >
                                    <img data-toggle="modal" 
                                        style="width:20px;height:20px;cursor:pointer;"
                                        src="{{url('images/icon/icon-remove.png')}}"
                                    />
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td width="100%" style="text-align:center;padding:5px 15px 5px 15px;font-weight:bold;">@lang('app.no_ip')</td>
                        </tr>
                    @endforelse
                @endif
            </table>
            <div style="margin-top:80px;">
            </div>
        </div>
        <div aria-labelledby="tab4-tab" id="tab4" class="tab-pane @if (!empty(Session::get('vnRedirect'))) active @endif" role="tabpanel">
            @if (empty($only_see))
                <div class= "c-div-1">
                    <div class = "c-div-2-big">
                        <h3 style="color:white;">Virtual Number Registrations</h3>
                        <form class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('vn.create') }}">
                            @csrf
                            <br>
                            <input type="hidden" name="added_user_id" value="{{$user->USER_ID}}" required/> 

                            <div class="uk-grid">
                                <div class="uk-width-1-15"><h6 class = "h-header">Destination</h6></div>
                                <div class="uk-width-1-30 c-input-div-1" ><input type="text" value="{{old('added_destination')}}" name="added_destination" maxlength="16" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                                <div class="uk-width-1-10"></div>
                                <div class="uk-width-1-15" style="text-align:left;"><h6 class = "h-header">Use Forward URL</h6></div>
                                <div class="uk-width-1-30 c-input-div-1" style="text-align:left;">
                                    <input type="radio" id="added_use_forward_url_yes" name="added_use_forward_url" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="added_use_forward_url_no" name="added_use_forward_url" value="0" required/> <label for="No" class="radio-label">No</label>
                                </div>
                            </div>

                            <div class="uk-grid">
                                <div class="uk-width-1-15"><h6 class = "h-header">URL</h6></div>
                                <div class="uk-width-1-30 c-input-div-1" ><input id="added_forward_url" type="text" value="{{old('added_forward_url')}}" name="added_forward_url" maxlength="255" class="uk-width-1-1"  style= "background-color:white;border-color:white;color:black;" required/></div>
                            </div>

                            <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                                <button title="Press to create new VN" class="uk-button uk-float-right" style="color:black;font-weight:500;">Create</button>
                            </div>

                            <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            <br><br>
            <table id="t2" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Destination</th>
                        <th class = "c-th-td">URL Active</th>
                        <th class = "c-th-td">Forward URL</th>
                        <th class = "c-th-td">Invalid Forward</th>
                        <th class = "c-th-td">Last Forwading Retry</th>
                        @if (empty($only_see))
                        <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($all_vn as $vn)
                        <tr>
                            <td class = "c-th-td">{{$vn->DESTINATION}}</td>
                            <td class = "c-th-td" style="text-align:center;">

                                @if($vn->URL_ACTIVE == 0)
                                    <span style='color:white;background-color:rgb(212,0,0);padding:4px 15px 6px 15px;border-radius:8px;' > Inactive </span>
                                @else
                                    <span style='color:white;background-color:rgb(0,177,0);padding:4px 15px 6px 15px;border-radius:8px;' > Active </span>
                                @endif

                            </td>
                            <td class = "c-th-td">
                                {{$vn->FORWARD_URL}}
                            </td>
                            <td class = "c-th-td">
                                @if (empty($vn->URL_INVALID_COUNT))
                                    0 time(s)
                                @else
                                    $vn->URL_INVALID_COUNT time(s)
                                @endif
                            </td>
                            <td class = "c-th-td">
                                {{$vn->URL_LAST_RETRY}}
                            </td>
                            @if (empty($only_see))
                                <td class = "c-td-action">
                                    <img title="Press to edit VN"
                                        data-toggle="modal" 
                                        data-target="#virtual_edit" 
                                        data-virtual_edit_id = "{{$vn->VIRTUAL_NUMBER_ID}}" 
                                        data-destination = "{{$vn->DESTINATION}}"
                                        data-forward = "{{$vn->URL_ACTIVE}}"
                                        data-url = "{{$vn->FORWARD_URL}}"
                                        data-user_edit_id = "{{$user->USER_ID}}"
                                        id="edit_virtual{{$vn->VIRTUAL_NUMBER_ID}}"

                                        style="width:20px;height:20px;cursor:pointer;" 
                                        src="{{url('images/icon/circle-pencil.png')}}" />

                                    &nbsp

                                    <a title="Press to delete this vn" href="{{ route('vn.delete',['VN_ID' => $vn->VIRTUAL_NUMBER_ID]) }}" 
                                    onclick="return confirm('are you sure delete this virtual number?');">
                                        <img 
                                            style="width:20px;height:20px;cursor:pointer;" 
                                            src="{{url('images/icon/icon-remove.png')}}" />
                                    </a>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
       
    </div>

    <script>
        
        $(document).ready(function() {
            var table = $('#t1').DataTable();
            var table2 = $('#t2').DataTable();
            
            if($("#" + 'added_use_forward_url_yes').length > 0) {
                $('#added_use_forward_url_yes').on('click',function(event){
                    $('#added_forward_url').prop('disabled',false);
                });

                $('#added_use_forward_url_no').on('click',function(event){
                    $('#added_forward_url').prop('disabled',true);
                    $('#added_forward_url').val('');
                });
            }

            $("#" + 'user_status').click(function(event){
                var status = confirm('are you sure to change this user status?');

                if (status == true){
                    location.href="{{route('user.change_status',['USER_ID' => $user->USER_ID,'ADAPT' => 1])}}"
                }
            });
            
            $('#added_cobrander_id').select2();
        } );
    </script>
@endsection