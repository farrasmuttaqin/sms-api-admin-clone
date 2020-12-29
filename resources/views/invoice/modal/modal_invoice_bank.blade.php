    <div class="modal fade" id="bank_create" tabindex="-1" role="dialog" aria-labelledby="bank_create">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Add Bank Account</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('bank.create') }}">
                @csrf
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Bank Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="added_bank_name"  value="{{old('added_bank_name')}}" name="added_bank_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Account Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="added_account_name" value="{{old('added_account_name')}}" name="added_account_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Account Number</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="added_account_number" value="{{old('added_account_number')}}" name="added_account_number" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Address</td>
                            <td class = "edm-td-right">
                                <textarea class = "edm-input" id="added_address" value="{{old('added_address')}}" name="added_address" style="height:90px;" >{{old('edited_agent_description')}}</textarea>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Add Bank" /><i class="pencil-edit-delete las la-plus-square"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="bank_edit" tabindex="-1" role="dialog" aria-labelledby="bank_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Bank Account</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('bank.update') }}">
                @csrf
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Bank Name</td>
                            <td class = "edm-td-right">
                                <input type="hidden" id="edited_bank_id"  value="{{old('edited_bank_id')}}" name="edited_bank_id" required/>
                                <input class = "edm-input" id="edited_bank_name"  value="{{old('edited_bank_name')}}" name="edited_bank_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Account Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="edited_account_name" value="{{old('edited_account_name')}}" name="edited_account_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Account Number</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="edited_account_number" value="{{old('edited_account_number')}}" name="edited_account_number" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Address</td>
                            <td class = "edm-td-right">
                                <textarea class = "edm-input" id="edited_address" value="{{old('edited_address')}}" name="edited_address" style="height:90px;" >{{old('edited_agent_description')}}</textarea>
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

        $('#bank_create').on('show.bs.modal', function(event){
            
        })

        $('#bank_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var edited_bank_id = button.data('bank_id');
            var edited_bank_name = button.data('bank_name');
            var edited_account_name = button.data('account_name');
            var edited_account_number = button.data('account_number');
            var edited_address = button.data('address');

            var modal = $(this);

            modal.find('.modal-body #edited_bank_id').val(edited_bank_id);
            modal.find('.modal-body #edited_bank_name').val(edited_bank_name);
            modal.find('.modal-body #edited_account_name').val(edited_account_name);
            modal.find('.modal-body #edited_account_number').val(edited_account_number);
            modal.find('.modal-body #edited_address').val(edited_address);
        })
    </script>