    <div class="modal fade" id="tiering_group_edit" tabindex="-1" role="dialog" aria-labelledby="tiering_group_edit">
        <form action="{{route('tiering.group.update')}}" method="POST">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                        <h3 class="modal-title">Edit Tiering Group</h3>
                    </div>
                    
                    <br>

                    <div class="modal-body">
                        
                            <table class = "bo-table">
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold" >Name</span> 
                                    </td>
                                    <td width='75%'>
                                        <input type='hidden' class = "uk-width-1-1 bo-cn-input-1-1" id="tg_id" name="edited_tg_id" />
                                        <input class = "uk-width-1-1 bo-cn-input-1-1" id="tg_name" value = "{{old('edited_tiering_group_name')}}" name="edited_tiering_group_name" required/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold" >Description</span> 
                                    </td>
                                    <td width='75%'>
                                        <textarea type="text" id="tg_description" name="edited_description" class="uk-width-1-1" style= "background-color:white;border:1px solid rgb(199,199,199);color:black;height:90px;">{{old('edited_description')}}</textarea>    
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold" >Users</span> 
                                    </td>
                                    <td width='75%'>
                                        <select id="edited_tiering_group_users" name="edited_users[]" multiple="multiple" class="uk-width-1-1" required>
                                            @foreach ($all_tiering_group_users as $user)
                                                <option value="{{$user->USER_ID}}">{{$user->USER_NAME}}</option>
                                            @endforeach
                                        </select>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                    </td>
                                    <td width='75%' style="color:red;">
                                        * Select users which accumulate the same tiering
                                        <br>
                                        ** You can only select users whose implement the same billing profile
                                    </td> 
                                </tr>
                            </table>
                    </div>
                    <div class="modal-footer">
                        <button style="cursor:pointer;" data-dismiss="modal" type="button" class = "bo-cancel">Cancel</button>
                        <button style="cursor:pointer;" type="submit" class = "bo-save">Save</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>

        $('#tiering_group_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var tg_id = button.data('tg_id'),
                tg_name = button.data('tg_name'),
                tg_description = button.data('tg_description');

            var modal = $(this);

            modal.find('.modal-body #tg_id').val(tg_id);
            modal.find('.modal-body #tg_name').val(tg_name);
            modal.find('.modal-body #tg_description').val(tg_description);

            /**
             * Load Users
             */


            var usersSelect = $('#edited_tiering_group_users');
            var arrayUser = new Array();

            usersSelect.select2({
                placeholder: "@lang('app.no_user')",
                allowClear: true
            });

            usersSelect.val('');

            $.ajax({
                type: 'GET',
                url: "{{url('find_users_tg')}}/"+tg_id
            }).then(function (data) {

                for(i=0; i< data.length; i++){
                    arrayUser[i] = data[i].USER_ID;
                }
                usersSelect.val(arrayUser).trigger('change');

            });

        })
    </script>