<div class="modal fade" id="generate_billing" tabindex="-1" role="dialog" aria-labelledby="generate_billing">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Billing Report</h3>
            </div>
            <div class="modal-body">
                <form method="post" action="{{route('download.user.billing')}}">
                    @csrf
                    <table class="cdm-table">

                        <tr>
                            <td class = "cdm-td-left">Company Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="companyName" readonly="readonly" disabled />
                                <input type="hidden" id="usernameHidden" name="added_username"/>
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Username</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="username" readonly="readonly" disabled />
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Year</td>
                            <td class = "cdm-td-right">
                                <select style="color:black;margin-top:10px;" name="added_report_year" class="bo-cn-input-no-width3" required>
                                    <option value="">@lang('app.no_year')</option>
                                    @foreach($years as $year)
                                        <option value="{{$year}}">{{$year}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Month</td>
                            <td class = "cdm-td-right">
                                <select style="color:black;margin-top:10px;" name="added_report_month" class="bo-cn-input-no-width3" required>
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
                            </td> 
                        </tr>
                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='68%' class = "td-edit-delete" title="Press to close window">
                                <span  data-dismiss="modal" class = "span2-edit-delete" style="cursor:pointer;padding-left:10px;" >Close</span><i style="float:right;" class="close-edit-delete las la-window-close"></i> 
                            </td>
                            <td width='32%' class = "td-edit-delete" title="Press to download report">
                                
                                <input type="submit" class="span2-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Download Report"><i class="close-edit-delete las la-download"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>

        $('#generate_billing').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var username = button.data('username');
            var usernameHidden = button.data('username');
            var companyName = button.data('company_name');

            var modal = $(this);

            modal.find('.modal-body #companyName').val(companyName);
            modal.find('.modal-body #usernameHidden').val(usernameHidden);
            modal.find('.modal-body #username').val(username);
        })
        
    </script>