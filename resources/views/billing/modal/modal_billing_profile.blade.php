    <div class="modal fade" id="billing_profile_edit" tabindex="-1" role="dialog" aria-labelledby="billing_profile_edit">
        <form id="billing-profile-form-modal" action="{{route('billing.update')}}" method="POST">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                        <h3 class="modal-title">Edit Billing Profile</h3>
                    </div>
                    
                    <br>

                    <div class="modal-body">
                        
                        <table class = "bo-table">
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-ro-span-bold" >Name</span> 
                                </td>
                                <td width='75%'>
                                    <input type='hidden' class = "uk-width-1-1 bo-cn-input-1-1" id="billing_id" name="edited_billing_id" />

                                    <input class = "uk-width-1-1 bo-cn-input-1-1" id="billing_name" value = "{{old('edited_name')}}" name="edited_name" />
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-ro-span-bold" >Description</span> 
                                </td>
                                <td width='75%'>
                                    <textarea type="text" id="description" name="edited_description" class="uk-width-1-1" style= "background-color:white;border:1px solid rgb(199,199,199);color:black;height:90px;" >{{old('edited_description')}}</textarea>    
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-ro-span-bold" >Type</span> 
                                </td>
                                <td width='75%'>
                                    <input class = "uk-width-1-1 bo-cn-input-1-1" id="type" name='edited_type' readonly />
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-ro-span-bold" >Users</span> 
                                </td>
                                <td width='75%'>
                                    <select id="edited_users" name="edited_users[]" multiple="multiple" class="uk-width-1-1" required>
                                        @foreach ($all_users as $user)
                                            <option value="{{$user->USER_ID}}">{{$user->USER_NAME}}</option>
                                        @endforeach
                                    </select>
                                </td> 
                            </tr>
                            <tr class = "bo-tr">
                                <td width='25%' class = "bo-cn-td">
                                    <span class = "bo-ro-span-bold" >Settings</span> 
                                </td>
                                <td width='75%'>
                                    <div id="operator-container-modal" class="operator-container-modal" style="display:none;">
                                        <div style="max-height: 300px; margin-top: 10px; overflow-x: auto;">
                                            <table id="operator-table-modal" >
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>Operator Name</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button id="add-operator-field-modal" class="uk-button uk-float-left" style="color:black;font-weight:bold;margin-top:20px;">
                                            Add Field
                                        </button>
                                    </div>
                                    <div id="tiering-container-modal" class="tiering-container-modal" style="display:none;">
                                        <div style="max-height: 300px; margin-top: 10px; overflow-x: auto;">
                                            <table id="tiering-table-modal">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>From</th>
                                                        <th>Up To</th>
                                                        <th>Price</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button id="add-tiering-field-modal" class="uk-button uk-float-left" style="color:black;font-weight:bold;margin-top:20px;">
                                            Add Field
                                        </button>
                                    </div>
                                    <div id="tiering-operator-container-modal" class="tiering-operator-container-modal" style="display:none;">
                                        <div style="max-height: 300px; margin-top: 10px; overflow-x: auto;">
                                            <table id="tiering-operator-table-modal">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>From</th>
                                                        <th>Up To</th>
                                                        <th>OP Name</th>
                                                        <th>Price</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                        <button id="add-tiering-operator-field-modal" class="uk-button uk-float-left" style="color:black;font-weight:bold;margin-top:20px;">
                                            Add Field
                                        </button>
                                    </div>
                                </td> 
                            </tr>
                        </table>
                    </div>
                <div class="modal-footer">
                    <button data-dismiss="modal" style="cursor:pointer;" type="button" class = "bo-cancel">Cancel</button>
                    <button type="submit" style="cursor:pointer;" class = "bo-save">Save</button>
                </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var rowIndexTieringOperator = $('#tiering-operator-table-modal tbody tr').length;
        
        $(document)
        .on('click', '.add-tOperator-modal', function(e){

            newRowTieringOperator     =   
            '<tr style="height:30px;">'
                + '<td>'
                    +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tOperator-modal'>"
                + '</td>'
                + '<td style="padding-left:10px;">'
                    +'<input type="hidden" name="edit_tOperatorFR['+rowIndexTieringOperator+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" />'
                + '</td>'
                + '<td style="padding-left:10px;">'
                    +'<input type="hidden" name="edit_tOperatorUP['+rowIndexTieringOperator+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" />'
                + '</td>'
                + '<td style="padding-left:10px;">'
                    + '<select style="border-radius: 0px;width:120px;" name="edit_tOperatorOP['+rowIndexTieringOperator+'][operator]" required></select>'
                + '</td>'
                + '<td style="padding-left:10px;">'
                    +'<input type="number" name="edit_tOperatorPR['+rowIndexTieringOperator+'][price]" style="border-radius: 0px;width:50px;" maxlength="10" step=".01" required/>'
                + '</td>'
                + '<td>'
                    +"<img src='{{url('images/icon/circle-add.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='add-tOperator-modal'>"
                + '</td>'
            +'</tr>';
            
            $(newRowTieringOperator).insertAfter($(this).closest('tr'));

            var tOperatorOP =$('select[name^="edit_tOperatorOP"]'),
                tOperatorPR =$('input[name^="edit_tOperatorPR"]'),
                tOperatorFrom     =$('input[name^="edit_tOperatorFR"]'),
                tOperatorTo       =$('input[name^="edit_tOperatorUP"]'),
                tOperatorOPSelectProcess = $('select[name="edit_tOperatorOP['+rowIndexTieringOperator+'][operator]"]');
            
            /**
            * Process Select Operator
            */

            loadOperatorModal(tOperatorOPSelectProcess,CSRF_TOKEN);

            /**
            * Fixing tiering operator systematic name index
            */
            
            fixingTieringOperatorNameIndexFormModal(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo)
        })
        .on('change', '.check-max-tOperator-modal', function(e){

            if (this.checked){

                $(this).closest('tr').find('.trOperator-to-modal')
                    .prop('type','text');

                $(this).closest('tr').find('.trOperator-to-modal')
                    .val('MAX')
                    .trigger('input');

                $(this).closest('tr').find('.trOperator-to-modal')
                    .prop('readonly', true);

            }else{

                $(this).closest('tr').find('.trOperator-to-modal')
                    .prop('type','number');

                $(this).closest('tr').find('.trOperator-to-modal')
                    .val('')
                    .trigger('input');

                $(this).closest('tr').find('.trOperator-to-modal')
                    .prop('readonly', false);
            }
        })
        
        $('#billing_profile_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var billing_id = button.data('billing_id'),
                billing_name = button.data('billing_name'),
                description = button.data('description'),
                type = button.data('type');

            var modal = $(this);

            modal.find('.modal-body #billing_id').val(billing_id);
            modal.find('.modal-body #billing_name').val(billing_name);
            modal.find('.modal-body #description').val(description);
            modal.find('.modal-body #type').val(type);

            /**
             * Load Users
             */


            var usersSelect = $('#edited_users');
            var arrayUser = new Array();

            usersSelect.select2({
                placeholder: "@lang('app.no_user')",
                allowClear: true
            });

            usersSelect.val('');

            $.ajax({
                type: 'GET',
                url: "{{url('find_users')}}/"+billing_id
            }).then(function (data) {

                for(i=0; i< data.length; i++){
                    arrayUser[i] = data[i].USER_ID;
                }
                usersSelect.val(arrayUser).trigger('change');

            });

            /**
             * Setting
             */

            var tcon = $('#tiering-container-modal'),
                ocon = $('#operator-container-modal'),
                tocon = $('#tiering-operator-container-modal'),
                billingNameFirst = $('#billing_name').val();

            if (type == 'operator'){
                tcon.css('display','none');
                ocon.css('display','block');
                tocon.css('display','none');

                $.ajax({
                    type: 'GET',
                    url: "{{url('find_operator_settings')}}/"+billing_id
                }).then(function (data) {

                    $("#operator-table-modal tbody").empty();

                    for(i=0;i<data.length;i++)
                        {
                            if (i==0){
                                newRowOperator      =   
                                '<tr>'
                                    + '<td>'
                                            +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-operator-modal'>"
                                    + '</td>'
                                    + '<td style="padding-left:10px;">'
                                    +   '<select style="border-radius: 0px;width:100%;" name="editOperatorOP['+i+'][operator]" disabled required>'
                                        +'<option value = "DEFAULT" selected>DEFAULT</option>'
                                    +   '</select>'
                                    + '</td>'
                                    + '<td style="padding-left:10px;">'
                                    +   '<input type="number" value="'+data[i].PER_SMS_PRICE+'" name="editOperatorPR['+i+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required>'
                                    + '</td>'
                                +'</tr>';
                            }else{
                                newRowOperator      =   
                                '<tr>'
                                    + '<td>'
                                            +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-operator-modal'>"
                                    + '</td>'
                                    + '<td style="padding-left:10px;">'
                                    +   '<select style="border-radius: 0px;width:100%;" name="editOperatorOP['+i+'][operator]" required>'
                                    +   '</select>'
                                    + '</td>'
                                    + '<td style="padding-left:10px;">'
                                    +   '<input type="number" value="'+data[i].PER_SMS_PRICE+'" name="editOperatorPR['+i+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required>'
                                    + '</td>'
                                +'</tr>';
                            }

                            $('#operator-table-modal tbody').append(newRowOperator);

                            var operatorOPB = $('select[name="editOperatorOP['+i+'][operator]"]');

                            loadOperatorModal(operatorOPB,CSRF_TOKEN);

                            option = new Option(data[i].OP_ID, data[i].OP_ID, true, true);
                            operatorOPB.html(option);
                        }
                    })

                /**
                 * Method for add / delete operator field
                 */

                 var operatorOp = $('select[name ="editOperatorOP[0][operator]"]'),
                    addOperatorField = $('#add-operator-field-modal'),
                    rowIndexOperator = $('#operator-table-modal tbody tr').length,
                    newRowOperator = '';

                requiredSetting('operator');

                addOperatorField.unbind('click').click(function(e){
                    e.preventDefault();

                    newRowOperator      =   
                    '<tr>'
                        + '<td>'
                                +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-operator-modal'>"
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                        +   '<select style="border-radius: 0px;width:100%;" name="editOperatorOP['+rowIndexOperator+'][operator]" required>'
                        +   '</select>'
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                        +   '<input type="number" name="editOperatorPR['+rowIndexOperator+'][price]" style="border-radius: 0px;" maxlength="10" step=".01" required>'
                        + '</td>'
                    +'</tr>';

                    $('#operator-table-modal tbody').append(newRowOperator);

                    var operatorOp = $('select[name="editOperatorOP['+rowIndexOperator+'][operator]"]'),
                        operatorOP =$('select[name^="editOperatorOP"]'),
                        operatorPR =$('input[name^="editOperatorPR"]');

                    loadOperator(operatorOp,CSRF_TOKEN);

                    /**
                     * Fixing operator systematic name index
                     */

                    fixingOperatorNameIndexFormModal(operatorOP,operatorPR)

                    rowIndexOperator++;
                });
            }else if (type == 'tiering'){
                tcon.css('display','block');
                ocon.css('display','none');
                tocon.css('display','none');

                $.ajax({
                    type: 'GET',
                    url: "{{url('find_tiering_settings')}}/"+billing_id
                }).then(function (data) {

                    $("#tiering-table-modal tbody").empty();

                    for(i=0;i<data.length;i++)
                    {
                        if (data[i].SMS_COUNT_UP_TO == 9999999999){
                            data[i].SMS_COUNT_UP_TO = 'MAX';
                        }

                        if (data[i].SMS_COUNT_UP_TO == 'MAX'){
                            read = 'readonly'
                            type = 'type="text"'
                        }else{
                            read = ''
                            type = 'type="number"'
                        }

                        if (i == 0){
                            read_from = 'readonly'
                        }else{
                            read_from = ''
                        }

                        
                        newRowTiering      =   
                        '<tr style="height:30px;">'
                            + '<td>'
                                +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tiering-modal'>"
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="number" value="'+data[i].SMS_COUNT_FROM+'" class="tr-from-modal" name="editTieringFR['+i+'][tr]" style="border-radius: 0px;width:90px;" maxlength="10" '+read_from+' required/>'
                            + '</td>'
                            + '<td style="padding-left:10px;width:100px;">'
                                +'<input '+type+' class="tr-to-modal" value="'+data[i].SMS_COUNT_UP_TO+'" name="editTieringUP['+i+'][tr]" style="border-radius: 0px;width:90px;" maxlength="10" '+read+' required/>'
                            + '</td>'
                            + '<td style="padding-left:10px;">'
                                +'<input type="number" value="'+data[i].PER_SMS_PRICE+'" name="editTieringPR['+i+'][price]" style="border-radius: 0px;width:75px;" maxlength="10" step=".01" required/>'
                            + '</td>'
                        +'</tr>';

                        $('#tiering-table-modal tbody').append(newRowTiering);
                    }
                });

                /**
                 * Method for add / delete tiering field
                 */

                var addTieringField = $('#add-tiering-field-modal'),
                    newRowTiering = '',
                    rowIndexTiering     = $('#tiering-table-modal tbody tr').length;

                requiredSetting('tiering');

                addTieringField.unbind('click').click(function(e){
                    e.preventDefault();

                    index = rowIndexTiering - 1;
                    
                    uptoVal = parseInt($('input[name ="editTieringUP['+index+'][tr]"]').val());
                    
                    /* fill the next tiering's from value based on current tiering's up to value */
                    if(uptoVal > 0){
                        up = uptoVal + 1;
                    } else {
                        up = '';
                    }
                    
                    indexNow = $('#tiering-table-modal tbody tr').length - 1;

                    $('input[name ="editTieringUP['+indexNow+'][tr]"]').val('');
                    $('input[name ="editTieringUP['+indexNow+'][tr]"]').attr("type", 'number'); 
                    $('input[name ="editTieringUP['+indexNow+'][tr]"]').attr("readonly", false); 

                    newRowTiering      =   
                    '<tr style="height:30px;">'
                        + '<td>'
                            +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tiering-modal'>"
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                            +'<input type="number" value="'+up+'" class="tr-from-modal" name="editTieringFR['+rowIndexTiering+'][tr]" style="border-radius: 0px;width:90px;" maxlength="10" required/>'
                        + '</td>'
                        + '<td style="padding-left:10px;width:100px;">'
                            +'<input type="text" value="MAX" class="tr-to-modal" name="editTieringUP['+rowIndexTiering+'][tr]" style="border-radius: 0px;width:90px;" maxlength="10" readonly required/>'
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                            +'<input type="number" name="editTieringPR['+rowIndexTiering+'][price]" style="border-radius: 0px;width:75px;" maxlength="10" step=".01" required/>'
                        + '</td>'
                    +'</tr>';

                    $('#tiering-table-modal tbody').append(newRowTiering);

                    var tieringFrom     =$('input[name^="editTieringFR"]'),
                        tieringTo       =$('input[name^="editTieringUP"]'),
                        tieringPrice    =$('input[name^="editTieringPR"]');

                    /**
                    * Fixing tiering systematic name index
                    */

                    fixingTieringNameIndexFormModal(tieringFrom,tieringTo,tieringPrice)

                    rowIndexTiering++;
                })
            }else if (type == 'tiering-operator'){
                tcon.css('display','none');
                ocon.css('display','none');
                tocon.css('display','block');

                var temp_BILLING_PROFILE_TIERING_OPERATOR_ID = 0,
                    rowIndexTieringOperator     = 0;

                $.ajax({
                    type: 'GET',
                    url: "{{url('find_tiering_operator_settings')}}/"+billing_id
                }).then(function (data) {
                    
                    $("#tiering-operator-table-modal tbody").empty();

                    for(i=0;i<data.length;i++)
                    {
                        if (data[i].SMS_COUNT_UP_TO == 9999999999){
                            data[i].SMS_COUNT_UP_TO = 'MAX';
                        }
                        
                        if (data[i].SMS_COUNT_UP_TO == 'MAX'){
                            max = 'checked'
                            read = 'readonly'

                            type = 'type="text"'
                        }else{
                            max = ''
                            read = ''

                            type = 'type="number"'
                        }

                        if (i == 0){
                            read_from = 'readonly'
                        }else{
                            read_from = ''
                        }

                        

                        if (data[i].BILLING_PROFILE_TIERING_OPERATOR_ID != temp_BILLING_PROFILE_TIERING_OPERATOR_ID){
                            newRowTieringOperator     =   
                            '<tr style="height:30px;">'
                                + '<td>'
                                    +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tOperator-modal'>"
                                + '</td>'
                                + '<td style="padding-left:10px;">'
                                    +'<input type="number" class="trOperator-from-modal" value="'+data[i].SMS_COUNT_FROM+'" name="edit_tOperatorFR['+i+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" '+read_from+' required/>'
                                + '</td>'
                                + '<td style="padding-left:10px;width:60px;">'
                                    +'<input '+type+' class="trOperator-to-modal" value="'+data[i].SMS_COUNT_UP_TO+'" name="edit_tOperatorUP['+i+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" '+read+' required/>'
                                + '</td>'
                                + '<td style="padding-left:10px;">'
                                    + '<select style="border-radius: 0px;width:120px;" name="edit_tOperatorOP['+i+'][operator]" disabled required>'
                                        +'<option value = "DEFAULT" selected>DEFAULT</option>'
                                    + '</select>'
                                + '</td>'
                                + '<td style="padding-left:10px;">'
                                    +'<input type="number" value="'+data[i].PER_SMS_PRICE+'" name="edit_tOperatorPR['+i+'][price]" style="border-radius: 0px;width:50px;" maxlength="10" step=".01" required/>'
                                + '</td>'
                                + '<td>'
                                    +"<img src='{{url('images/icon/circle-add.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='add-tOperator-modal'>"
                                + '</td>'
                            +'</tr>';
                        }else{
                            newRowTieringOperator     =   
                            '<tr style="height:30px;">'
                                + '<td>'
                                    +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tOperator-modal'>"
                                + '</td>'
                                + '<td style="padding-left:10px;">'
                                    +'<input type="hidden" name="edit_tOperatorFR['+i+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" />'
                                + '</td>'
                                + '<td style="padding-left:10px;width:60px;">'
                                    +'<input type="hidden" name="edit_tOperatorUP['+i+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" />'
                                + '</td>'
                                + '<td style="padding-left:10px;">'
                                    + '<select style="border-radius: 0px;width:120px;" name="edit_tOperatorOP['+i+'][operator]" required></select>'
                                + '</td>'
                                + '<td style="padding-left:10px;">'
                                    +'<input type="number" value="'+data[i].PER_SMS_PRICE+'" name="edit_tOperatorPR['+i+'][price]" style="border-radius: 0px;width:50px;" maxlength="10" step=".01" required/>'
                                + '</td>'
                                + '<td>'
                                    +"<img src='{{url('images/icon/circle-add.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='add-tOperator-modal'>"
                                + '</td>'
                            +'</tr>';
                        }
                        
                        temp_BILLING_PROFILE_TIERING_OPERATOR_ID = data[i].BILLING_PROFILE_TIERING_OPERATOR_ID;
                        $('#tiering-operator-table-modal tbody').append(newRowTieringOperator);

                        var operatorOPB = $('select[name="edit_tOperatorOP['+i+'][operator]"]');

                        loadOperatorModal(operatorOPB,CSRF_TOKEN);

                        option = new Option(data[i].OP_ID, data[i].OP_ID, true, true);
                        operatorOPB.html(option);

                        rowIndexTieringOperator++;
                    }
                });

                /**
                 * Method for add / delete tiering field
                 */

                var addTieringOperatorField = $('#add-tiering-operator-field-modal'),
                    newRowTieringOperator = '';
                    

                requiredSetting('tiering-operator');

                addTieringOperatorField.unbind('click').click(function(e){
                    e.preventDefault();

                    index = rowIndexTieringOperator - 1;

                    uptoVal = parseInt($('input[name ="edit_tOperatorUP['+index+'][tr]"]').val());

                    /* fill the next tiering's from value based on current tiering's up to value */
                    if(uptoVal > 0){
                        up = uptoVal + 1;
                    } else {
                        up = '';
                    }
                    
                    newRowTieringOperator      =   
                    '<tr style="height:30px;">'
                        + '<td>'
                            +"<img src='{{url('images/icon/circle-red.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='remove-tOperator-modal'>"
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                            +'<input type="number" value="'+up+'" class="trOperator-from-modal" name="edit_tOperatorFR['+rowIndexTieringOperator+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" required/>'
                        + '</td>'
                        + '<td style="padding-left:10px;width:60px;">'
                            +'<input type="text" value="MAX" class="trOperator-to-modal" name="edit_tOperatorUP['+rowIndexTieringOperator+'][tr]" style="border-radius: 0px;width:45px;" maxlength="10" required/>'
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                            + '<select style="border-radius: 0px;width:120px;" name="edit_tOperatorOP['+rowIndexTieringOperator+'][operator]" disabled required>'
                                +'<option value = "DEFAULT" selected>DEFAULT</option>'
                            + '</select>'
                        + '</td>'
                        + '<td style="padding-left:10px;">'
                            +'<input type="number" name="edit_tOperatorPR['+rowIndexTieringOperator+'][price]" style="border-radius: 0px;width:50px;" maxlength="10" step=".01" required/>'
                        + '</td>'
                        + '<td>'
                            +"<img src='{{url('images/icon/circle-add.png')}}' alt='Remove' style='cursor:pointer;' width='13px' class='add-tOperator-modal'>"
                        + '</td>'
                    +'</tr>';

                    $('#tiering-operator-table-modal tbody').append(newRowTieringOperator);

                    var tOperatorOP =$('select[name^="edit_tOperatorOP"]'),
                        tOperatorPR =$('input[name^="edit_tOperatorPR"]'),
                        tOperatorFrom     =$('input[name^="edit_tOperatorFR"]'),
                        tOperatorTo       =$('input[name^="edit_tOperatorUP"]'),
                        tOperatorOPSelectProcess = $('select[name="edit_tOperatorOP['+rowIndexTieringOperator+'][operator]"]');

                    /**
                     * Process Select Operator
                    */

                    loadOperatorModal(tOperatorOPSelectProcess,CSRF_TOKEN);

                    /**
                    * Fixing tiering systematic name index
                    */

                    fixingTieringOperatorNameIndexFormModal(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo)

                    indexNow = $('#tiering-operator-table-modal tbody tr').length - 1;

                    if ($('input[name ="edit_tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').attr('type') == 'text'){
                        $('input[name ="edit_tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').val('');
                        $('input[name ="edit_tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').attr("type", 'number'); 
                        $('input[name ="edit_tOperatorUP['+indexNow+'][tr]"]').closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').attr("readonly", false); 
                    }

                    rowIndexTieringOperator++;
                })
            }

            
            
            $(document)
                .on('click', '.remove-operator-modal', function(){
                    var length = $('#operator-table-modal').find('tbody tr').length,
                        parent = $(this).parent('td').parent('tr');

                    if (length > 1 && $(this).parent('td').parent('tr') && parent.is(':not(:first-child)')){
                        parent.remove();
                    }

                    var operatorOP =$('select[name^="editOperatorOP"]'),
                        operatorPR =$('input[name^="editOperatorPR"]');

                    /**
                     * Fixing operator systematic name index
                     */
                     
                    fixingOperatorNameIndexFormModal(operatorOP,operatorPR)
                })
                .on('click', '.remove-tiering-modal', function(){
                    var length = $('#tiering-table-modal').find('tbody tr').length,
                        parent = $(this).parent('td').parent('tr');

                    if (length > 1 && $(this).parent('td').parent('tr') && parent.is(':not(:first-child)')){


                        if ($(this).parent('td').parent('tr').find('.tr-to-modal').val() == 'MAX'){
                            $(this).parents('tr').prev().find('.tr-to-modal').attr('type','text');
                            $(this).parents('tr').prev().find('.tr-to-modal').attr('readonly',true);
                            $(this).parents('tr').prev().find('.tr-to-modal').val('MAX');
                        }

                        parent.remove();
                    }

                    var tieringFrom     =$('input[name^="editTieringFR[0][tr]"]'),
                        tieringFrom2     =$('input[name^="editTieringFR"]'),
                        tieringTo       =$('input[name^="editTieringUP"]'),
                        tieringPrice    =$('input[name^="editTieringPR"]');

                    /**
                    * Fixing tiering systematic name index
                    */

                    fixingTieringNameIndexFormModal(tieringFrom2,tieringTo,tieringPrice)
                })
                .on('click', '.remove-tOperator-modal', function(){
                    var length = $('#tiering-operator-table-modal').find('tbody tr').length,
                        parent = $(this).parent('td').parent('tr');

                    if (length > 1 && $(this).parent('td').parent('tr') && parent.is(':not(:first-child)')){

                        if ($(this).parent('td').parent('tr').find('.trOperator-to-modal').val() == 'MAX'){
                            $(this).closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').attr('type','text');
                            $(this).closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').attr('readonly',true);
                            $(this).closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal').val('MAX');
                        }


                        parent.remove();
                    }

                    var tOperatorOP =$('select[name^="edit_tOperatorOP"]'),
                        tOperatorPR =$('input[name^="edit_tOperatorPR"]'),
                        tOperatorFrom     =$('input[name^="edit_tOperatorFR"]'),
                        tOperatorTo       =$('input[name^="edit_tOperatorUP"]');

                    /**
                    * Fixing tiering operator systematic name index
                    */
                    fixingTieringOperatorNameIndexFormModal(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo)
                })
                .on('keyup', '.tr-to-modal', function(e){
                    var uptoVal = parseInt($(this).val());

                    /* fill the next tiering's from value based on current tiering's up to value */
                    if(uptoVal > 0){
                        $(this).parents('tr').next().find('.tr-from-modal')
                            .val(uptoVal + 1)
                            .trigger('input');
                    } else {
                        $(this).parents('tr').next().find('.tr-from-modal')
                            .val('');
                        
                    }
                })
                .on('keyup', '.trOperator-to-modal', function(e){
                    var uptoVal = parseInt($(this).val());

                    if(uptoVal > 0){
                        $(this).closest('tr').nextAll(':has(.trOperator-from-modal):first').find('.trOperator-from-modal')
                            .val(uptoVal + 1)
                            .trigger('input');
                    } else {
                        $(this).closest('tr').nextAll(':has(.trOperator-from-modal):first').find('.trOperator-from-modal')
                            .val('');
                        
                    }
                })
                .on('keyup', '.tr-from-modal', function(e){

                    var fromVal = parseInt($(this).val());

                    /* fill the previous tiering's up to value based on current tiering's from value */
                    if(fromVal > 0){
                        $(this).parents('tr').prev().find('.tr-to-modal')
                               .val(fromVal - 1)
                               .trigger('input');
                    } else {
                        $(this).parents('tr').prev().find('.tr-to-modal')
                               .val('');
                    }
                })
                .on('keyup', '.trOperator-from-modal', function(e){

                    var fromVal = parseInt($(this).val());

                    /* fill the previous tiering's up to value based on current tiering's from value */
                    if(fromVal > 0){
                        $(this).closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal')
                                .val(fromVal - 1)
                                .trigger('input');
                    } else {
                        $(this).closest('tr').prevAll(':has(.trOperator-to-modal):first').find('.trOperator-to-modal')
                                .val('');
                    }
                })
                

            $('#billing-profile-form-modal').submit(function(e){
                e.preventDefault();

                /**
                 * Validate From & Up to value in Tiering
                 */

                var toValidate = $(".validateTo");
                toValidate.each(function(i){
                    $(this).remove();
                });

                var tieringFrom = $('input[name^="editTieringFR"]'),
                    tieringTo = $('input[name^="editTieringUP"]'),
                    nameTo,
                    charAtFrom,
                    nameFromLength,
                    validation=0;
                

                tieringFrom.each(function (i) {

                    nameFrom = $(this).attr("name");

                    nameFromLength = nameFrom.length;

                    if (nameFromLength == 20){
                        charAtFrom = nameFrom.charAt(14);
                    }

                    if (nameFromLength == 21){
                        charAtFrom = nameFrom.charAt(14)+nameFrom.charAt(15);
                    }
                    
                    nameTo = $('input[name ="editTieringUP['+charAtFrom+'][tr]"]').val();
                    
                    
                    if (!($(this).val() == 0 || nameTo == 0 || nameTo == 'MAX')){
                        if (parseInt($(this).val()) >= parseInt(nameTo)){
                            validateTo = "<span class='validateTo' style='color:red;font-weight:bold;'> &nbsp * Up to must bigger than from to!</span>";
                            $('input[name ="editTieringUP['+charAtFrom+'][tr]"]').after(validateTo);
                            validation=1;
                            return false;
                        }
                    }
                });


                /**
                 * Validate From & Up to value in Tiering - Operator
                 */

                var tOperatorFrom = $('input[name^="edit_tOperatorFR"]'),
                    tOperatorUP = $('input[name^="edit_tOperatorUP"]'),
                    nameTo,
                    charAtFrom,
                    nameFromLength,
                    validationTO=0;

                tOperatorFrom.each(function (i) {

                    nameFrom = $(this).attr("name");

                    nameFromLength = nameFrom.length;

                    if (nameFromLength == 23){
                        charAtFrom = nameFrom.charAt(17);
                    }

                    if (nameFromLength == 24){
                        charAtFrom = nameFrom.charAt(17)+nameFrom.charAt(18);
                    }

                    nameTo = $('input[name ="edit_tOperatorUP['+charAtFrom+'][tr]"]').val();

                    if (!($(this).val() == 0 || nameTo == 0)){
                        
                        if (parseInt($(this).val()) >= parseInt(nameTo)){
                            validateTo = "<span class='validateTo' style='color:red;font-weight:bold;'> &nbsp * Up to must bigger than from to!</span>";
                            $('input[name ="edit_tOperatorUP['+charAtFrom+'][tr]"]').after(validateTo);
                            validationTO=1;
                            return false;
                        }
                    }
                });

                /** 
                 * Validation for no duplicate name in billing
                 */

                var $form = $('#billing-profile-form-modal');
                var billingName = $('#billing_name').val();

                if (validation == 0 && validationTO == 0){
                    if (billingNameFirst == billingName){
                        $form.attr('post', "{{ route('billing.update') }}").off('submit').submit();
                    }else {
                        $.ajax({
                            url: "{{url('find_billing_name')}}/"+billingName,         
                            type: "GET",
                            success: function (data) {
                                $form.attr('post', "{{ route('billing.update') }}").off('submit').submit();
                            },
                            error: function() {
                                alert('Billing name already exist!');
                            }
                        });
                    }
                }
            });
        })

        function loadOperatorModal(operatorOp,CSRF_TOKEN){
            operatorOp.select2({
                ajax: { 
                    url: "{{route('operator.all')}}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        
                        if (params.term == undefined){
                            params.term = ''
                        }

                        return {
                            _token: CSRF_TOKEN,
                            search: params.term
                        };
                    },
                    processResults: function (data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }

            });
        }

        function fixingOperatorNameIndexFormModal(operatorOP,operatorPR){
            operatorOP.each(function (i) {
                $(this).attr('name','editOperatorOP['+i+'][operator]');
            });

            operatorPR.each(function (i) {
                $(this).attr('name','editOperatorPR['+i+'][price]');
            });
        }

        function fixingTieringNameIndexFormModal(tieringFrom,tieringTo,tieringPrice){
            tieringFrom.each(function (i) {
                $(this).attr('name','editTieringFR['+i+'][tr]');
            });

            tieringTo.each(function (i) {
                $(this).attr('name','editTieringUP['+i+'][tr]');
            });

            tieringPrice.each(function (i) {
                $(this).attr('name','editTieringPR['+i+'][price]');
            });
        }

        function fixingTieringOperatorNameIndexFormModal(tOperatorOP,tOperatorPR, tOperatorFrom, tOperatorTo){
            tOperatorFrom.each(function (i) {
                $(this).attr('name','edit_tOperatorFR['+i+'][tr]');
            });

            tOperatorTo.each(function (i) {
                $(this).attr('name','edit_tOperatorUP['+i+'][tr]');
            });

            tOperatorOP.each(function (i) {
                $(this).attr('name','edit_tOperatorOP['+i+'][operator]');
            });

            tOperatorPR.each(function (i) {
                $(this).attr('name','edit_tOperatorPR['+i+'][price]');
            });
        }

        function requiredSettingModal(type){

            var operatorOP =$('select[name^="editOperatorOP"]'),
                operatorPR =$('input[name^="editOperatorPR"]'),
                tieringFrom     =$('input[name^="editTieringFR"]'),
                tieringTo       =$('input[name^="editTieringUP"]'),
                tieringPrice    =$('input[name^="editTieringPR"]'),

                tOperatorFrom   =$('input[name^="tOperatorFR"]'),
                tOperatorTo     =$('input[name^="tOperatorUP"]'),
                tOperatorOP     =$('select[name^="tOperatorOP"]'),
                tOperatorPR     =$('input[name^="tOperatorPR"]'),

                o,t,to;
            
            if (type == 'operator'){
                o = true;
                t = false;
                to = false;
            }else if (type == 'tiering'){
                o = false;
                t = true;
                to = false;
            }else if (type == 'tiering-operator'){
                o = false;
                t = false;
                to = true;
            }
            
            operatorOP.each(function (i) {
                $(this).prop('required',o);
            });

            operatorPR.each(function (i) {
                $(this).prop('required',o);
            });

            tieringFrom.each(function (i) {
                $(this).prop('required',t);
            });

            tieringTo.each(function (i) {
                $(this).prop('required',t);
            });

            tieringPrice.each(function (i) {
                $(this).prop('required',t);
            });

            tOperatorFrom.each(function (i) {
                $(this).prop('required',to);
            });

            tOperatorTo.each(function (i) {
                $(this).prop('required',to);
            });

            tOperatorOP.each(function (i) {
                $(this).prop('required',to);
            });

            tOperatorPR.each(function (i) {
                $(this).prop('required',to);
            });
        }
    </script>