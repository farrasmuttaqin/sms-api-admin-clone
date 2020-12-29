
    <div class="modal fade" id="virtual_edit" tabindex="-1" role="dialog" aria-labelledby="virtual_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Agent Details</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('vn.update') }}">
                @csrf
                    <input type="hidden" id = "edited_user_id" name = "USER_ID"/>
                    <input type="hidden" id = "virtualEditID" name = "edited_virtual_id" />
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Destination</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="virtualDestinationEdit"  value="{{old('edited_virtual_destination')}}" name="edited_virtual_destination" maxlength="16" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Use Foward URL</td>
                            <td class = "edm-td-right">
                                <input type="radio" id="virtualForwardEditYes" name="edited_forward" value="1" required/> <label id="yes" for="Yes" class="radio-label">Yes</label> &nbsp &nbsp <input type="radio" id="virtualForwardEditNo" name="edited_forward" value="0" required/> <label id="no" for="No" class="radio-label">No</label>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">URL</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="virtualURLEdit"  value="{{old('edited_URL')}}" name="edited_URL" maxlength="255" required/>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td data-dismiss="modal" width='80%' class = "td-edit-delete">
                                <span class = "span2-edit-delete" style="cursor:pointer;padding-right:20px;" >Close</span><i class="pencil-edit-delete las la-window-close"></i> 
                                
                            </td>
                            <td width='20%' class = "td-edit-delete">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;margin-right: 25px;" value="Save" /><i class="close-edit-delete las la-pencil-alt" style="padding-left:10px;"></i> 
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

            $('#yes').on('click',function(event){
                $('#virtualForwardEditYes').prop('checked',true);
                $('#virtualURLEdit').prop('disabled',false);
            });

            $('#no').on('click',function(event){
                $('#virtualForwardEditNo').prop('checked',true);
                $('#virtualURLEdit').prop('disabled',true);
                $('#virtualURLEdit').val('');
            });
            
            $('#virtualForwardEditYes').on('click',function(event){
                $('#virtualURLEdit').prop('disabled',false);
            });

            $('#virtualForwardEditNo').on('click',function(event){
                $('#virtualURLEdit').prop('disabled',true);
                $('#virtualURLEdit').val('');
            });
        });

        $('#virtual_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var vID = button.data('virtual_edit_id');
            var uID = button.data('user_edit_id');
            var destination = button.data('destination');
            var forward = button.data('forward');
            var url = button.data('url');

            var modal = $(this);

            modal.find('.modal-body #virtualEditID').val(vID);
            modal.find('.modal-body #edited_user_id').val(uID);
            modal.find('.modal-body #virtualDestinationEdit').val(destination);
            modal.find('.modal-body #virtualURLEdit').val(url);

            if (forward == 1){
                modal.find('.modal-body #virtualForwardEditYes').prop('checked',true);
            }else{
                modal.find('.modal-body #virtualForwardEditNo').prop('checked',true);
                $('#virtualURLEdit').prop('disabled',true);
                $('#virtualURLEdit').val('');
            }

        })

        
    </script>