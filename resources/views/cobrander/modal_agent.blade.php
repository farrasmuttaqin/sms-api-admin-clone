    <div class="modal fade" id="agent_details" tabindex="-1" role="dialog" aria-labelledby="agent_details">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Agent Details</h3>
            </div>
            <div class="modal-body">
                <form>
                    <table class="cdm-table">
                        <input type='hidden' id="agentID" />

                        <tr>
                            <td class = "cdm-td-left">Agent Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="agentName" readonly="readonly" disabled />
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Agent Queue Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="agentQueueName" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Agent Description</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="agentDescription" readonly="readonly" disabled />
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
                                <input class = "cdm-input" id="createdOn" readonly="readonly" disabled />
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
                                <input class = "cdm-input" id="updatedOn" readonly="readonly" disabled />
                            </td> 
                        </tr>
                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='80%' class = "td-edit-delete">
                                <span id="editID" 
                                        class = "span1-edit-delete" style="cursor:pointer;">Edit </span><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                            <td width='20%' class = "td-edit-delete">
                                <span  id = "deleteID_Detail" class = "span2-edit-delete" style="cursor:pointer;" >Delete</span><i class="close-edit-delete las la-window-close"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="agent_edit" tabindex="-1" role="dialog" aria-labelledby="agent_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Agent Details</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('agent.update') }}">
                @csrf
                    <input type="hidden" id = "agentEditID" name = "edited_agent_id" />
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Agent Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="agentNameEdit"  value="{{old('edited_agent_name')}}" name="edited_agent_name" maxlength="100" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Agent Queue Name</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="agentQueueNameEdit" value="{{old('edited_agent_queue_name')}}" name="edited_agent_queue_name" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Agent Description</td>
                            <td class = "edm-td-right">
                                <textarea class = "edm-input" id="agentDescriptionEdit" value="{{old('edited_agent_description')}}" name="edited_agent_description" style="height:90px;" required>{{old('edited_agent_description')}}</textarea>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='80%' class = "td-edit-delete">
                                
                            <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Save Edit" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                            <td width='20%' class = "td-edit-delete">
                                <span id = "deleteID_Edit" class = "span2-edit-delete" style="cursor:pointer;" >Delete </span><i class="close-edit-delete las la-window-close"></i> 
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
            $('#editID').on('click',function(event){
                var agentID = $('#agentID').val();
                $("#edit_agent"+agentID).click();
            });

            $('#deleteID_Edit').on('click',function(event){
                var agentID = $('#agentID').val();
                if (!agentID) agentID = $('#agentEditID').val();

                var con = confirm('are you sure delete this agent?');

                if (con == true) { 
                    location.href = "{{ url('agent') }}/"+agentID;
                } 
            });

            $('#deleteID_Detail').on('click',function(event){
                var agentID = $('#agentID').val();

                var con = confirm('are you sure delete this agent?');

                if (con == true) { 
                    location.href = "{{ url('agent') }}/"+agentID;
                } 
            });
        });

        $('#agent_details').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var agentID = button.data('agent_id');
            var agentName = button.data('agent_name');
            var agentQueueName = button.data('agent_queue_name');
            var agentDescription = button.data('agent_description');

            if (agentDescription.length > 32){
                agentDescription = agentDescription.substring(0,21)+'...'
            }
            
            var createdBy = button.data('created_by');
            var createdOn = button.data('created_on');
            var updatedBy = button.data('updated_by');
            var updatedOn = button.data('updated_on');

            var modal = $(this);

            modal.find('.modal-body #agentID').val(agentID);
            modal.find('.modal-body #agentName').val(agentName);
            modal.find('.modal-body #agentQueueName').val(agentQueueName);
            modal.find('.modal-body #agentDescription').val(agentDescription);

            modal.find('.modal-body #createdBy').val(createdBy);
            modal.find('.modal-body #createdOn').val(createdOn);
            modal.find('.modal-body #updatedBy').val(updatedBy);
            modal.find('.modal-body #updatedOn').val(updatedOn);
        })

        $('#agent_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var agentID = button.data('agent_id');
            var agentName = button.data('agent_name');
            var agentQueueName = button.data('agent_queue_name');
            var agentDescription = button.data('agent_description');

            var modal = $(this);

            modal.find('.modal-body #agentEditID').val(agentID);
            modal.find('.modal-body #agentNameEdit').val(agentName);
            modal.find('.modal-body #agentQueueNameEdit').val(agentQueueName);
            modal.find('.modal-body #agentDescriptionEdit').val(agentDescription);
        })
    </script>