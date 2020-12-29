@extends('layouts.main',['title'=> 'SMS API ADMIN | AGENT PAGE'])

@push('breadcrumb')
    <li class="uk-active" style="color:black;font-weight:bold;">@lang('app.cobrander_agent_management')</li>
@endpush

@section('content')
    
    <div style="padding-top:30px;">
        <h3 style="color:#272727;"> Agent List </h3>
    </div>

    <div class= "c-div-1">
        @include('components.alert-danger', ['autoHide' => false])
        @include('components.alert-success', ['autoHide' => false])
        <div class = "c-div-2">
            <h3 id = "arrow" style="color:white;"><img src="{{url('images/icon/icon-contact.png')}}"/> &nbsp Agent Registration <i class="arrow-down las la-angle-down"></i></h3>
            <form id = "agent_form" class="form-index uk-form uk-margin-top" method="POST" action="{{ route('agent.create') }}">
                @csrf

                <hr style="color:white;"></hr>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Agent Name</h6></div>
                    <div class="uk-width-1-3 c-input-div-1"><input type="text" value="{{old('added_agent_name')}}" name="added_agent_name" maxlength="20" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required /></div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Agent Queue Name</h6></div>
                    <div class="uk-width-1-3 c-input-div-1"><input type="text" value="{{old('added_agent_queue_name')}}" name="added_agent_queue_name" maxlength="20" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;" required /></div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Agent Description</h6></div>
                    <div class="uk-width-1-3 c-input-div-1"><textarea type="text" name="added_agent_description" class="uk-width-1-1" style= "background-color:white;border-color:white;color:black;height:90px;" required>{{old('added_agent_description')}}</textarea></div>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                    <button class="uk-button uk-float-right" style="color:black;font-weight:500;">Create Agent</button>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                </div>
            </form>
        </div>
    </div>

    <div style="margin-top:30px;">
    </div>

    <table id="agent_table" class="display" class = "c-table-1" style="color:black;">
        <thead class = "c-thead-1">
            <tr>
                <th class = "c-th-td">Agent Name</th>
                <th class = "c-th-td">Agent Queue Name</th>
                <th class = "c-th-td">Action(s)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($all_agents as $agent)
            <tr>
                <td class = "c-th-td">{{$agent->AGENT_NAME}}</td>
                <td class = "c-th-td">{{$agent->AGENT_NAME_QUEUE}}</td>
                <td class = "c-td-action">

                <img data-toggle="modal" 
                    data-target="#agent_details" 
                    data-agent_id = "{{$agent->AGENT_ID}}" 
                    data-agent_name = "{{$agent->AGENT_NAME}}" 
                    data-agent_queue_name = "{{$agent->AGENT_NAME_QUEUE}}" 
                    data-agent_description = "{{$agent->AGENT_DESCRIPTION}}"

                    @if ($agent->CREATED_BY)
                        data-created_by = "{{$agent->ADMIN_DISPLAY_NAME}}" 
                        data-created_on = "{{$agent->CREATED_AT}}" 
                    @else
                        data-created_by = "" 
                        data-created_on = "" 
                    @endif

                    @if ($agent->UPDATED_BY)
                        data-updated_by = "{{$agent->ADMIN_DISPLAY_NAME}}" 
                        data-updated_on = "{{$agent->UPDATED_AT}}" 
                    @else
                        data-updated_by = "" 
                        data-updated_on = "" 
                    @endif

                    style="width:25px;height:25px;cursor:pointer;" 
                    src="{{url('images/icon/circle-detail.png')}}" />

                &nbsp &nbsp

                <img data-toggle="modal" 
                    data-target="#agent_edit" 
                    data-agent_id = "{{$agent->AGENT_ID}}" 
                    data-agent_name = "{{$agent->AGENT_NAME}}" 
                    data-agent_queue_name = "{{$agent->AGENT_NAME_QUEUE}}" 
                    data-agent_description = "{{$agent->AGENT_DESCRIPTION}}" 
        
                    id="edit_agent{{$agent->AGENT_ID}}"

                    style="width:25px;height:25px;cursor:pointer;" 
                    src="{{url('images/icon/circle-pencil.png')}}" />
                &nbsp &nbsp
                
                <a id="delete_agent{{$agent->AGENT_ID}}" href="{{ route('agent.delete',['AGENT_ID' => $agent->AGENT_ID]) }}" 
                    onclick="return confirm('are you sure delete this agent?');">
                    <img 
                        style="width:25px;height:25px;cursor:pointer;"
                        src="{{url('images/icon/circle-delete.png')}}" />
                </a>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        $(document).ready(function() {
            $('#agent_table').DataTable();

            $('#arrow').click(function(e){
                e.preventDefault();
                $('#agent_form').stop().slideToggle();
            });
            
        } );
    </script>
@endsection