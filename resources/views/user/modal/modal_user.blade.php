    

    <div class="modal fade" id="user_edit" tabindex="-1" role="dialog" aria-labelledby="user_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Change User Details</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('user.update') }}">
                @csrf
                    <input type="hidden" id = "editedUserID" name = "edited_user_id"/>
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Client</td>
                            <td class = "edm-td-right-select">
                                <select id="editedClientID" name="edited_client_id" class="uk-width-1-1 edm-select" required>
                                    <option value="">@lang('app.no_client')</option>
                                    @foreach ($all_clients as $client)
                                        <option value="{{$client->CLIENT_ID}}" {{ (old("edited_client_id") == $client->CLIENT_ID ? "selected":"") }}>{{$client->COMPANY_NAME}}</option>
                                    @endforeach
                                </select>
                            
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Cobrander ID</td>
                            <td class = "edm-td-right-select">
                                <select id = "userEditedCobranderID" class="uk-width-1-1 edm-select" name="edited_cobrander_id" required>
                                    <option value="" selected >@lang('app.no_cobrander')</option>
                                    @foreach ($all_cobranders as $cobrander)
                                        <option value="{{$cobrander->COBRANDER_NAME}}" {{ (old("edited_cobrander_id") == $cobrander->COBRANDER_NAME ? "selected":"") }}>{{$cobrander->COBRANDER_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Activate</td>
                            <td class = "edm-td-right-select">
                                <input style="border:1px solid #aaa;width:24px;" type="checkbox" id="editedUserActivate" name="edited_user_activate" value="1" class="uk-width-1-1" />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Status Delivery</td>
                            <td class = "edm-td-right-select">
                                <input type="radio" id="editedStatusDeliveryYes" name="edited_status_delivery" value="1" required/> <label id="yes_status_delivery" for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="editedStatusDeliveryNo" name="edited_status_delivery" value="0" required/> <label id="no_status_delivery" for="No" class="radio-label">No</label>
                            </td> 
                        </tr>

                        <tr id="deliv_url">
                            <td class = "edm-td-left">Delivery URL</td>
                            <td class = "edm-td-right">
                                <input type='text' class = "edm-input" id="editedDeliveryURL"  value="{{old('edited_delivery_url')}}" name="edited_delivery_url" maxlength="255" />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Is Postpaid?</td>
                            <td class = "edm-td-right-select">
                                <input type="radio" id="editedIsPostPaidYes" name="edited_is_postpaid" value="1" required/> <label id="yes_post_paid" for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="editedIsPostPaidNo" name="edited_is_postpaid" value="0" required/> <label id="no_post_paid" for="No" class="radio-label">No</label>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Is Blacklist?</td>
                            <td class = "edm-td-right-select">
                                <input type="radio" id="editedIsBLYes" name="edited_is_bl" value="1" required/> <label id="yes_bl" for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="editedIsBLNo" name="edited_is_bl" value="0" required/> <label id="no_bl" for="No" class="radio-label">No</label>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Is OJK</td>
                            <td class = "edm-td-right-select">
                                <input type="radio" id="editedIsOJKYes" name="edited_isojk" value="1" required/> <label id="yes_isojk" for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="editedIsOJKNo" name="edited_isojk" value="0" required/> <label id="no_isojk" for="No" class="radio-label">No</label>
                            </td> 
                        </tr>
                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='80%' class = "td-edit-delete">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Save Edit" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="change_password" tabindex="-1" role="dialog" aria-labelledby="change_password">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Change User Password</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('user.update.password') }}">
                @csrf
                    <input type="hidden" id = "editedUserID" name = "edited_user_id"/>
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Password</td>
                            <td class = "edm-td-right">
                                <input type='text' class = "edm-input" id="editedUserPassword"  value="{{old('edited_user_password')}}" name="edited_user_password" maxlength="32" required/>
                            </td> 
                        </tr>
                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='80%' class = "td-edit-delete">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Save Edit" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            
            $('#yes_status_delivery').on('click',function(event){
                $('#editedStatusDeliveryYes').prop('checked',true);
            });

            $('#no_status_delivery').on('click',function(event){
                $('#editedStatusDeliveryNo').prop('checked',true);
            });

            $('#yes_post_paid').on('click',function(event){
                $('#editedIsPostPaidYes').prop('checked',true);
            });

            $('#yes_bl').on('click',function(event){
                $('#editedIsBLYes').prop('checked',true);
            });

            $('#yes_isojk').on('click',function(event){
                $('#editedIsOJKYes').prop('checked',true);
            });

            $('#no_post_paid').on('click',function(event){
                $('#editedIsPostPaidNo').prop('checked',true);
            });

            $('#no_bl').on('click',function(event){
                $('#editedIsBLNo').prop('checked',true);
            });

            $('#no_isojk').on('click',function(event){
                $('#editedIsOJKNo').prop('checked',true);
            });

        });
        
        $('#user_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var userID = button.data('user_id');
            var clientID = button.data('client_id');
            var cobranderID = button.data('cobrander_id');
            var activate = button.data('activate');
            
            var statusDelivery = button.data('status_delivery');

            var deliveryURL = button.data('delivery_url');
            var ispostpaid = button.data('ispostpaid');

            var isbl = button.data('isbl');

            var isojk = button.data('isojk');
            
            var modal = $(this);


            if (activate == 1){
                $('#editedUserActivate').prop('checked',true);
            }

            if (statusDelivery == 1){
                modal.find('.modal-body #editedStatusDeliveryYes').prop('checked',true);
                $("#deliv_url").show() 
                $('#editedDeliveryURL').prop('required', true);
            }else{
                modal.find('.modal-body #editedStatusDeliveryNo').prop('checked',true);
                $("#deliv_url").hide() 
                $("#editedDeliveryURL").val('')
                $('#editedDeliveryURL').prop('required', false);
            }

            if (ispostpaid == 1){
                modal.find('.modal-body #editedIsPostPaidYes').prop('checked',true);
            }else{
                modal.find('.modal-body #editedIsPostPaidNo').prop('checked',true);
            }

            if (isbl == 1){
                modal.find('.modal-body #editedIsBLYes').prop('checked',true);
            }else{
                modal.find('.modal-body #editedIsBLNo').prop('checked',true);
            }

            if (isojk == 1){
                modal.find('.modal-body #editedIsOJKYes').prop('checked',true);
            }else{
                modal.find('.modal-body #editedIsOJKNo').prop('checked',true);
            }

            modal.find('.modal-body #editedUserID').val(userID);
            modal.find('.modal-body #editedClientID').val(clientID);
            modal.find('.modal-body #userEditedCobranderID').val(cobranderID);
            modal.find('.modal-body #editedDeliveryURL').val(deliveryURL);

            $('#userEditedCobranderID').select2();

            $('#editedStatusDeliveryYes').click(function(){
                $("#deliv_url").show() 
                $('#editedDeliveryURL').prop('required', true);
            })

            $('#editedStatusDeliveryNo').click(function(){
                $("#deliv_url").hide() 
                $("#editedDeliveryURL").val('')
                $('#editedDeliveryURL').prop('required', false);
            })
        })

        $('#change_password').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var u_id = button.data('user_id');
            var u_password = button.data('password');
            
            var modal = $(this);

            modal.find('.modal-body #editedUserID').val(u_id);
            modal.find('.modal-body #editedUserPassword').val(u_password);
        })

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