    <div class="modal fade" id="setting_edit" tabindex="-1" role="dialog" aria-labelledby="setting_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Invoice Setting</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('setting.update') }}">
                @csrf
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Term of Payment</td>
                            <td class = "edm-td-right">
                                <input type="hidden" id="edited_setting_id"  value="{{old('edited_setting_id')}}" name="edited_setting_id" required/>
                                <input type="number" class = "edm-input" id="edited_term_of_payment"  value="{{old('edited_term_of_payment')}}" name="edited_term_of_payment" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Last Invoice Number</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="edited_last_invoice_number" value="{{old('edited_last_invoice_number')}}" name="edited_last_invoice_number" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Invoice Number Prefix</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="edited_invoice_number_prefix" value="{{old('edited_invoice_number_prefix')}}" name="edited_invoice_number_prefix" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Authorize Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="edited_authorized_name"  value="{{old('edited_authorized_name')}}" name="edited_authorized_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Authorize Position</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="edited_authorized_position" name="edited_authorized_position" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Note Message</td>
                            <td class = "edm-td-right">
                                <textarea class = "edm-input" id="edited_note_message" name="edited_note_message" style="height:90px;" >{{old('edited_note_message')}}</textarea>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Edit setting" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
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

        $('#setting_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var edited_setting_id = button.data('edited_setting_id');
            var edited_term_of_payment = button.data('edited_term_of_payment');
            var edited_last_invoice_number = button.data('edited_last_invoice_number');
            var edited_invoice_number_prefix = button.data('edited_invoice_number_prefix');
            var edited_authorized_name = button.data('edited_authorized_name');

            var edited_authorized_position = button.data('edited_authorized_position');
            var edited_note_message = button.data('edited_note_message');
            console.log(edited_invoice_number_prefix);
            var modal = $(this);

            modal.find('.modal-body #edited_setting_id').val(edited_setting_id);
            modal.find('.modal-body #edited_term_of_payment').val(edited_term_of_payment);
            modal.find('.modal-body #edited_last_invoice_number').val(edited_last_invoice_number);
            modal.find('.modal-body #edited_invoice_number_prefix').val(edited_invoice_number_prefix);
            modal.find('.modal-body #edited_authorized_name').val(edited_authorized_name);
            modal.find('.modal-body #edited_authorized_position').val(edited_authorized_position);
            modal.find('.modal-body #edited_note_message').val(edited_note_message);
        })
    </script>