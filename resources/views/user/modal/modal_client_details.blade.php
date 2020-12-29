<div class="modal fade" id="detail_client" tabindex="-1" role="dialog" aria-labelledby="detail_client">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Client Details </h3>
                    <!-- of <span style='color:white;' id ='user_name'></span></h3> -->
            </div>
            <div class="modal-body">
                <form>
                    <table class="cdm-table">
                        <input type='hidden' id="clientID" />

                        <tr>
                            <td class = "cdm-td-left">Company Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="companyName" readonly="readonly" disabled />
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Country</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="country" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Company URL</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="companyURL" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Contact Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="contactName" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Contact Email</td>
                            <td class = "cdm-td-right">
                                <input type = "email" class = "cdm-input" id="contactEmail" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Contact Phone</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="contactPhone" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Contact Address</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="contactAddress" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Created By</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="createdBy" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Created On</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="createdAt" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Updated By</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="updatedBy" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Updated On</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="updatedAt" readonly="readonly" disabled />
                            </td> 
                        </tr>
                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='80%' class = "td-edit-delete">
                                
                            </td>
                            <td width='100%' class = "td-edit-delete">
                                <span  data-dismiss="modal" class = "span2-edit-delete" style="cursor:pointer;" >Close</span><i class="close-edit-delete las la-window-close"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>

        $('#detail_client').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var user_name = button.data('user_name');

            var clientID = button.data('client_id');
            var companyName = button.data('company_name');
            var country = button.data('country');
            var companyURL = button.data('company_url');
            
            var contactName = button.data('contact_name');
            var contactEmail = button.data('contact_email');
            var contactPhone = button.data('contact_phone');
            var contactAddress = button.data('contact_address');

            var createdBy = button.data('created_by');
            var createdAt = button.data('created_at');
            var updatedBy = button.data('updated_by');
            var updatedAt = button.data('updated_at');

            var modal = $(this);

            $("#user_name").text(user_name);

            modal.find('.modal-body #clientID').val(clientID);
            modal.find('.modal-body #companyName').val(companyName);
            modal.find('.modal-body #country').val(country);
            modal.find('.modal-body #companyURL').val(companyURL);

            modal.find('.modal-body #contactName').val(contactName);
            modal.find('.modal-body #contactEmail').val(contactEmail);
            modal.find('.modal-body #contactPhone').val(contactPhone);
            modal.find('.modal-body #contactAddress').val(contactAddress);

            modal.find('.modal-body #createdBy').val(createdBy);
            modal.find('.modal-body #createdAt').val(createdAt);
            modal.find('.modal-body #updatedBy').val(updatedBy);
            modal.find('.modal-body #updatedAt').val(updatedAt);
        })
        
    </script>