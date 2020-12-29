@extends('layouts.main',['title'=> 'SMS API ADMIN | COBRANDER PAGE'])

@push('breadcrumb')
    <li class="uk-active" style="color:black;font-weight:bold;">@lang('app.cobrander_management')</li>
@endpush

@section('content')
    
    <div style="padding-top:30px;">
        <h3 style="color:#272727;"> Cobrander List </h3>
    </div>

    <div class= "c-div-1">
        @include('components.alert-danger', ['autoHide' => false])
        @include('components.alert-success', ['autoHide' => false])
        <div class = "c-div-2">
            <h3 id = "arrow" style="color:white;"><img src="{{url('images/icon/icon-virtualnumber.png')}}"/> &nbsp Cobrander Registration <i class="arrow-down las la-angle-down"></i></h3>
            <form id = "cobrander_form" class="form-index uk-form uk-margin-top" method="POST" action="{{ route('cobrander.create') }}">
                @csrf

                <hr style="color:white;"></hr>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Cobrander Name</h6></div>
                    <div class="uk-width-1-3 c-input-div-1">
                    <select name="added_cobrander_name" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                        <option value="">@lang('app.no_user')</option>
                        @foreach ($all_users as $user)
                            <option value="{{$user->USER_NAME}}" {{ (old("added_cobrander_name") == $user->USER_NAME ? "selected":"") }}>{{$user->USER_NAME}}</option>
                        @endforeach
                    </select>

                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Agent Name</h6></div>
                    <div class="uk-width-1-3 c-input-div-1">
                        <select name="added_agent_id" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                            <option value="">@lang('app.no_agent')</option>
                            @foreach ($all_agents as $agent)
                                <option value="{{$agent->AGENT_ID}}" {{ (old("added_agent_id") == $agent->AGENT_ID ? "selected":"") }}>{{$agent->AGENT_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Operator Name</h6></div>
                    <div class="uk-width-1-3 c-input-div-1">
                        <select id="added_operators" name="added_operator_name[]" class="uk-width-1-1" style="height:100px;background-color:white;border-color:white;color:black;" multiple="multiple" required>
                            <option value="">@lang('app.no_operator')</option>
                            @foreach ($all_operators as $operator)
                                <option value="{{$operator->OPERATOR_NAME}}" {{ (old("added_operator_name") == $operator->OPERATOR_NAME ? "selected":"") }}>{{$operator->OPERATOR_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                    <button title="Press to create cobrander" class="uk-button uk-float-right" style="color:black;font-weight:500;">Create Cobrander</button>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                </div>
            </form>
            
        </div>
    </div>

    <div style="margin-top:30px;">
    </div>

    <table id="cobrander_table" class="display" class = "c-table-1" style="color:black;">
        <thead class = "c-thead-1">
            <tr>
                <th class = "c-th-td" style="border-bottom:3px solid white;">Cobrander Name</th>
                <th class = "c-th-td" style="border-bottom:3px solid white;">Agent Name</th>
                <th class = "c-th-td" style="border-bottom:3px solid white;">Operator Name</th>
                <th class = "c-th-td" style="border-bottom:3px solid white;">Action(s)</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 0; $counter_inside=0; $firstLine=0;?>
            @foreach ($all_cobranders as $cobrander)
            
            @if (!empty($all_cobranders[$counter-1]['COBRANDER_NAME']))
                @if ($cobrander->COBRANDER_NAME == $all_cobranders[$counter-1]['COBRANDER_NAME'])
                    <script>
                        $(document).ready(function(){

                            <?php $counter_inside++; ?>

                            $this = $('#cob_name_{{$cobrander->COBRANDER_NAME}}');

                            value = <?php echo "'".$cobrander->OPERATOR_NAME."'"; ?>

                            value_id = <?php echo "'".$cobrander->COBRANDER_ID."'"; ?>

                            <?php
                                $temp1 = $counter-$counter_inside;
                                $temp2 = $counter;
                            ?>

                            <?php
                                for ($i=$temp1;$i<$temp2;$i++){
                            ?>
                                value = '<?php echo $all_cobranders[$i]["OPERATOR_NAME"]; ?>'+','+ value;

                                value_id = '<?php echo $all_cobranders[$i]["COBRANDER_ID"]; ?>'+','+ value_id

                            <?php
                                }
                            ?>  
                            
                            $this.find('td:nth-child(' + 4 + ')').find('img').attr("data-operator",value)
                            
                            consV = value.split(',');
                            value = '';

                            for (o=0;o<consV.length;o++){
                                value += '- '+consV[o]+' <br> ';
                            }

                            $this.find('td:nth-child(' + 4 + ')').find('img').attr("data-cobrander_id",value_id)

                            $this.find('td:eq(2)').html(value);

                        })
                    </script>
                @else
                    <?php $counter_inside=0; $firstLine=0; ?>
                    <tr id="cob_name_{{$cobrander->COBRANDER_NAME}}">
                        <td class = "c-th-td" style="border-top:1px solid white;">{{$cobrander->COBRANDER_NAME}}</td>
                        <td class = "c-th-td" style="border-top:1px solid white;">{{$cobrander->AGENT_NAME}}</td>
                        <td class = "c-th-td" style="border-top:1px solid white;">{{$cobrander->OPERATOR_NAME}}</td>
                        <td class = "c-td-action" style="border-top:1px solid white;">
                            <span style="display:none;"> {{$cobrander->COBRANDER_NAME}} </span>

                            <img data-toggle="modal" 
                                data-target="#cobrander_details" 
                                data-cobrander_id = "{{$cobrander->COBRANDER_ID}}" 
                                data-cobrander_name = "{{$cobrander->COBRANDER_NAME}}" 
                                data-agent_name = "{{$cobrander->AGENT_NAME}}" 
                                data-operator = "{{$cobrander->OPERATOR_NAME}}"

                                @if ($cobrander->CREATED_BY)
                                    data-created_by = "{{$cobrander->ADMIN_DISPLAY_NAME}}" 
                                    data-created_on = "{{$cobrander->CREATED_AT}}" 
                                @else
                                    data-created_by = "" 
                                    data-created_on = "" 
                                @endif

                                style="width:25px;height:25px;cursor:pointer;" 
                                src="{{url('images/icon/circle-detail.png')}}" />

                            &nbsp &nbsp

                            <img data-toggle="modal" 
                                data-target="#cobrander_edit" 
                                data-cobrander_id = "{{$cobrander->COBRANDER_ID}}" 
                                data-cobrander_name = "{{$cobrander->COBRANDER_NAME}}" 
                                data-agent_name = "{{$cobrander->AGENT_ID}}" 
                                data-operator = "{{$cobrander->OPERATOR_NAME}}" 
                                
                                id="edit_cobrander{{$cobrander->COBRANDER_ID}}"

                                style="width:25px;height:25px;cursor:pointer;" 
                                src="{{url('images/icon/circle-pencil.png')}}" />

                            &nbsp &nbsp

                            <img class="delete_cobrander_x" 
                                data-cobrander_id = "{{$cobrander->COBRANDER_ID}}" 
                                style="width:25px;height:25px;cursor:pointer;"
                                src="{{url('images/icon/circle-delete.png')}}" />
                                
                        </td>
                    </tr>
                @endif
            @else
                <?php $firstLine=1; ?>
                <tr id="cob_name_{{$cobrander->COBRANDER_NAME}}">
                    <td class = "c-th-td" style="border-top:1px solid white;">{{$cobrander->COBRANDER_NAME}}</td>
                    <td class = "c-th-td" style="border-top:1px solid white;">{{$cobrander->AGENT_NAME}}</td>
                    <td class = "c-th-td" style="border-top:1px solid white;">{{$cobrander->OPERATOR_NAME}}</td>
                    <td class = "c-td-action" style="border-top:1px solid white;">
                        <span style="display:none;"> {{$cobrander->COBRANDER_NAME}} </span>

                        <img data-toggle="modal" 
                            data-target="#cobrander_details" 
                            data-cobrander_id = "{{$cobrander->COBRANDER_ID}}" 
                            data-cobrander_name = "{{$cobrander->COBRANDER_NAME}}" 
                            data-agent_name = "{{$cobrander->AGENT_NAME}}" 
                            data-operator = "{{$cobrander->OPERATOR_NAME}}"

                            @if ($cobrander->CREATED_BY)
                                data-created_by = "{{$cobrander->ADMIN_DISPLAY_NAME}}" 
                                data-created_on = "{{$cobrander->CREATED_AT}}" 
                            @else
                                data-created_by = "" 
                                data-created_on = "" 
                            @endif

                            @if ($cobrander->UPDATED_BY_COBRANDER)
                                data-updated_by = "{{$cobrander->ADMIN_DISPLAY_NAME}}" 
                                data-updated_on = "{{$cobrander->UPDATED_AT_COBRANDER}}" 
                            @else
                                data-updated_by = "" 
                                data-updated_on = "" 
                            @endif

                            style="width:25px;height:25px;cursor:pointer;" 
                            src="{{url('images/icon/circle-detail.png')}}" />

                        &nbsp &nbsp

                        <img data-toggle="modal" 
                            data-target="#cobrander_edit" 
                            data-cobrander_id = "{{$cobrander->COBRANDER_ID}}" 
                            data-cobrander_name = "{{$cobrander->COBRANDER_NAME}}" 
                            data-agent_name = "{{$cobrander->AGENT_ID}}" 
                            data-operator = "{{$cobrander->OPERATOR_NAME}}" 
                            
                            id="edit_cobrander{{$cobrander->COBRANDER_ID}}"

                            style="width:25px;height:25px;cursor:pointer;" 
                            src="{{url('images/icon/circle-pencil.png')}}" />

                        &nbsp &nbsp

                        <img class="delete_cobrander_x" 
                            data-cobrander_id = "{{$cobrander->COBRANDER_ID}}" 
                            style="width:25px;height:25px;cursor:pointer;"
                            src="{{url('images/icon/circle-delete.png')}}" />
                            
                    </td>
                </tr>
            @endif
            
            <?php
                $counter++;
            ?>

            @endforeach
        </tbody>
    </table>
    

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function() {
            $('#cobrander_table').DataTable();

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#cobrander_form').stop().slideToggle();
            });

            $('#added_operators').select2({
                placeholder: "@lang('app.no_operator')",
                allowClear: true
            });

            $('.delete_cobrander_x').each(function () {
                var $this = $(this);
                $this.on("click", function () {
                    
                    cobranderId = $(this).data('cobrander_id');
                    
                    var con = confirm('are you sure delete this cobrander?');

                    if (con == true) { 
                        location.href = '{{ url("cobrander") }}/'+JSON.stringify(cobranderId);
                    } 

                });
            });
        } );
    </script>
@endsection