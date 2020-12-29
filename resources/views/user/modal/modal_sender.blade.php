
    <div class="modal fade" id="sender_edit" tabindex="-1" role="dialog" aria-labelledby="sender_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Agent Details</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('sender.update') }}">
                @csrf
                    <input type="hidden" id = "editedUserID" name = "edited_user_id"/>
                    <input type="hidden" id = "editedSenderID" name = "edited_sender_id" />
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Sender Name</td>
                            <td class = "edm-td-right">
                                <input type='text' class = "edm-input" id="editedSenderName"  value="{{old('edited_sender_name')}}" name="edited_sender_name" maxlength="20" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Activate</td>
                            <td class = "edm-td-right">
                                <input type="checkbox" id="editedSenderEnabled" value="1" name="edited_sender_enabled" class="uk-width-1-1" />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Cobrander ID</td>
                            <td class = "edm-td-right-select">
                                <select id = "editedCobranderID" class="uk-width-1-1 edm-select" name="edited_cobrander_id" required>
                                    <option value="" selected >@lang('app.no_cobrander')</option>
                                    @foreach ($all_cobranders as $cobrander)
                                        <option value="{{$cobrander->COBRANDER_NAME}}" {{ (old("edited_cobrander_id") == $cobrander->COBRANDER_NAME ? "selected":"") }}>{{$cobrander->COBRANDER_NAME}}</option>
                                    @endforeach
                                </select>
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

        $('#sender_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var s_id = button.data('sender_id');
            var s_enabled = button.data('sender_enabled');
            var s_user_id = button.data('user_id');
            var s_name = button.data('sender_name');
            var s_cobrander_id = button.data('cobrander_id');
            
            var modal = $(this);

            modal.find('.modal-body #editedSenderID').val(s_id);
            
            modal.find('.modal-body #editedUserID').val(s_user_id);
            modal.find('.modal-body #editedSenderName').val(s_name);
            modal.find('.modal-body #editedCobranderID').val(s_cobrander_id);

            $('#editedCobranderID').select2();

            if (s_enabled == 1){
                $('#editedSenderEnabled').prop('checked',true);
            }

        })

        
    </script>