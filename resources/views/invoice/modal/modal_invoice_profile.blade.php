    <div class="modal fade" id="profile_edit" tabindex="-1" role="dialog" aria-labelledby="profile_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Invoice Profile</h3>
            </div>
            <div class="modal-body"> 
                <form method = "POST" action = "{{ route('profile.update') }}">
                @csrf
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Client</td>
                            <td class = "edm-td-right-select">
                                <input type="hidden" id="edited_profile_id"  value="{{old('edited_profile_id')}}" name="edited_profile_id" required/>
                                <select id="edited_client" name="edited_client" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" disabled required>
                                    <option value="">@lang('app.no_client')</option>
                                    @foreach ($all_clients as $client)
                                        <option value="{{$client->CLIENT_ID}}">{{$client->COMPANY_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Payment Detail</td>
                            <td class = "edm-td-right-select">
                                <input type="hidden" id="edited_profile_id"  value="{{old('edited_profile_id')}}" name="edited_profile_id" required/>
                                <select id="edited_payment_detail" name="edited_payment_detail" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_bank')</option>
                                    @foreach ($all_banks as $bank)
                                        <option value="{{$bank->INVOICE_BANK_ID}}">{{$bank->BANK_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Is Auto Generate?</td>
                            <td class = "edm-td-right">
                                <input id="edited_auto_generate_yes" type="radio" name="edited_auto_generate" value="1" required/> <label for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="edited_auto_generate_no" name="edited_auto_generate" value="0" required/> <label for="No" class="radio-label">No</label>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Approved Name</td>
                            <td class = "edm-td-right">
                                <input id="edited_approved_name" type="text" class = "edm-input" id="edited_approved_name" value="{{old('edited_approved_name')}}" name="edited_approved_name"/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Approved Position</td>
                            <td class = "edm-td-right">
                                <input id="edited_approved_position" type="text" class = "edm-input" id="edited_approved_position" value="{{old('edited_approved_position')}}" name="edited_approved_position"/>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Edit Bank" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
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
            
        });

        $('#profile_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var edited_profile_id = button.data('profile_id');
            var edited_client = button.data('client');
            var edited_payment_detail = button.data('payment_detail');
            var edited_auto_generate = button.data('auto_generate');
            var edited_approved_name = button.data('approved_name');
            var edited_approved_position = button.data('approved_position');

            var modal = $(this);

            if (edited_auto_generate == 1){
                modal.find('.modal-body #edited_auto_generate_yes').prop('checked',true);
            }else{
                modal.find('.modal-body #edited_auto_generate_no').prop('checked',true);
            }

            modal.find('.modal-body #edited_profile_id').val(edited_profile_id);
            modal.find('.modal-body #edited_client').val(edited_client);
            modal.find('.modal-body #edited_payment_detail').val(edited_payment_detail);
            modal.find('.modal-body #edited_auto_generate').val(edited_auto_generate);
            modal.find('.modal-body #edited_approved_name').val(edited_approved_name);
            modal.find('.modal-body #edited_approved_position').val(edited_approved_position);
        })
    </script>