
    <div class="modal fade" id="credit_deduct" tabindex="-1" role="dialog" aria-labelledby="credit_top_up">
        <div class="modal-dialog" role="document">
            <form action = "{{route('credit.deduct')}}" method="post">
                @csrf
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_user_id" value="{{$user->USER_ID}}" />
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_user_credit" value="{{$user->CREDIT}}" />
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_username" value="{{$user->USER_NAME}}" />

                <div class="modal-content">
                    <div class="modal-header">
                        <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                        <h3 class="modal-title">Credit Removal</h3>
                    </div>
                    <div class="modal-body">
                        
                            <table class = "bo-table">
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Credit</span> 
                                    </td>
                                    <td width='60%' colspan='3'>
                                        <input type="number" class = "bo-cn-input-requested-1-1" name="added_credit_deduct"  required/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td" style="position:fixed;">
                                        <span class = "bo-ro-span-bold" >Information</span> 
                                    </td>
                                    <td width='60%' colspan = '2'>
                                        <textarea class = "edm-input-2" id="agentDescriptionEdit" value="{{old('edited_agent_description')}}" name="added_information_deduct" style="height:90px;" >{{old('edited_agent_description')}}</textarea>
                                    </td> 
                                </tr>

                            </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class = "bo-cancel-cursor">Close</button>
                        <button type="submit" class = "bo-save-cursor">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="credit_detail" tabindex="-1" role="dialog" aria-labelledby="credit_detail">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">View Transaction Details</h3>
            </div>
            <div class="modal-body">
                <form>
                    <table class="cdm-table">
                        <tr>
                            <td class = "cdm-td-left">Reference Code</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" style="font-weight:bold;" id="reference_code" readonly="readonly" disabled />
                            </td> 
                        </tr>
                        <tr>
                            <td class = "cdm-td-left">Requested By</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="requested_by" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Credit Mutation</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="credit_mutation" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Price</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="price" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Currency</td>
                            <td class = "cdm-td-right">
                                <input type = "email" class = "cdm-input" id="currency" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Payment Method</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="payment_method" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Payment Status</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="payment_status" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Payment Date</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="payment_date" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Created Date</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="created_date" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Updated Date</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="updated_date" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Created By</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="created_by" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Updated By</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="updated_by" readonly="readonly" disabled />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "cdm-td-left">Remark</td>
                            <td class = "cdm-td-right">
                                <input class = "cdm-input" id="remark" readonly="readonly" disabled />
                            </td> 
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class = "api-accounts" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="credit_top_up" tabindex="-1" role="dialog" aria-labelledby="credit_top_up">
        <div class="modal-dialog" role="document">
            <form action = "{{route('credit.top_up')}}" method="post">
                @csrf
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_user_id" value="{{$user->USER_ID}}" />
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_user_credit" value="{{$user->CREDIT}}" />
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_username" value="{{$user->USER_NAME}}" />

                <div class="modal-content">
                    <div class="modal-header">
                        <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                        <h3 class="modal-title">Credit Top Up</h3>
                    </div>
                    <div class="modal-body">
                        
                            <table class = "bo-table">
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td-bold">
                                        <span class = "bo-ro-span-bold">Requested By</span> 
                                    </td>
                                    <td width='60%' colspan='3'>
                                        <input type="text" class = "bo-cn-input-requested-1-1" value="{{old('added_requested_by')}}" name="added_requested_by" />
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Credit</span> 
                                    </td>
                                    <td width='60%' colspan='3'>
                                        <input type="number" class = "bo-cn-input-requested-1-1" value="{{old('added_credit')}}" name="added_credit"  required/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Price</span> 
                                    </td>
                                    <td width='25%' class= "bo-cn-td">
                                        <input type="number" value="{{old('added_price')}}" style="width:90%;" class="bo-cn-input-requested-1-1" name="added_price" required />
                                    </td> 
                                    <td width='25%'>
                                        <select value="{{old('added_currency')}}" name="added_currency" class="bo-cn-input-no-width4" required>
                                            <option value="">@lang('app.no_currency')</option>
                                            <option value="rp">Rupiah</option>
                                            <option value="usd">US Dollar</option>
                                            <option value="euro">Euro</option>
                                        </select>
                                    </td> 
                                    <td width='25%' class= "bo-cn-td">
                                    </td>
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Payment Method</span> 
                                    </td>
                                    <td width='60%' colspan='2'>
                                        <select value="{{old('added_payment_method')}}" name="added_payment_method" class="bo-cn-input-no-width4" required>
                                            <option value="">@lang('app.no_payment_method')</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="internet_banking">Internet Banking</option>
                                            <option value="phone_bill">Phone Bill</option>
                                            <option value="voucher">Voucher</option>
                                        </select>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td" style="position:fixed;">
                                        <span class = "bo-ro-span-bold" >Information</span> 
                                    </td>
                                    <td width='60%' colspan = '2'>
                                        <textarea class = "edm-input-2" id="agentDescriptionEdit" name="added_information" style="height:90px;" >{{old('added_information')}}</textarea>
                                    </td> 
                                </tr>

                            </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class = "bo-cancel-cursor">Close</button>
                        <button type="submit" class = "bo-save-cursor">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="credit_payment_acknowledgement" tabindex="-1" role="dialog" aria-labelledby="credit_payment_acknowledgement">
        <div class="modal-dialog" role="document">
            <form action = "{{route('credit.payment_acknowledgement')}}" method="post">
                @csrf
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_user_id" value="{{$user->USER_ID}}" />

                <input type="hidden" class = "bo-cn-input-requested-1-1" name="added_username" value="{{$user->USER_NAME}}" />

                <div class="modal-content">
                    <div class="modal-header">
                        <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                        <h3 class="modal-title">Payment Acknowledgement</h3>
                    </div>
                    <div class="modal-body">
                            <table class = "bo-table">
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span">Reference</span> 
                                    </td>
                                    <td width='75%' colspan='2'>
                                        <input class = "bo-cn-input-requested" id="reference_code_ack" disabled/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr-2">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span">Requested By</span> 
                                    </td>
                                    <td width='75%' colspan='2'>
                                        <input class = "bo-cn-input-requested" id="requested_by_ack" name="farras" disabled/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr-2">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span">Credit</span> 
                                    </td>
                                    <td width='75%' colspan='2'>
                                        <input class = "bo-cn-input-requested" id="credit_mutation_ack" disabled/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr-2">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span">Price</span> 
                                    </td>
                                    <td width='75%' colspan='2'>
                                        <input class = "bo-cn-input-requested" id="price_ack" disabled/>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span">Currency</span> 
                                    </td>
                                    <td width='75%'>
                                        <select style="color:black;" id="currency_ack" class="bo-cn-input-no-width3" disabled>
                                            <option value="">@lang('app.no_currency')</option>
                                            <option value="rp">Rupiah</option>
                                            <option value="usd">US Dollar</option>
                                            <option value="euro">Euro</option>
                                        </select>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span">Payment Method</span> 
                                    </td>
                                    <td width='75%'>
                                        <select style="color:black;" id="payment_method_ack" class="bo-cn-input-no-width3" disabled>
                                            <option value="">@lang('app.no_payment_method')</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="internet_banking">Internet Banking</option>
                                            <option value="phone_bill">Phone Bill</option>
                                            <option value="voucher">Voucher</option>
                                        </select>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Payment Date</span> 
                                    </td>
                                    <td width='75%' colspan='2'>
                                        <input style="background-color:white;" id="date_for_payment_acknowledgement" class = "bo-cn-input-requested" name="payment_date_acknowledgement" required/>
                                        <input type="hidden" id ="credit_transaction_id_unique" name="added_transaction_id" />
                                    </td> 
                                </tr>

                            </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class = "bo-cancel">Close</button>
                        <button type="submit" class = "bo-save">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="credit_edit" tabindex="-1" role="dialog" aria-labelledby="credit_edit">
        <div class="modal-dialog" role="document">
            <form action = "{{route('credit.update')}}" method="post">
                @csrf
                <input type="hidden" class = "bo-cn-input-requested-1-1" name="edited_user_id" value="{{$user->USER_ID}}" />

                <div class="modal-content">
                    <div class="modal-header">
                        <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                        <h3 class="modal-title">Credit Top Up</h3>
                    </div>
                    <div class="modal-body">
                        
                            <table class = "bo-table">
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td-bold">
                                        <span class = "bo-ro-span-bold">Transaction Ref</span> 
                                    </td>
                                    <td width='60%' colspan='3'>
                                        <input type="text" class = "bo-cn-input-requested-1-1" id="edited_transaction_ref" disabled />
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td-bold">
                                        <span class = "bo-ro-span-bold">Requested By</span> 
                                    </td>
                                    <td width='60%' colspan='3'>
                                        <input type="text" class = "bo-cn-input-requested-1-1" name="edited_requested_by" id="edited_requested_by" />
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    
                                    <td width='40%' class = "bo-cn-td-bold">
                                        <span class = "bo-ro-span-bold">Credit</span> 
                                    </td>
                                    <td width='60%' colspan='3'>
                                        <input type="text" class = "bo-cn-input-requested-1-1" id="edited_credit" disabled />
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='25%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Price</span> 
                                    </td>
                                    <td width='25%' class= "bo-cn-td">
                                        <input type="number" style="width:90%;" class="bo-cn-input-requested-1-1" name="edited_price" id="edited_price" required />
                                    </td> 
                                    <td width='25%'>
                                        <select name="edited_currency" id="edited_currency" class="bo-cn-input-no-width4" required>
                                            <option value="">@lang('app.no_currency')</option>
                                            <option value="rp">Rupiah</option>
                                            <option value="usd">US Dollar</option>
                                            <option value="euro">Euro</option>
                                        </select>
                                    </td> 
                                    <td width='25%' class= "bo-cn-td">
                                    </td>
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td">
                                        <span class = "bo-ro-span-bold">Payment Method</span> 
                                    </td>
                                    <td width='60%' colspan='2'>
                                        <select name="edited_payment_method" id="edited_payment_method" class="bo-cn-input-no-width4" required>
                                            <option value="">@lang('app.no_payment_method')</option>
                                            <option value="bank">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                            <option value="internet_banking">Internet Banking</option>
                                            <option value="phone_bill">Phone Bill</option>
                                            <option value="voucher">Voucher</option>
                                        </select>
                                    </td> 
                                </tr>
                                <tr class = "bo-tr">
                                    <td width='40%' class = "bo-cn-td" style="position:fixed;">
                                        <span class = "bo-ro-span-bold" >Information</span> 
                                    </td>
                                    <td width='60%' colspan = '2'>
                                        <textarea class = "edm-input-2" id="edited_information" value="{{old('edited_agent_description')}}" name="edited_information" style="height:90px;" >{{old('edited_information')}}</textarea>

                                        <input type="hidden" id ="ed_edited_transaction_ref" name="edited_transaction_ref" />

                                        <input type="hidden" id ="edited_credit_transaction_id" name="edited_credit_transaction_id" />
                                    </td> 
                                </tr>

                            </table>
                        
                    </div>
                    <div class="modal-footer">
                        <button data-dismiss="modal" class = "bo-cancel-cursor">Close</button>
                        <button type="submit" class = "bo-save-cursor">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            $('#date_for_payment_acknowledgement').datepicker({ dateFormat: 'yy-mm-dd' }).val();
        });

        $('#credit_detail').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var reference_code = button.data('reference_code'),
                requested_by = button.data('requested_by'),
                credit_mutation = button.data('credit_mutation'),
                price = button.data('price'),
                currency = button.data('currency'),
                payment_method = button.data('payment_method'),
                payment_status = button.data('payment_status'),
                payment_date = button.data('payment_date'),
                created_date = button.data('created_date'),
                updated_date = button.data('updated_date'),
                created_by = button.data('created_by'),
                updated_by = button.data('updated_by'),
                remark = button.data('remark');
            
            var modal = $(this);

            modal.find('.modal-body #reference_code').val(reference_code);
            modal.find('.modal-body #requested_by').val(requested_by);
            modal.find('.modal-body #credit_mutation').val(credit_mutation);
            modal.find('.modal-body #price').val(price);
            modal.find('.modal-body #currency').val(currency);
            modal.find('.modal-body #payment_method').val(payment_method);
            modal.find('.modal-body #payment_status').val(payment_status);
            modal.find('.modal-body #payment_date').val(payment_date);
            modal.find('.modal-body #created_date').val(created_date);
            modal.find('.modal-body #updated_date').val(updated_date);
            modal.find('.modal-body #created_by').val(created_by);
            modal.find('.modal-body #updated_by').val(updated_by);
            modal.find('.modal-body #remark').val(remark);
        })

        $('#credit_payment_acknowledgement').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var reference_code = button.data('ack_transaction_ref'),
                credit_transaction_id = button.data('ack_credit_transaction_id'),
                requested_by = button.data('ack_requested_by'),
                credit_mutation = button.data('ack_credit'),
                price = button.data('ack_price'),
                currency = button.data('ack_currency'),
                payment_method = button.data('ack_payment_method');

            var modal = $(this);

            modal.find('.modal-body #credit_transaction_id_unique').val(credit_transaction_id);
            modal.find('.modal-body #reference_code_ack').val(reference_code);
            modal.find('.modal-body #requested_by_ack').val(requested_by);
            modal.find('.modal-body #credit_mutation_ack').val(credit_mutation);
            modal.find('.modal-body #price_ack').val(price);
            modal.find('.modal-body #currency_ack').val(currency);
            modal.find('.modal-body #payment_method_ack').val(payment_method);
        });

        $('#credit_edit').on('show.bs.modal', function(event){
            var button = $(event.relatedTarget);

            var edited_transaction_ref = button.data('edited_transaction_ref'),
                edited_requested_by = button.data('edited_requested_by'),
                edited_credit = button.data('edited_credit'),
                edited_price = button.data('edited_price'),
                edited_currency = button.data('edited_currency'),
                edited_payment_method = button.data('edited_payment_method'),
                edited_information = button.data('edited_information'),
                edited_credit_transaction_id = button.data('edited_credit_transaction_id');

            var modal = $(this);
            
            modal.find('.modal-body #edited_transaction_ref').val(edited_transaction_ref);
            modal.find('.modal-body #edited_credit_transaction_id').val(edited_credit_transaction_id);
            modal.find('.modal-body #ed_edited_transaction_ref').val(edited_transaction_ref);
            modal.find('.modal-body #edited_requested_by').val(edited_requested_by);
            modal.find('.modal-body #edited_credit').val(edited_credit);
            modal.find('.modal-body #edited_price').val(parseInt(edited_price));
            modal.find('.modal-body #edited_currency').val(edited_currency);
            modal.find('.modal-body #edited_payment_method').val(edited_payment_method);
            modal.find('.modal-body #edited_information').val(edited_information);
        });
        
    </script>