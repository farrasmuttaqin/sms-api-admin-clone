    <div class="modal fade" id="cobrander_details" tabindex="-1" role="dialog" aria-labelledby="cobrander_details">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Cobrander Details</h3>
            </div>
            <div class="modal-body">
                <form>
                    <table class="cdm-table">
                        <input type='hidden' id="cobranderID" />

                        <tr>
                            <td class = "cdm-td-left">Cobrander Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="cobranderName" readonly="readonly" disabled />
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Agent Name</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="agentName" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Operator Name</td>
                            <td class = "cdm-td-right">
                                <textarea class = "uk-width-1-1 cdm-input" id="operator" readonly="readonly" disabled ></textarea>
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
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="cobrander_edit" tabindex="-1" role="dialog" aria-labelledby="cobrander_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Cobrander Details</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('cobrander.update') }}">
                @csrf
                    <input type="hidden" id = "cobranderEditID" name = "edited_cobrander_id" />
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Cobrander Name</td>
                            <td class = "edm-td-right-select">
                                <select id = "cobranderNameEdit" class="uk-width-1-1 edm-select" name="edited_cobrander_name" required>
                                    <option value="">@lang('app.no_user')</option>
                                    @foreach ($all_users as $user)
                                        <option value="{{$user->USER_NAME}}" {{ (old("edited_cobrander_name") == $user->USER_NAME ? "selected":"") }}>{{$user->USER_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Agent Name</td>
                            <td class = "edm-td-right-select">
                                <select id = "agentNameEdit" class="uk-width-1-1 edm-select" name="edited_agent_id" required>
                                    <option value="" selected >@lang('app.no_agent')</option>
                                    @foreach ($all_agents as $agent)
                                        <option value="{{$agent->AGENT_ID}}">{{$agent->AGENT_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Operator</td>
                            <td class = "edm-td-right-select">
                                <select id = "operatorEdit" class="uk-width-1-1 edm-select" name="edited_operator_name[]" multiple="multiple" required>
                                    <option value="" selected >@lang('app.no_operator')</option>
                                    @foreach ($all_operators as $operator)
                                        <option value="{{$operator->OPERATOR_NAME}}">{{$operator->OPERATOR_NAME}}</option>
                                    @endforeach
                                </select>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete">
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
        $('#cobrander_details').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var cobranderID = button.data('cobrander_id');
            var cobranderName = button.data('cobrander_name');
            var agentName = button.data('agent_name');
            var operator = button.data('operator');
            
            var createdBy = button.data('created_by');
            var createdOn = button.data('created_on');
            var updatedBy = button.data('updated_by');
            var updatedOn = button.data('updated_on');

            var modal = $(this);

            modal.find('.modal-body #cobranderID').val(cobranderID);
            modal.find('.modal-body #cobranderName').val(cobranderName);
            modal.find('.modal-body #agentName').val(agentName);

            consV = operator.split(',');
            value = '';

            height = 0;

            for (o=0;o<consV.length;o++){
                value += '- '+consV[o]+' \n';
                height += 18;
            }
            
            modal.find('.modal-body #operator').val(value);
            modal.find('.modal-body #operator').height(height);

            modal.find('.modal-body #createdBy').val(createdBy);
            modal.find('.modal-body #createdOn').val(createdOn);
        })

        $('#cobrander_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var cobranderID = button.data('cobrander_id');
            var cobranderName = button.data('cobrander_name');

            var agentName = button.data('agent_name');
            var operator = button.data('operator');
            
            var modal = $(this);

            $('#cobranderNameEdit').append(`<option value="${cobranderName}"> 
                                       ${cobranderName} 
                                  </option>`); 

            modal.find('.modal-body #cobranderEditID').val(cobranderID);
            modal.find('.modal-body #cobranderNameEdit').val(cobranderName);
            modal.find('.modal-body #agentNameEdit').val(agentName);

            var arrayUser = operator.split(',');

            modal.find('.modal-body #operatorEdit').val(arrayUser).trigger('change');

            $('#operatorEdit').select2({
                placeholder: "@lang('app.no_operator')",
                allowClear: true
            });
        })

        $('#cobrander_edit').on('hidden.bs.modal', function () {
            $("select[name=edited_cobrander_name] option:last").remove();
        });
    </script>