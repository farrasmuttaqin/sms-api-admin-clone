<div class="modal fade" id="download_billing_reports" tabindex="-1" role="dialog" aria-labelledby="download_billing_reports">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Download Billing Reports</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('download.billing.reports') }}">
                @csrf
                    <div style="padding-left:16px;color:black;">Download all available reports for clients, not including SMS sent in the last 3 days. 
                    </div>
                    <table class="table-edit-delete" style="margin-top:10px;margin-bottom:20px;">
                        <tr>
                            <td class = "edm-td-left">Select the billing period</td>
                            <td class = "edm-td-right">
                                <select name="added_billing_month" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
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
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Download" /><i class="pencil-edit-delete las la-download"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>

        $('#download_billing_reports').on('show.bs.modal', function(event){})

    </script>