    <!--   
        Modal for Client Details
    -->

    <div class="modal fade" id="client_details" tabindex="-1" role="dialog" aria-labelledby="client_details">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Client Details</h3>
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
                            <td class = "cdm-td-left">Customer ID</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="customerID" readonly="readonly" disabled />
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
                            <td title="Press to update client" width='80%' class = "td-edit-delete">
                                <span id="editID" 
                                        class = "span1-edit-delete" style="cursor:pointer;">Edit </span><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                            <td title="Press to delete client" width='20%' class = "td-edit-delete">
                                <span  id = "deleteID_Detail" class = "span2-edit-delete" style="cursor:pointer;" >Delete</span><i class="close-edit-delete las la-window-close"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button title="Press to see users of this client" id="apiAccounts" type="button" class = "api-accounts" style="cursor:pointer;" >API Users</button>
            </div>
            </div>
        </div>
    </div>

    <!--   
        Modal for Edit Client Details
    -->

    <div class="modal fade" id="client_edit" tabindex="-1" role="dialog" aria-labelledby="client_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Client Details</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('client.update') }}">
                @csrf
                    <input type="hidden" id = "clientEditID" name = "clientEditID" />
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Company Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="companyNameEdit"  value="{{old('edited_company_name')}}" name="edited_company_name" maxlength="100" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Company URL</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="companyURLEdit" value="{{old('edited_company_url')}}" name="edited_company_url" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Country</td>
                            <td class = "edm-td-right-select">
                                <select id = "countryEdit" class="uk-width-1-1 edm-select" name="edited_country" required>
                                    <option value="" selected >@lang('app.no_country')</option>
                                    @foreach ($all_countries as $country)
                                        <option value="{{$country->COUNTRY_CODE}}">{{$country->COUNTRY_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Contact Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="contactNameEdit" value="{{old('edited_contact_name')}}" name="edited_contact_name" maxlength="32" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Contact Email</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="contactEmailEdit" value="{{old('edited_contact_email')}}" name="edited_contact_email" maxlength="32" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Contact Phone</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="contactPhoneEdit" value="{{old('edited_contact_phone')}}" name="edited_contact_phone" maxlength="32" pattern="(\()?(\+62|62|0)(\d{2,3})?\)?[ .-]?\d{2,4}[ .-]?\d{2,4}[ .-]?\d{2,4}" title=" Only Accept Indonesia Phone Number" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Customer ID</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="customerIDEdit" value="{{old('edited_customer_id')}}" name="edited_customer_id" maxlength="32" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Contact Address</td>
                            <td class = "edm-td-right">
                                <textarea class = "edm-input" id="contactAddressEdit" name="edited_contact_address" style="height:90px;" required>{{old('edited_contact_address')}}</textarea>
                            </td> 
                        </tr>
                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td title="Press to update client" width='80%' class = "td-edit-delete">
                                
                            <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Save Edit" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                            <td title="Press to delete client" width='20%' class = "td-edit-delete">
                                <span id = "deleteID_Edit" class = "span2-edit-delete" style="cursor:pointer;" >Delete </span><i class="close-edit-delete las la-window-close"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button title="Press to see users of this client" id="editApiAccounts" type="button" style="background-color:white;border:1px solid white;padding:5px 15px 5px 15px;color:black;border-radius: 8px;cursor:pointer;">API Users</button>
            </div>
            </div>
        </div>
    </div>

    <!--   
        Modal for Billing Options
    -->

    <div class="modal fade" id="billing_options" tabindex="-1" role="dialog" aria-labelledby="billing_options">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                    <h3 class="modal-title">Billing Options</h3>
                </div>
                <div class="modal-body">
                    <form>
                        <table class = "bo-table">
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-cn-span">Company Name</span> 
                                </td>
                                <td width='75%'>
                                    <input class = "bo-cn-input" id="firstname" />
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                &nbsp
                                </td>
                                <td width='75%'>
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-ro-span" >Report Options*:</span> 
                                </td>
                                <td width='75%'>
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td colspan='2' width='100%' style="color:black;">
                                    <input width="30px" height="30px" type="checkbox"/><span class = "bo-nbs-input" >Non-billed SMS</span> 
                                </td>
                            </tr>
                            <tr class = "bo-tr">
                                <td>
                                    <input class ="bo-nbs-input-2" id="firstname" />
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td colspan='2' width='100%' style="color:black;">
                                    <input width="30px" height="30px" type="checkbox" /><span class = "bo-3-input" >include error codes in the report</span> 
                                </td>
                            </tr>

                            <tr class = "bo-tr">
                                <td colspan='2' width='100%' style="color:black;">
                                    <input width="30px" height="30px" type="checkbox" /><span class = "bo-3-input" >"Unknown" as "Delivered"</span> 
                                </td>
                            </tr>

                            <tr class = "bo-tr">
                                <td colspan='2' width='100%' style="color:black;">
                                    <input width="30px" height="30px" type="checkbox" /><span class = "bo-3-input" >"Pending" as "Delivered"</span> 
                                </td>
                            </tr>

                        </table>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class = "bo-cancel">Cancel</button>
                    <button type="button" class = "bo-save">Save</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#editID').on('click',function(event){
                var clientID = $('#clientID').val();
                $("#edit_client"+clientID).click();
            });


            $('#deleteID_Edit').on('click',function(event){
                var clientID = $('#clientID').val();
                if (!clientID) clientID = $('#clientEditID').val();

                var con = confirm('are you sure delete this client?');

                if (con == true) { 
                    location.href = "{{ url('client') }}/"+clientID;
                } 
            });

            $('#deleteID_Detail').on('click',function(event){
                var clientID = $('#clientID').val();

                var con = confirm('are you sure delete this client?');

                if (con == true) { 
                    location.href = "{{ url('client') }}/"+clientID;
                } 
            });
        });

        $('#client_details').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var clientID = button.data('client_id');
            var companyName = button.data('company_name');
            var country = button.data('country');
            var companyURL = button.data('company_url');
            
            var contactName = button.data('contact_name');
            var contactEmail = button.data('contact_email');
            var contactPhone = button.data('contact_phone');
            var contactAddress = button.data('contact_address');

            var customerID = button.data('customer_id');

            var createdBy = button.data('created_by');
            var createdAt = button.data('created_at');
            var updatedBy = button.data('updated_by');
            var updatedAt = button.data('updated_at');

            var modal = $(this);

            modal.find('.modal-body #clientID').val(clientID);
            modal.find('.modal-body #companyName').val(companyName);
            modal.find('.modal-body #country').val(country);
            modal.find('.modal-body #companyURL').val(companyURL);

            modal.find('.modal-body #contactName').val(contactName);
            modal.find('.modal-body #contactEmail').val(contactEmail);
            modal.find('.modal-body #contactPhone').val(contactPhone);
            modal.find('.modal-body #contactAddress').val(contactAddress);
            modal.find('.modal-body #customerID').val(customerID);

            modal.find('.modal-body #createdBy').val(createdBy);
            modal.find('.modal-body #createdAt').val(createdAt);
            modal.find('.modal-body #updatedBy').val(updatedBy);
            modal.find('.modal-body #updatedAt').val(updatedAt);

            $('#apiAccounts').on('click',function(event){
                location.href = "{{ url('user/client') }}/"+clientID;
            });
            
        })

        $('#client_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var clientID = button.data('client_edit_id');
            var companyName = button.data('company_name');
            var country = button.data('country');
            var companyURL = button.data('company_url');

            var contactName = button.data('contact_name');
            var contactEmail = button.data('contact_email');
            var contactPhone = button.data('contact_phone');
            var contactAddress = button.data('contact_address');
            var customerIDEdit = button.data('customer_id');

            var modal = $(this);

            modal.find('.modal-body #clientEditID').val(clientID);
            modal.find('.modal-body #companyNameEdit').val(companyName);
            modal.find('.modal-body #countryEdit').val(country);
            modal.find('.modal-body #companyURLEdit').val(companyURL);

            modal.find('.modal-body #customerIDEdit').val(customerIDEdit);

            modal.find('.modal-body #contactNameEdit').val(contactName);
            modal.find('.modal-body #contactEmailEdit').val(contactEmail);
            modal.find('.modal-body #contactPhoneEdit').val(contactPhone);
            modal.find('.modal-body #contactAddressEdit').val(contactAddress);

            $('#editApiAccounts').on('click',function(event){
                location.href = "{{ url('user/client') }}/"+clientID;
            });
        })
    </script>