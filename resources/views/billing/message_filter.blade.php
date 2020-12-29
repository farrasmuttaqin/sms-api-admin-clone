@extends('layouts.main',['title'=> 'SMS API ADMIN | FILTER PAGE'])

@push('breadcrumb')
<li class="uk-active" style="color:black;font-weight:bold;">@lang('app.message_filter')</li>
@endpush

@section('content')

    <div class= "c-div-1">
        @include('components.alert-danger', ['autoHide' => false])
        @include('components.alert-success', ['autoHide' => false])
        <div class = "c-div-2">
            <h3 style="color:white;"><img style="padding-bottom:5px;" src="{{url('images/icon/icon-history.png')}}"/> &nbsp Message Content Based Report</h3>
            <form id="filter-form" class="form-index-show uk-form uk-margin-top" method="POST" action="{{ route('generate.sms.content') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf

                <hr style="color:white;"></hr>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">SMS API User</h6></div>
                    <div class="uk-width-1-4 c-input-div-1">
                        <select id="added_user" name="added_user" class="uk-width-1-1" style="height:28px;" required>
                        <option value="">@lang('app.no_user')</option>
                        @foreach ($all_users as $user)
                            <option value="{{$user->USER_NAME}}">{{$user->USER_NAME}}</option>
                        @endforeach
                        </select>
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Message Content File</h6></div>
                    <div class="uk-width-1-4 c-input-div-1">
                        <input name="added_file" id="content_id" onchange="checkfile(this);" style="background-color:white;color:black;border:1px solid #ddd;padding:4px 6px;border-radius:4px;max-width:100%;width:100%;" type="file" accept=".ods, .csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required />
                    </div>
                </div>

                <div class="uk-grid">
                    <div class="uk-width-1-6"><h6 class = "h-header">Report For Month</h6></div>
                    <div class="uk-width-1-4 c-input-div-1">
                        <select name="added_month" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                            <option value="">@lang('app.no_month')</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9">September</option>
                            <option value="10">October</option>
                            <option value="11">November</option>
                            <option value="12">December</option>
                        </select>
                    </div>
                </div>
        
                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                    <button type="submit" class="uk-button uk-float-right" style="color:black;font-weight:500;">Process</button>
                    <button id="cancel" type="reset" class="uk-button uk-float-right" style="color:black;font-weight:500;margin-right:20px;">Cancel</button>
                </div>

                <div class="uk-form-row uk-text-left" style="margin-top:30px;">
                </div>
            </form>
            
        </div>
        <br><br>
        <table id="billing_profile_table" class="display" class = "c-table-1" style="color:black;te">
            <thead class = "c-thead-1">
                <tr>
                    <th class = "c-th-td">User API</th>
                    <th class = "c-th-td">Created At</th>
                    <th class = "c-th-td">Report Name</th>
                    <th class = "c-th-td">Action(s)</th>
                </tr>
            </thead>
            <tbody>

            @foreach($all_filter_messages as $filter)
                <tr>
                    <td class = "c-th-td" style="text-align:center;">{{$filter->userAPI}}</td>
                    <td class = "c-th-td" style="text-align:center;">{{$filter->createdAt}}</td>
                    <td class = "c-th-td" style="text-align:center;">
                        @if($filter->isDone == "true")
                            {{$filter->reportName}}
                        @else
                            Failed to process
                        @endif
                    </td>
                    <td class = "c-td-action">
                        @if($filter->isDone == "true")

                        <a href="{{route('download.filter',['reportName'=>$filter->reportName])}}">
                            <img 
                                style="width:16px;height:16px;cursor:pointer;" 
                                src="{{url('images/icon/icon-download-all.png')}}" />
                        </a>
                        @else
                        -
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <script type="text/javascript" language="javascript">
        $(document).ready(function(){
            $('#billing_profile_table').DataTable({
                "oSearch": { "bSmart": false, "bRegex": true }
            });
        })
        function checkfile(sender) {
            var validExts = new Array(".xlsx", ".xls", ".csv", ".ods");
            var fileExt = sender.value;
            fileExt = fileExt.substring(fileExt.lastIndexOf('.'));
            if (validExts.indexOf(fileExt) < 0) {
                alert("Invalid file selected, valid files are of " +
                        validExts.toString() + " types.");
                
                $('#content_id').val('');

                return false;
            }
            else return true;
        }
    </script>
@endsection