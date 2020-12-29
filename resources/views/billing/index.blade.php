@extends('layouts.main',['title'=> 'SMS API ADMIN | BILLING PAGE'])

@push('breadcrumb')
<li class="uk-active" style="color:black;font-weight:bold;">@lang('app.billing_management')</li>
@endpush

@section('content')

    <div style="padding-top:30px;padding-bottom:10px;">
        <h3 style="color:#272727;">Billing Management <span style="font-weight: bold;"></span> </h3>
    </div>

    @include('components.alert-danger', ['autoHide' => false])
    @include('components.alert-success', ['autoHide' => false])

    <div id="error-billing-name" class="uk-alert uk-alert-danger uk-text-left uk-text-small" style="display:none;">
	    <ul class='uk-list'>
            <li><span class="uk-icon-warning"></span> *Billing name already exist!</li>
	    </ul>
	</div>

    <ul role="tablist" class="nav nav-tabs bs-adaptive-tabs" id="myTab" tabindex="1">
        <li class="active" role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab1-tab" href="#tab1"><label><img src="{{url('images/icon/icon-history.png')}}"/> &nbsp Billing Profile</label></a></li>
        <li role="presentation" style="padding:2px 5px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab2-tab" href="#tab2"><label><img src="{{url('images/icon/icon-history.png')}}"/> &nbsp Tiering Group</label></a></li>
        <li role="presentation" style="padding:2px 0px 10px 0px;"><a style="background-color:rgb(40,153,255);color:white;border-radius:8px 8px 0px 0px;padding:8px 8px 10px 8px;" aria-expanded="true" aria-controls="home" data-toggle="tab" role="tab" id="tab3-tab" href="#tab3"><label><img src="{{url('images/icon/icon-history.png')}}"/> &nbsp Report Group</label></a></li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div aria-labelledby="tab1-tab" id="tab1" class="tab-pane fade in active" role="tabpanel">

            <div class= "c-div-1">
                <div class = "c-div-2">
                    <h3 id = "arrow" style="color:white;">New Billing Profile <i class="arrow-down las la-angle-down"></i></h3>
                    <form id="billing-profile-form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('billing.create') }}" autocomplete="off" style="display:none;">
                        @csrf

                        <hr style="color:white;"></hr>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Name</h6> </div>
                            <div class="uk-width-1-6 c-input-div-1"><input type="text" id="added_billing_name" value="{{old('added_billing_name')}}" name="added_billing_name" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required/></div>
                            <div class="uk-width-1-6 c-input-div-1"></div>
                            <div class="uk-width-1-8"><h6 class = "h-header">Type</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select id="added_type" name="added_type" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_type')</option>
                                    <option value="operator">Operator</option>
                                    <option value="tiering">Tiering</option>
                                    <option value="tiering-operator">Tiering-Operator</option>
                                </select>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Description</h6></div>
                            <div class="uk-width-1-4 c-input-div-1">
                                <textarea type="text" name="added_billing_description" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;height:90px;" >{{old('added_billing_description')}}</textarea>    
                            </div>
                            <div style="width:8.4%;" class="c-input-div-1"></div>
                            <div class="uk-width-1-8"><h6 class = "h-header">Users</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select id="added_user" name="added_billing_users[]" multiple="multiple" class="uk-width-1-1" style="height:28px;" required>
                                <option value="">@lang('app.no_users')</option>
                                @foreach ($all_users as $user)
                                    <option value="{{$user->USER_ID}}">{{$user->USER_NAME}}</option>
                                @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Setting</h6></div>
                            <div style="width:80%;" class="c-input-div-1" >
                                <h6 id="choose-alert" class = "h-header">Select a type</h6>
                                <div id="operator-container" class="operator-container" style="display:none;">
                                    <div style="max-height: 300px; margin-top: 10px; overflow-x: auto;">
                                        <table id="operator-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Operator Name</th>
                                                    <th>Price</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <img src="{{url('images/icon/circle-red.png')}}" alt="Remove" style="cursor:pointer;" width="13px" class='remove-operator'>
                                                    </td>
                                                    <td style='padding-left:10px;'>
                                                        <select style="border-radius: 0px;" name="operatorOP[0][operator]" disabled>
                                                            <option value = "DEFAULT" selected>DEFAULT</option>
                                                        </select>
                                                    </td>
                                                    <td style='padding-left:10px;'>
                                                        <input type="number" name="operatorPR[0][price]" style="border-radius: 0px;" maxlength="10" step=".01">
                                                    </td>
                                                    <td> 
                                                        <span style="font-size: 10px; color:yellow;"> &nbsp * This price will be implemented <br> &nbsp if destination prefix doesn't found on listed operator</span>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button id="add-operator-field" class="uk-button uk-float-left" style="color:black;font-weight:bold;margin-top:20px;">
                                        Add Field
                                    </button>
                                </div>
                                <div id="tiering-container" class="tiering-container" style="display:none;">
                                    <div style="max-height: 300px; margin-top: 10px; overflow-x: auto;">
                                        <table id="tiering-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>From</th>
                                                    <th>Up To</th>
                                                    <th>Price</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="height:40px;">
                                                    <td>
                                                        <img src="{{url('images/icon/circle-red.png')}}" alt="Remove" style="cursor:pointer;" width="13px" class="remove-tiering">
                                                    </td>
                                                    <td style="padding-left:10px;">
                                                        <input type="number" class="tr-from" name="tieringFR[0][tr]" value=0 style="border-radius: 0px;" maxlength="10" readonly/>
                                                    </td>
                                                        
                                                    <td style="padding-left:10px;">
                                                        <input type="text" value='MAX' class="tr-to" name="tieringUP[0][tr]" style="border-radius: 0px;" maxlength="10" readonly/>
                                                    </td>
                                                    <td style="padding-left:10px;">
                                                        <input type="number" name="tieringPR[0][price]" style="border-radius: 0px;" maxlength="10" step=".01" />
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button id="add-tiering-field" class="uk-button uk-float-left" style="color:black;font-weight:bold;margin-top:20px;">
                                        Add Field
                                    </button>
                                </div>
                                <div id="tiering-operator-container" class="tiering-operator-container" style="display:none;">
                                    <div style="max-height: 300px; margin-top: 10px; overflow-x: auto;">
                                        <table id="tiering-operator-table">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>From</th>
                                                    <th>Up To</th>
                                                    <th>Operator Name</th>
                                                    <th>Price</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr style="height:40px;">
                                                    <td>
                                                        <img src="{{url('images/icon/circle-red.png')}}" alt="Remove" style="cursor:pointer;" width="13px" class="remove-tOperator">
                                                    </td>
                                                    <td style="padding-left:10px;">
                                                        <input type="number" class="trOperator-from" name="tOperatorFR[0][tr]" value=0 style="border-radius: 0px;" maxlength="10" />
                                                    </td>
                                                        
                                                    <td style="padding-left:10px;">
                                                        <input type="text" value="MAX" class="trOperator-to" name="tOperatorUP[0][tr]" style="border-radius: 0px;" maxlength="10" readonly  />
                                                    </td>
                                                    <td style="padding-left:10px;">
                                                        <select style="border-radius: 0px;" name="tOperatorOP[0][operator]" disabled>
                                                            <option value = "DEFAULT" selected>DEFAULT</option>
                                                        </select>
                                                    </td>
                                                    <td style="padding-left:10px;">
                                                        <input type="number" name="tOperatorPR[0][price]" style="border-radius: 0px;" maxlength="10" step=".01" />
                                                    </td>
                                                    <td>
                                                        <img src="{{url('images/icon/circle-add.png')}}" alt="Remove" style="cursor:pointer;" width="13px" class="add-tOperator">
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <button id="add-tiering-operator-field" class="uk-button uk-float-left" style="color:black;font-weight:bold;margin-top:20px;">
                                        Add Field
                                    </button>
                                </div>
                            </div>
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
            <table id="billing_profile_table" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Name</th>
                        <th class = "c-th-td">Price Base</th>
                        <th class = "c-th-td">Created At</th>
                        <th class = "c-th-td">Action(s)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($all_billings as $billing)
                    <tr>
                        <td class = "c-th-td">{{$billing->NAME}}</td>
                        <td class = "c-th-td">{{$billing->BILLING_TYPE}}</td>
                        <td class = "c-th-td" style="text-align:center;">
                            {{$billing->CREATED_AT}}
                        </td>
                        <td class = "c-td-action">
                            <img data-toggle="modal" 
                                data-target="#billing_profile_edit" 
                                data-billing_id="{{$billing->BILLING_PROFILE_ID}}"
                                data-billing_name="{{$billing->NAME}}" 
                                data-description="{{$billing->DESCRIPTION}}" 
                                data-type="{{$billing->BILLING_TYPE}}" 


                                style="width:25px;height:25px;cursor:pointer;" 
                                src="{{url('images/icon/circle-pencil.png')}}" />
                                &nbsp &nbsp
                                
                                <a href="{{ route('billing.delete',['BILLING_ID' => $billing->BILLING_PROFILE_ID ]) }}" 
                                    onclick="return confirm('are you sure delete this billing?');">

                                    <img data-toggle="modal" 

                                    style="width:25px;height:25px;cursor:pointer;" 
                                    src="{{url('images/icon/circle-delete.png')}}" />
                                </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div aria-labelledby="tab2-tab" id="tab2" class="tab-pane" role="tabpane2">
            <div class= "c-div-1">
                <div class = "c-div-2">
                    <h3 id = "arrow2" style="color:white;">Tiering Group <i class="arrow-down las la-angle-down"></i></h3>
                    <form id="tiering-group-form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('tiering_group.create') }}" style="display:none;">
                        @csrf

                        <hr style="color:white;"></hr>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Name</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <input type="text" value="{{old('added_tiering_group_name')}}" name="added_tiering_group_name" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required />
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Description</h6></div>
                            <div style="width:87.5%" class="c-input-div-1">
                                <textarea type="text" name="added_tiering_group_description" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;height:90px;">{{old('added_tiering_group_description')}}</textarea>    
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Users</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select id="added_tiering_group_user" name="added_tiering_group_users[]" multiple="multiple" class="uk-width-1-1" required>
                                    <option value="">@lang('app.no_users')</option>
                                    @foreach ($all_tiering_group_users as $user)
                                        <option value="{{$user->USER_ID}}">{{$user->USER_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="uk-width-1-2" style="color:white;">
                                * Select users which accumulate the same tiering
                                <br>
                                ** You can only select users whose implement the same billing profile
                            </div>
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
            <table id="tiering_group_table" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Name</th>
                        <th class = "c-th-td">Created At</th>
                        <th class = "c-th-td">Action(s)</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($all_tiering_groups as $billing)
                    <tr>
                        <td class = "c-th-td">{{$billing->NAME}}</td>
                        <td class = "c-th-td" style="text-align:center;">
                            {{$billing->CREATED_AT}}
                        </td>
                        <td class = "c-td-action">
                            <img data-toggle="modal" 
                                data-target="#tiering_group_edit" 
                                data-tg_id="{{$billing->BILLING_TIERING_GROUP_ID}}"
                                data-tg_name="{{$billing->NAME}}"
                                data-tg_description="{{$billing->DESCRIPTION}}"

                                style="width:25px;height:25px;cursor:pointer;" 
                                src="{{url('images/icon/circle-pencil.png')}}" />
                                &nbsp &nbsp
                                
                                <a href="{{ route('tiering_group.delete',['BILLING_TIERING_GROUP_ID' => $billing->BILLING_TIERING_GROUP_ID ]) }}" 
                                    onclick="return confirm('are you sure delete this billing tiering group?');">

                                    <img data-toggle="modal" 

                                    style="width:25px;height:25px;cursor:pointer;" 
                                    src="{{url('images/icon/circle-delete.png')}}" />
                                </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        <div aria-labelledby="tab3-tab" id="tab3" class="tab-pane" role="tabpane3">
            <div class= "c-div-1">
                <div class = "c-div-2">
                    <h3 id="arrow3" style="color:white;">Report Group <i class="arrow-down las la-angle-down"></i></h3>
                    <form id="report-group-form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('report_group.create') }}" style="display:none;">
                        @csrf

                        <hr style="color:white;"></hr>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Name</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                            <input type="text" value="{{old('added_report_group_name')}}" name="added_report_group_name" maxlength="100" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required />
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Description</h6></div>
                            <div style="width:87.5%" class="c-input-div-1">
                                <textarea type="text" name="added_report_group_description" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;height:90px;">{{old('added_report_group_description')}}</textarea>      
                            </div>
                        </div>

                        <div class="uk-grid">
                            <div class="uk-width-1-8"><h6 class = "h-header">Users</h6></div>
                            <div class="uk-width-1-6 c-input-div-1">
                                <select id="added_report_group_user" name="added_report_group_users[]" multiple="multiple" class="uk-width-1-1" required>
                                    <option value="">@lang('app.no_users')</option>
                                    @foreach ($all_users as $user)
                                        <option value="{{$user->USER_ID}}">{{$user->USER_NAME}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="uk-width-1-2" style="color:white;">
                                * Select users which accumulate the same tiering
                                <br>
                                ** You can only select users whose implement the same billing profile
                            </div>
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
            <table id="report_group_table" class="display" class = "c-table-1" style="color:black;">
                <thead class = "c-thead-1">
                    <tr>
                        <th class = "c-th-td">Name</th>
                        <th class = "c-th-td">Created At</th>
                        <th class = "c-th-td">Action(s)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($all_report_groups as $billing)
                        <tr>
                            <td class = "c-th-td">{{$billing->NAME}}</td>
                            <td class = "c-th-td" style="text-align:center;">
                                {{$billing->CREATED_AT}}
                            </td>
                            <td class = "c-td-action">
                                <img data-toggle="modal" 
                                    data-target="#report_group_edit" 
                                    data-rg_id="{{$billing->BILLING_REPORT_GROUP_ID}}"
                                    data-rg_name="{{$billing->NAME}}"
                                    data-rg_description="{{$billing->DESCRIPTION}}"

                                    style="width:25px;height:25px;cursor:pointer;" 
                                    src="{{url('images/icon/circle-pencil.png')}}" />
                                    &nbsp &nbsp
                                    
                                    <a href="{{ route('report_group.delete',['BILLING_REPORT_GROUP_ID' => $billing->BILLING_REPORT_GROUP_ID ]) }}" 
                                        onclick="return confirm('are you sure delete this billing report group?');">

                                        <img data-toggle="modal" 

                                        style="width:25px;height:25px;cursor:pointer;" 
                                        src="{{url('images/icon/circle-delete.png')}}" />
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

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#billing-profile-form').stop().slideToggle();
            });

            $('#arrow2').click(function(e){
                e.preventDefault();
                $('#tiering-group-form').stop().slideToggle();
            });

            $('#arrow3').click(function(e){
                e.preventDefault();
                $('#report-group-form').stop().slideToggle();
            });

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content'),
                rowIndexOperator    = $('#operator-table tbody tr').length,
                rowIndexTiering     = $('#tiering-table tbody tr').length;
                rowIndextOperator   = $('#tiering-operator-table tbody tr').length;

            $(document)
                    .on('click', '.remove-operator', function(){
                        var length = $('#operator-table').find('tbody tr').length,
                            parent = $(this).parent('td').parent('tr');

                        if (length > 1 && $(this).parent('td').parent('tr') && parent.is(':not(:first-child)')){
                            parent.remove();
                        }

                        var operatorOP =$('select[name^="operatorOP"]'),
                            operatorPR =$('input[name^="operatorPR"]');

                        /**
                         * Fixing operator systematic name index
                         */
                         
                        fixingOperatorNameIndexForm(operatorOP,operatorPR)
                    })
                    .on('click', '.remove-tiering', function(){
                        var length = $('#tiering-table').find('tbody tr').length,
                            parent = $(this).parent('td').parent('tr');

                        if (length > 1 && $(this).parent('td').parent('tr') && parent.is(':not(:first-child)')){

                            if ($(this).parents('tr').find('.tr-to').val() == 'MAX'){
                                $(this).parents('tr').prev().find('.tr-to').attr('type','text');
                                $(this).parents('tr').prev().find('.tr-to').attr('readonly',true);
                                $(this).parents('tr').prev().find('.tr-to').val('MAX');
                            }
                            
                            
                            parent.remove();
                        }

                        var tieringFrom     =$('input[name^="tieringFR"]'),
                            tieringTo       =$('input[name^="tieringUP"]'),
                            tieringPrice    =$('input[name^="tieringPR"]');

                        /**
                        * Fixing tiering systematic name index
                        */

                        fixingTieringNameIndexForm(tieringFrom,tieringTo,tieringPrice)
                    })
                    .on('click', '.remove-tOperator', function(){
                        var length = $('#tiering-operator-table').find('tbody tr').length,
                            parent = $(this).parent('td').parent('tr');

                        if (length > 1 && $(this).parent('td').parent('tr') && parent.is(':not(:first-child)')){

                            if ($(this).parent('td').parent('tr').find('.trOperator-to').val() == 'MAX'){
                                $(this).closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').attr('type','text');
                                $(this).closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').attr('readonly',true);
                                $(this).closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').val('MAX');
                            }

                            parent.remove();
                        }

                        var tOperatorOP =$('select[name^="tOperatorOP"]'),
                            tOperatorPR =$('input[name^="tOperatorPR"]'),
                            tOperatorFrom     =$('input[name^="tOperatorFR"]'),
                            tOperatorTo       =$('input[name^="tOperatorUP"]');

                        /**
                         * Fixing tiering operator systematic name index
                         */
                         
                        fixingTieringOperatorNameIndexForm(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo)
                    })
                    .on('keyup', '.tr-to', function(e){
                        var uptoVal = parseInt($(this).val());

                        /* fill the next tiering's from value based on current tiering's up to value */
                        if(uptoVal > 0){
                            $(this).parents('tr').next().find('.tr-from')
                                .val(uptoVal + 1)
                                .trigger('input');
                        } else {
                            $(this).parents('tr').next().find('.tr-from')
                                .val('');
                            
                        }
                    })
                    .on('keyup', '.trOperator-to', function(e){
                        var uptoVal = parseInt($(this).val());

                        if(uptoVal > 0){
                            $(this).closest('tr').nextAll(':has(.trOperator-from):first').find('.trOperator-from')
                                .val(uptoVal + 1)
                                .trigger('input');
                        } else {
                            $(this).closest('tr').nextAll(':has(.trOperator-from):first').find('.trOperator-from')
                                .val('');
                            
                        }
                    })
                    .on('keyup', '.tr-from', function(e){

                        var fromVal = parseInt($(this).val());

                        /* fill the previous tiering's up to value based on current tiering's from value */
                        if(fromVal > 0){
                            $(this).parents('tr').prev().find('.tr-to')
                                   .val(fromVal - 1)
                                   .trigger('input');
                        } else {
                            $(this).parents('tr').prev().find('.tr-to')
                                   .val('');
                        }
                    })
                    .on('keyup', '.trOperator-from', function(e){

                        var fromVal = parseInt($(this).val());

                        if($(this).closest('tr').prevAll(':has(.check-max-tOperator):first').find('.check-max-tOperator').prop("checked") == false){
                            /* fill the previous tiering's up to value based on current tiering's from value */
                            if(fromVal > 0){
                                $(this).closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to')
                                       .val(fromVal - 1)
                                       .trigger('input');
                            } else {
                                $(this).closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to')
                                       .val('');
                            }
                        }
                    })
                    .on('change', '.check-max', function(e){

                        if (this.checked){

                            $(this).parents('tr').find('.tr-to')
                                .prop('type','text');

                            $(this).parents('tr').find('.tr-to')
                                .val('MAX')
                                .trigger('input');

                            $(this).parents('tr').find('.tr-to')
                                .prop('readonly', true);

                        }else{

                            $(this).parents('tr').find('.tr-to')
                                .prop('type','number');

                            $(this).parents('tr').find('.tr-to')
                                .val('')
                                .trigger('input');

                            $(this).parents('tr').find('.tr-to')
                                .prop('readonly', false);
                        }
                    })
                    .on('change', '.check-max-tOperator', function(e){

                        if (this.checked){

                            $(this).parents('tr').find('.trOperator-to')
                                .prop('type','text');

                            $(this).parents('tr').find('.trOperator-to')
                                .val('MAX')
                                .trigger('input');

                            $(this).parents('tr').find('.trOperator-to')
                                .prop('readonly', true);

                        }else{

                            $(this).parents('tr').find('.trOperator-to')
                                .prop('type','number');

                            $(this).parents('tr').find('.trOperator-to')
                                .val('')
                                .trigger('input');

                            $(this).parents('tr').find('.trOperator-to')
                                .prop('readonly', false);
                        }
                    })
                    .on('click', '.add-tOperator', function(){

                        newRowTieringOperator      =   
                        '<tr style="height:40px;">'
                            + '<td>'
                                +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tOperator'>"
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="hidden" name="tOperatorFR['+rowIndextOperator+'][tr]" style="border-radius: 0px;" maxlength="10" />'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="hidden" name="tOperatorUP['+rowIndextOperator+'][tr]" style="border-radius: 0px;" maxlength="10" />'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<select style="border-radius: 0px;width:100%;" name="tOperatorOP['+rowIndextOperator+'][operator]" required>'
                                +'</select>'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="number" name="tOperatorPR['+rowIndextOperator+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required/>'
                            + '</td>'
                            + '<td>'
                                +"<img src='{{url('images/icon/circle-add.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='add-tOperator'>"
                            + '</td>'
                        +'</tr>';
                        
                        $(newRowTieringOperator).insertAfter($(this).closest('tr'));

                        var tOperatorOP =$('select[name^="tOperatorOP"]'),
                            tOperatorPR =$('input[name^="tOperatorPR"]'),
                            tOperatorFrom     =$('input[name^="tOperatorFR"]'),
                            tOperatorTo       =$('input[name^="tOperatorUP"]'),
                            tOperatorOPSelectProcess = $('select[name="tOperatorOP['+rowIndextOperator+'][operator]"]');
                        
                        /**
                        * Process Select Operator
                        */

                        loadOperator(tOperatorOPSelectProcess,CSRF_TOKEN);

                        /**
                        * Fixing tiering operator systematic name index
                        */
                            
                        fixingTieringOperatorNameIndexForm(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo)

                        rowIndextOperator++;

                    })
                    
                
            $('#added_type').change(function(e){
                e.preventDefault();
                
                if (this.value == 'operator'){
                    var operatorOp = $('select[name ="operatorOP[0][operator]"]'),
                        addOperatorField = $('#add-operator-field'),
                        newRowOperator = '';

                    requiredSetting(this.value);
                    loadOperator(operatorOp,CSRF_TOKEN);

                    addOperatorField.unbind('click').click(function(e){
                        e.preventDefault();

                        newRowOperator      =   '<tr>'
                                            + '<td>'
                                                    +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-operator'>"
                                            + '</td>'
                                            + '<td style="padding-left:10px;">'
                                            +   '<select style="border-radius: 0px;width:100%;" name="operatorOP['+rowIndexOperator+'][operator]" required>'
                                            +   '</select>'
                                            + '</td>'
                                            + '<td style="padding-left:10px;">'
                                            +   '<input type="number" name="operatorPR['+rowIndexOperator+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required>'
                                            + '</td>'
                                            + '<td>'
                                            +   "<span style='font-size: 10px; color:yellow;'> &nbsp * This price will be implemented <br> &nbsp if destination prefix doesn't found on listed operator</span>"
                                            + '</td>'
                                        +'</tr>';

                        $('#operator-table tbody').append(newRowOperator);

                        var operatorOp = $('select[name="operatorOP['+rowIndexOperator+'][operator]"]'),
                            operatorOP =$('select[name^="operatorOP"]'),
                            operatorPR =$('input[name^="operatorPR"]');

                        loadOperator(operatorOp,CSRF_TOKEN);

                        /**
                         * Fixing operator systematic name index
                         */

                        fixingOperatorNameIndexForm(operatorOP,operatorPR)

                        rowIndexOperator++;
                    })
                }else if (this.value == 'tiering'){
                    var addTieringField = $('#add-tiering-field'),
                        newRowTiering = '';

                    requiredSetting(this.value);

                    addTieringField.unbind('click').click(function(e){
                        e.preventDefault();

                        index = rowIndexTiering - 1;
                        
                        uptoVal = parseInt($('input[name ="tieringUP['+index+'][tr]"]').val());
                        
                        /* fill the next tiering's from value based on current tiering's up to value */
                        if(uptoVal > 0){
                            up = uptoVal + 1;
                        } else {
                            up = '';
                        }

                        indexNow = $('#tiering-table tbody tr').length - 1;

                        $('input[name ="tieringUP['+indexNow+'][tr]"]').val('');
                        $('input[name ="tieringUP['+indexNow+'][tr]"]').attr("type", 'number'); 
                        $('input[name ="tieringUP['+indexNow+'][tr]"]').attr("readonly", false); 

                        newRowTiering      =   '<tr style="height:40px;">'
                                            + '<td>'
                                                +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tiering'>"
                                            + '</td>'
                                            + '<td style="padding-left:10px;">'
                                                +'<input type="number" value="'+up+'" class="tr-from" name="tieringFR['+rowIndexTiering+'][tr]" style="border-radius: 0px;" maxlength="10" required />'
                                            + '</td>'
                                            + '<td style="padding-left:10px;">'
                                                +'<input value="MAX" class="tr-to" name="tieringUP['+rowIndexTiering+'][tr]" style="border-radius: 0px;" maxlength="10" readonly required/>'
                                            + '</td>'
                                            + '<td style="padding-left:10px;">'
                                                +'<input type="number" name="tieringPR['+rowIndexTiering+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required/>'
                                            + '</td>'
                                        +'</tr>';

                        $('#tiering-table tbody').append(newRowTiering);

                        var tieringFrom     =$('input[name^="tieringFR"]'),
                            tieringTo       =$('input[name^="tieringUP"]'),
                            tieringPrice    =$('input[name^="tieringPR"]');

                        /**
                        * Fixing tiering systematic name index
                        */

                        fixingTieringNameIndexForm(tieringFrom,tieringTo,tieringPrice)

                        rowIndexTiering++;
                    })
                }else if (this.value == 'tiering-operator'){
                    var addTieringOperatorField = $('#add-tiering-operator-field'),
                        newRowTieringOperator = '',
                        tOperatorOp = $('select[name ="tOperatorOP[0][operator]"]');

                    loadOperator(tOperatorOp,CSRF_TOKEN);
                    requiredSetting(this.value);

                    addTieringOperatorField.unbind('click').click(function(e){
                        e.preventDefault();

                        index = rowIndextOperator - 1;
                        
                        uptoVal = parseInt($('input[name ="tOperatorUP['+index+'][tr]"]').val());
                        
                        /* fill the next tiering's from value based on current tiering's up to value */
                        if(uptoVal > 0){
                            up = uptoVal + 1;
                        } else {
                            up = '';
                        }
                                
                        newRowTieringOperator      =   
                        '<tr style="height:40px;">'
                            + '<td>'
                                +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tOperator'>"
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="number" value="'+up+'" class="trOperator-from" name="tOperatorFR['+rowIndextOperator+'][tr]" style="border-radius: 0px;" maxlength="10" required />'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input value="MAX" type="text" class="trOperator-to" name="tOperatorUP['+rowIndextOperator+'][tr]" style="border-radius: 0px;" maxlength="10" readonly required/>'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<select style="border-radius: 0px;width:100%;" name="tOperatorOP['+rowIndextOperator+'][operator]" disabled required>'
                                    +'<option value = "DEFAULT" selected>DEFAULT</option>'
                                +'</select>'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="number" name="tOperatorPR['+rowIndextOperator+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required/>'
                            + '</td>'
                            + '<td>'
                                +"<img src='{{url('images/icon/circle-add.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='add-tOperator'>"
                            + '</td>'
                        +'</tr>';

                        $('#tiering-operator-table tbody').append(newRowTieringOperator);

                        var tOperatorOP =$('select[name^="tOperatorOP"]'),
                            tOperatorPR =$('input[name^="tOperatorPR"]'),
                            tOperatorFrom     =$('input[name^="tOperatorFR"]'),
                            tOperatorTo       =$('input[name^="tOperatorUP"]'),
                            tOperatorOPSelectProcess = $('select[name="tOperatorOP['+rowIndextOperator+'][operator]"]');
                        
                        /**
                        * Process Select Operator
                        */

                        loadOperator(tOperatorOPSelectProcess,CSRF_TOKEN);

                        /**
                        * Fixing tiering operator systematic name index
                        */
                            
                        fixingTieringOperatorNameIndexForm(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo)

                        indexNow = $('#tiering-operator-table tbody tr').length - 1;

                        if ($('input[name ="tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').attr('type') == 'text'){
                            $('input[name ="tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').val('');
                            $('input[name ="tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').attr("type", 'number'); 
                            $('input[name ="tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to):first').find('.trOperator-to').attr("readonly", false); 
                        }

                        rowIndextOperator++;
                    });
                }
            });

            $('#billing_profile_table').DataTable({
                "oSearch": { "bSmart": false, "bRegex": true }
            });

            var href = "{{route('generate.sms.content.index')}}", images = "{{url('images/icon/icon-download-all.png')}}";

            var extraToolbar = '<div class="uk-form-row uk-text-left" style="margin-left:20px;margin-top:-1px;float:right;"><a href="'+href+'"><button class="uk-button uk-float-right" style="color:black;font-weight:500;border-radius:3px;padding:10.5px;"><img src="'+images+'"/>&nbsp Generate SMS Content Department Filter</button></a></div>';

            $("#billing_profile_table_filter").append(extraToolbar);

            $('#tiering_group_table').DataTable({
                "oSearch": { "bSmart": false, "bRegex": true }
            });
            
            $('#report_group_table').DataTable({
                "oSearch": { "bSmart": false, "bRegex": true }
            });

            $('#added_user').select2({
                placeholder: "@lang('app.no_user')",
                allowClear: true
            });
            $('#added_tiering_group_user').select2({
                placeholder: "@lang('app.no_user')",
                allowClear: true
            });
            $('#added_report_group_user').select2({
                placeholder: "@lang('app.no_user')",
                allowClear: true
            });

            $("#cancel").click(function(){
                $("#added_user").select2('val', 'All');
            });

            $("#added_type").change(function(){
                if (this.value == "operator"){
                    $('#choose-alert').css('display','none');
                    $('#operator-container').css('display','block'); 
                    $('#tiering-container').css('display','none');
                    $('#tiering-operator-container').css('display','none');
                }else if (this.value == "tiering"){
                    $('#choose-alert').css('display','none');
                    $('#operator-container').css('display','none'); 
                    $('#tiering-container').css('display','block');
                    $('#tiering-operator-container').css('display','none');
                }else if (this.value == "tiering-operator"){
                    $('#choose-alert').css('display','none');
                    $('#operator-container').css('display','none'); 
                    $('#tiering-container').css('display','none');
                    $('#tiering-operator-container').css('display','block');
                }else{
                    $('#choose-alert').css('display','block');
                    $('#operator-container').css('display','none'); 
                    $('#tiering-container').css('display','none');
                    $('#tiering-operator-container').css('display','none');
                }
            });

            $('#billing-profile-form').submit(function(e){
                e.preventDefault();

                /**
                 * Validate From & Up to value in Tiering
                 */

                var toValidate = $(".validateTo");
                toValidate.each(function(i){
                    $(this).remove();
                });

                var tieringFrom = $('input[name^="tieringFR"]'),
                    tieringTo = $('input[name^="tieringUP"]'),
                    nameTo,
                    charAtFrom,
                    nameFromLength,
                    validation=0;
                

                tieringFrom.each(function (i) {

                    nameFrom = $(this).attr("name");

                    nameFromLength = nameFrom.length;

                    if (nameFromLength == 16){
                        charAtFrom = nameFrom.charAt(10);
                    }

                    if (nameFromLength == 17){
                        charAtFrom = nameFrom.charAt(10)+nameFrom.charAt(11);
                    }
                    
                    nameTo = $('input[name ="tieringUP['+charAtFrom+'][tr]"]').val();
                    
                    if (!($(this).val() == 0 || nameTo == 0)){
                        
                        if (parseInt($(this).val()) >= parseInt(nameTo)){
                            validateTo = "<span class='validateTo' style='color:red;font-weight:bold;'> &nbsp * Up to must bigger than from to!</span>";
                            $('input[name ="tieringUP['+charAtFrom+'][tr]"]').after(validateTo);
                            validation=1;
                            return false;
                        }
                    }
                });

                /**
                 * Validate From & Up to value in Tiering - Operator
                 */

                var tOperatorFrom = $('input[name^="tOperatorFR"]'),
                    tOperatorUP = $('input[name^="tOperatorUP"]'),
                    nameTo,
                    charAtFrom,
                    nameFromLength,
                    validationTO=0;

                tOperatorFrom.each(function (i) {

                    nameFrom = $(this).attr("name");

                    nameFromLength = nameFrom.length;

                    if (nameFromLength == 18){
                        charAtFrom = nameFrom.charAt(12);
                    }

                    if (nameFromLength == 19){
                        charAtFrom = nameFrom.charAt(12)+nameFrom.charAt(13);
                    }
                    
                    nameTo = $('input[name ="tOperatorUP['+charAtFrom+'][tr]"]').val();
                    
                    if (!($(this).val() == 0 || nameTo == 0)){
                        
                        if (parseInt($(this).val()) >= parseInt(nameTo)){
                            validateTo = "<span class='validateTo' style='color:red;font-weight:bold;'> &nbsp * Up to must bigger than from to!</span>";
                            $('input[name ="tOperatorUP['+charAtFrom+'][tr]"]').after(validateTo);
                            validationTO=1;
                            return false;
                        }
                    }
                });

                /** 
                 * Validation for no duplicate name in billing
                 */

                var $form = $('#billing-profile-form');
                var billingName = $('#added_billing_name').val();

                if (validation == 0 && validationTO == 0){
                    $.ajax({
                        url: "{{url('find_billing_name')}}/"+billingName,         
                        type: "GET",
                        success: function (data) {
                            $('#error-billing-name').hide();
                            $form.attr('post', "{{ route('billing.create') }}").off('submit').submit();
                        },
                        error: function() { 
                            $('#error-billing-name').show();
                            alert('Billing name already exist!');
                        }
                    });
                }
            });
        } );

        function loadOperator(operatorOp,CSRF_TOKEN){
            operatorOp.select2({
                ajax: { 
                    url: "{{route('operator.all')}}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        
                        if (params.term == undefined){
                            params.term = ''
                        }

                        return {
                            _token: CSRF_TOKEN,
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }

            });
        }

        function fixingOperatorNameIndexForm(operatorOP,operatorPR){
            operatorOP.each(function (i) {
                $(this).attr('name','operatorOP['+i+'][operator]');
            });

            operatorPR.each(function (i) {
                $(this).attr('name','operatorPR['+i+'][price]');
            });
        }

        function fixingTieringNameIndexForm(tieringFrom,tieringTo,tieringPrice){
            tieringFrom.each(function (i) {
                $(this).attr('name','tieringFR['+i+'][tr]');
            });

            tieringTo.each(function (i) {
                $(this).attr('name','tieringUP['+i+'][tr]');
            });

            tieringPrice.each(function (i) {
                $(this).attr('name','tieringPR['+i+'][price]');
            });
        }

        function fixingTieringOperatorNameIndexForm(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo){
            tOperatorFrom.each(function (i) {
                $(this).attr('name','tOperatorFR['+i+'][tr]');
            });

            tOperatorTo.each(function (i) {
                $(this).attr('name','tOperatorUP['+i+'][tr]');
            });

            tOperatorOP.each(function (i) {
                $(this).attr('name','tOperatorOP['+i+'][operator]');
            });

            tOperatorPR.each(function (i) {
                $(this).attr('name','tOperatorPR['+i+'][price]');
            });
        }

        function requiredSetting(type){

            var operatorOP =$('select[name^="operatorOP"]'),
                operatorPR =$('input[name^="operatorPR"]'),
                tieringFrom     =$('input[name^="tieringFR"]'),
                tieringTo       =$('input[name^="tieringUP"]'),
                tieringPrice    =$('input[name^="tieringPR"]'),

                tOperatorFrom   =$('input[name^="tOperatorFR"]'),
                tOperatorTo     =$('input[name^="tOperatorUP"]'),
                tOperatorOP     =$('select[name^="tOperatorOP"]'),
                tOperatorPR     =$('input[name^="tOperatorPR"]'),

                o,t,to;
            
            if (type == 'operator'){
                o = true;
                t = false;
                to = false;
            }else if (type == 'tiering'){
                o = false;
                t = true;
                to = false;
            }else if (type == 'tiering-operator'){
                o = false;
                t = false;
                to = true;
            }
            
            operatorOP.each(function (i) {
                $(this).prop('required',o);
            });

            operatorPR.each(function (i) {
                $(this).prop('required',o);
            });

            tieringFrom.each(function (i) {
                $(this).prop('required',t);
            });

            tieringTo.each(function (i) {
                $(this).prop('required',t);
            });

            tieringPrice.each(function (i) {
                $(this).prop('required',t);
            });

            tOperatorFrom.each(function (i) {
                $(this).prop('required',to);
            });

            tOperatorTo.each(function (i) {
                $(this).prop('required',to);
            });

            tOperatorOP.each(function (i) {
                $(this).prop('required',to);
            });

            tOperatorPR.each(function (i) {
                $(this).prop('required',to);
            });
        }
    </script>
@endsection