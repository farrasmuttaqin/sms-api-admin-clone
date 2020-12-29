    <div class="modal fade" id="history_create" tabindex="-1" role="dialog" aria-labelledby="history_create">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Create Invoice History</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('create.invoice') }}" autocomplete="off">
                @csrf
                    <table class="table-edit-delete">
                        @if (empty($profile->INVOICE_PROFILE_ID))
                        <tr>
                            <td class = "edm-td-left">Invoice Profile</td>
                            <td class = "edm-td-right">
                                <select name="added_invoice_profile_id" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_profile')</option>
                                    @foreach ($all_invoice_profiles as $profile)
                                        <option value="{{$profile->INVOICE_PROFILE_ID}}">{{$profile->COMPANY_NAME}}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td class = "edm-td-left">Invoice Number</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="added_invoice_number"  value="{{old('added_invoice_number')}}" name="added_invoice_number" required/>
                            </td> 
                        </tr>
                        @else
                        <tr>
                            <td class = "edm-td-left">Invoice Number</td>
                            <td class = "edm-td-right">
                                <input type="hidden" id="added_invoice_profile_id"  value="{{$profile->INVOICE_PROFILE_ID}}" name="added_invoice_profile_id" required/>
                                <label style="display:inline-block;">{{$setting->INVOICE_NUMBER_PREFIX}}</label>
                                <input style="width:10%;padding-left:5px;" type="number" class = "edm-input" id="added_invoice_number"  value="{{$setting->LAST_INVOICE_NUMBER}}" name="added_invoice_number" required/>

                            </td> 
                        </tr>
                        @endif

                        <tr>
                            <td class = "edm-td-left">Invoice Date</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="added_invoice_date" value="{{old('added_invoice_date')}}" name="added_invoice_date" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Term of Payment</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="added_term_of_payment" value="{{$setting->PAYMENT_PERIOD}}" name="added_term_of_payment" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Due Date</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="added_due_date"  value="{{old('added_due_date')}}" name="added_due_date" readonly required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Ref Number</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="added_ref_number" name="added_ref_number" />
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Create Invoice" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="history_edit" tabindex="-1" role="dialog" aria-labelledby="history_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Invoice History</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('update.invoice') }}" autocomplete="off">
                @csrf
                    <table class="table-edit-delete">
                        
                        <tr>
                            <td class = "edm-td-left">Invoice Number</td>
                            <td class = "edm-td-right">
                                <input type="hidden" id="edited_invoice_history_id" name="edited_invoice_history_id" required/>
                                <label style="display:inline-block;">{{$setting->INVOICE_NUMBER_PREFIX}}</label>
                                <input style="width:10%;padding-left:5px;" type="number" class = "edm-input" id="edited_invoice_number" name="edited_invoice_number" required/>

                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Invoice Date</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="edited_invoice_date" value="{{old('edited_invoice_date')}}" name="edited_invoice_date" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Term of Payment</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="edited_term_of_payment" value="{{$setting->PAYMENT_PERIOD}}" name="edited_term_of_payment" maxlength="50" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Due Date</td>
                            <td class = "edm-td-right">
                                <input class = "edm-input" id="edited_due_date"  value="{{old('edited_due_date')}}" name="edited_due_date" readonly required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Ref Number</td>
                            <td class = "edm-td-right">
                                <input type="text" class = "edm-input" id="edited_ref_number" name="edited_ref_number" />
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Update Invoice" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
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

        $('#history_create').on('show.bs.modal', function(event){
            $('#added_invoice_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
            $('#added_invoice_date').datepicker('setDate', new Date());

            changeDate();

            $('#added_term_of_payment').keyup(function(){

                changeDate();
                
            })

            $('#added_invoice_date').change(function() {
                
                changeDate();
                
            });
        })

        $('#history_edit').on('show.bs.modal', function(event){

            $('#edited_invoice_date').datepicker({ dateFormat: 'yy-mm-dd' }).val();
            $('#edited_invoice_date').datepicker('setDate', new Date());

            changeDateEdit();

            $('#edited_term_of_payment').keyup(function(){

                changeDateEdit();
                
            })

            $('#edited_invoice_date').change(function() {
                
                changeDateEdit();
                
            });

            var button = $(event.relatedTarget);

            var edited_invoice_history_id = button.data('edited_invoice_history_id');
            var edited_invoice_number = button.data('edited_invoice_number');
            var edited_invoice_date = button.data('edited_invoice_date');
            var edited_term_of_payment = button.data('edited_term_of_payment');
            var edited_due_date = button.data('edited_due_date');
            var edited_ref_number = button.data('edited_ref_number');

            var modal = $(this);

            modal.find('.modal-body #edited_invoice_history_id').val(edited_invoice_history_id);
            modal.find('.modal-body #edited_invoice_number').val(edited_invoice_number);
            modal.find('.modal-body #edited_invoice_date').val(edited_invoice_date);
            modal.find('.modal-body #edited_term_of_payment').val(edited_term_of_payment);
            modal.find('.modal-body #edited_due_date').val(edited_due_date);
            modal.find('.modal-body #edited_ref_number').val(edited_ref_number);
        })

        function changeDate(){
            var invoiceDate = document.getElementById('added_invoice_date').value;
            var input = document.getElementById('added_term_of_payment').value;

            if (input){
                var date = new Date(invoiceDate);
                var newdate = new Date(invoiceDate);

                newdate.setDate(newdate.getDate() + parseInt(input));

                var dd = newdate.getDate();
                var mm = newdate.getMonth() + 1;
                var y = newdate.getFullYear();

                counter = String(dd).length;
                counterMonth = String(mm).length;

                if (counter == 1) dd='0'+dd;
                if (counterMonth == 1) mm='0'+mm;

                var formattedDate = y + '-' + mm + '-' + dd;
                
                $('#added_due_date').val(formattedDate);
            }
        }

        function changeDateEdit(){
            var invoiceDate = document.getElementById('edited_invoice_date').value;
            var input = document.getElementById('edited_term_of_payment').value;

            if (input){
                var date = new Date(invoiceDate);
                var newdate = new Date(invoiceDate);

                newdate.setDate(newdate.getDate() + parseInt(input));

                var dd = newdate.getDate();
                var mm = newdate.getMonth() + 1;
                var y = newdate.getFullYear();

                counter = String(dd).length;
                counterMonth = String(mm).length;

                if (counter == 1) dd='0'+dd;
                if (counterMonth == 1) mm='0'+mm;

                var formattedDate = y + '-' + mm + '-' + dd;
                
                $('#edited_due_date').val(formattedDate);
            }
        }
    </script>