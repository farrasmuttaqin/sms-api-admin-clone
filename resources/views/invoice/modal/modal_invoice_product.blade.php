    <div class="modal fade" id="product_create" tabindex="-1" role="dialog" aria-labelledby="product_create">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Add Invoice Product</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('product.create') }}" autocomplete="off">
                @csrf
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Product Name</td>
                            <td class = "edm-td-right">
                                <input type="hidden" class = "edm-input" id="added_profile_id"  value="{{$profile->INVOICE_PROFILE_ID}}" name="added_profile_id" required/>

                                <input type="hidden" class = "edm-input" id="added_owner_type"  value="PROFILE" name="added_owner_type" required/>

                                <input type="hidden" class = "edm-input" id="added_owner_id"  value="{{$profile->INVOICE_PROFILE_ID}}" name="added_owner_id" required/>

                                <input type="hidden" class = "edm-input" id="added_operator"  value="DEFAULT" name="added_operator" required/>

                                <input type="text" class = "edm-input" id="added_product_name"  value="{{old('added_product_name')}}" name="added_product_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Use Period?</td>
                            <td class = "edm-td-right">
                                <select id="added_use_period" name="added_use_period" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_period')</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td> 
                        </tr>

                        <tr id="report_tr" style="display:none;">
                            <td class = "edm-td-left">Use Billing Report?</td>
                            <td class = "edm-td-right">
                                <select id="added_use_report" name="added_use_report" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;">
                                    <option value="">@lang('app.no_report')</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td> 
                        </tr>

                        <tr id="ug_tr" style="display:none;">
                            <td class = "edm-td-left">Report Name</td>
                            <td class = "edm-td-right">
                                <select id="added_report_name" name="added_report_name" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;">
                                  <option value="">@lang('app.no_report_name')</option>

                                  @if ($groups == "[]")
                                  @else
                                    <optgroup label = "GROUPS">
                                      @foreach ($groups as $group)
                                        <option value="{{$group->NAME}}">{{$group->NAME}}</option>
                                      @endforeach
                                    </optgroup>
                                  @endif
                                  @if ($reports == "[]")
                                  @else
                                    <optgroup label = "REPORTS">
                                      @foreach ($reports as $report)
                                        <option value="{{$report->USER_NAME}}">{{$report->USER_NAME}}</option>
                                      @endforeach
                                    </optgroup>
                                  @endif
                                </select>
                            </td> 
                        </tr>

                        <tr id="manual_tr" style="display:none;">
                            <td class = "edm-td-left">Input Manual</td>
                            <td class = "edm-td-right">
                                <input type="checkbox" id="added_input_manual" name="added_input_manual" value="1" class="uk-width-1-1" />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Quantity</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="added_quantity" value="{{old('added_quantity')}}" name="added_quantity" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Unit Price (IDR)</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="added_unit_price" value="{{old('added_unit_price')}}" name="added_unit_price" maxlength="50" required/>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Add Product" /><i class="pencil-edit-delete las la-plus-square"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="product_edit" tabindex="-1" role="dialog" aria-labelledby="product_edit">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <button class="details-button" type="button" data-dismiss="modal" aria-label="Close"><i class="uk-icon-close-modal uk-icon-close"></i></button>
                <h3 class="modal-title">Edit Invoice Product</h3>
            </div>
            <div class="modal-body">
                <form method = "POST" action = "{{ route('product.update') }}" autocomplete="off">
                @csrf
                    <table class="table-edit-delete">
                        <tr>
                            <td class = "edm-td-left">Product Name</td>
                            <td class = "edm-td-right">

                                <input type="hidden" class = "edm-input" id="edited_product_id" name="edited_product_id" required/>

                                <input type="hidden" class = "edm-input" id="edited_profile_id"  value="{{$profile->INVOICE_PROFILE_ID}}" name="edited_profile_id" required/>

                                <input type="hidden" class = "edm-input" id="edited_owner_type"  value="PROFILE" name="edited_owner_type" required/>

                                <input type="hidden" class = "edm-input" id="edited_owner_id"  value="{{$profile->INVOICE_PROFILE_ID}}" name="edited_owner_id" required/>

                                <input type="hidden" class = "edm-input" id="edited_operator"  value="DEFAULT" name="edited_operator" required/>

                                <input type="text" class = "edm-input" id="edited_product_name"  value="{{old('edited_product_name')}}" name="edited_product_name" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Use Period?</td>
                            <td class = "edm-td-right">
                                <select id="edited_use_period" name="edited_use_period" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;" required>
                                    <option value="">@lang('app.no_period')</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td> 
                        </tr>

                        <tr id="edited_report_tr" style="display:none;">
                            <td class = "edm-td-left">Use Billing Report?</td>
                            <td class = "edm-td-right">
                                <select id="edited_use_report" name="edited_use_report" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;">
                                    <option value="">@lang('app.no_report')</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </td> 
                        </tr>

                        <tr id="edited_ug_tr" style="display:none;">
                            <td class = "edm-td-left">Report Name</td>
                            <td class = "edm-td-right">
                                <select id="edited_report_name" name="edited_report_name" class="uk-width-1-1" style="height:28px;background-color:white;border-color:white;color:black;">
                                  <option value="">@lang('app.no_report_name')</option>

                                  @if ($groups == "[]")
                                  @else
                                    <optgroup label = "GROUPS">
                                      @foreach ($groups as $group)
                                        <option value="{{$group->NAME}}">{{$group->NAME}}</option>
                                      @endforeach
                                    </optgroup>
                                  @endif
                                  @if ($reports == "[]")
                                  @else
                                    <optgroup label = "REPORTS">
                                      @foreach ($reports as $report)
                                        <option value="{{$report->USER_NAME}}">{{$report->USER_NAME}}</option>
                                      @endforeach
                                    </optgroup>
                                  @endif
                                </select>
                            </td> 
                        </tr>

                        <tr id="edited_manual_tr" style="display:none;">
                            <td class = "edm-td-left">Input Manual</td>
                            <td class = "edm-td-right">
                                <input type="checkbox" id="edited_input_manual" name="edited_input_manual" value="1" class="uk-width-1-1" />
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Quantity</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="edited_quantity" value="{{old('edited_quantity')}}" name="edited_quantity" required/>
                            </td> 
                        </tr>

                        <tr>
                            <td class = "edm-td-left">Unit Price (IDR)</td>
                            <td class = "edm-td-right">
                                <input type="number" class = "edm-input" id="edited_unit_price" value="{{old('edited_unit_price')}}" name="edited_unit_price" maxlength="50" required/>
                            </td> 
                        </tr>

                    </table>
                    <table class = "table-edit-delete">
                        <tr class = "tr-edit-delete">
                            <td width='100%' class = "td-edit-delete uk-float-right">
                                <input type="submit" class = "span1-edit-delete" style="cursor:pointer;background-color:rgb(59,59,59);border:1px solid rgb(59,59,59);color:white;font-size:12px;" value="Update Product" /><i class="pencil-edit-delete las la-pencil-alt"></i> 
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            </div>
        </div>
    </div>

    <script>

        $('#product_edit').on('show.bs.modal', function(event){

            var button = $(event.relatedTarget);

            var edited_product_id = button.data('edited_product_id');
            var edited_product_name = button.data('edited_product_name');
            var edited_use_period = button.data('edited_use_period');
            var edited_unit_price = button.data('edited_unit_price');
            var edited_quantity = button.data('edited_quantity');

            var edited_use_report = button.data('edited_use_report');
            var edited_report_name = button.data('edited_report_name');

            var modal = $(this);

            modal.find('.modal-body #edited_product_id').val(edited_product_id);
            modal.find('.modal-body #edited_product_name').val(edited_product_name);
            modal.find('.modal-body #edited_use_period').val(edited_use_period);
            modal.find('.modal-body #edited_unit_price').val(edited_unit_price);
            modal.find('.modal-body #edited_quantity').val(edited_quantity);

            modal.find('.modal-body #edited_use_report').val(edited_use_report);
            modal.find('.modal-body #edited_report_name').val(edited_report_name);
            
            var suggestions = [
              "SMS Mobile Broadcast",
              "SMS Mobile Broadcast Premium",
              "SMS API Dispatcher",
              "SMS API Interface",
              "Minimum charge SMS API Dispatcher",
              "Minimum charge SMS Mobile Broadcast",
              "Minimum charge SMS API Gateway",
              "Minimum charge SMS On Demand",
              "Minimum charge Email to SMS",
              "Maintenance modem Internal",
              "Maintenance modem",
            ];

            autocomplete(document.getElementById("edited_product_name"), suggestions);

            inputRequiredSettingUpdate();

            $( "#edited_use_period" ).change(function(e) {

              $('#edited_unit_price').val('');
              $('#edited_unit_price').prop('readonly', false);
              $('#edited_unit_price').prop('required', true);

              $('#edited_quantity').val('');
              $('#edited_quantity').prop('readonly', false);
              $('#edited_quantity').prop('required', true); 

              if(this.value == "0"){
                $( "#edited_use_report" ).val('');
                $( "#edited_report_tr" ).hide();

                $( "#edited_user_group_name" ).val('');
                $( "#edited_ug_tr" ).hide();

                $( "#edited_manual_tr" ).hide();
                $( "#edited_input_manual").prop('checked',false);
                $( "#edited_report_name" ).prop('required',false)
                $( "#edited_use_report" ).prop('required',false)
              }else if(this.value == "1"){
                $( "#edited_report_tr" ).show();
                $( "#edited_use_report" ).prop('required',true)
              }
            });

            $( "#edited_use_report" ).change(function(e) {
              if(this.value == "0"){
                $( "#edited_user_group_name" ).val('');
                $( "#edited_ug_tr" ).hide();
                $( "#edited_manual_tr" ).hide();

                $('#edited_quantity').val('');
                $('#edited_quantity').prop('readonly', false);
                $('#edited_quantity').prop('required', true);

                $('#edited_unit_price').val('');
                $('#edited_unit_price').prop('readonly', false);
                $('#edited_unit_price').prop('required', true);

                $( "#edited_report_name" ).prop('required',false)
              }else if(this.value == "1"){

                $( "#edited_ug_tr" ).show();
                $( "#edited_manual_tr" ).show();
                $("#edited_report_name").prop('required',true)

                if ($('#edited_input_manual').is(':checked')) {
                  $('#edited_quantity').val('');
                  $('#edited_quantity').prop('readonly', false);
                  $('#edited_quantity').prop('required', true);

                  $('#edited_unit_price').val('');
                  $('#edited_unit_price').prop('readonly', false);
                  $('#edited_unit_price').prop('required', true);
                }else if (!$('#edited_input_manual').is(':checked')) {
                  $('#edited_quantity').val('');
                  $('#edited_quantity').prop('readonly', true);
                  $('#edited_quantity').prop('required', false);

                  $('#edited_unit_price').val('');
                  $('#edited_unit_price').prop('readonly', true);
                  $('#edited_unit_price').prop('required', false);
                }
              }
            });

            $( "#edited_input_manual" ).click(function(e) { 
              if ($('#edited_input_manual').is(':checked')) {

                $('#edited_unit_price').val('');
                $('#edited_unit_price').prop('readonly', false);
                $('#edited_unit_price').prop('required', true);

                $('#edited_quantity').val('');
                $('#edited_quantity').prop('readonly', false);
                $('#edited_quantity').prop('required', true); 
              }else if (!$('#edited_input_manual').is(':checked')){
                $('#edited_unit_price').val('');
                $('#edited_unit_price').prop('readonly', true);
                $('#edited_unit_price').prop('required', false);

                $('#edited_quantity').val('');
                $('#edited_quantity').prop('readonly', true);
                $('#edited_quantity').prop('required', false); 
              }
            })
        })

        $('#product_create').on('show.bs.modal', function(event){

            var suggestions = [
              "SMS Mobile Broadcast",
              "SMS Mobile Broadcast Premium",
              "SMS API Dispatcher",
              "SMS API Interface",
              "Minimum charge SMS API Dispatcher",
              "Minimum charge SMS Mobile Broadcast",
              "Minimum charge SMS API Gateway",
              "Minimum charge SMS On Demand",
              "Minimum charge Email to SMS",
              "Maintenance modem Internal",
              "Maintenance modem",
            ];

            autocomplete(document.getElementById("added_product_name"), suggestions);

            inputRequiredSetting();

            $( "#added_use_period" ).change(function(e) {

              $('#added_unit_price').val('');
              $('#added_unit_price').prop('readonly', false);
              $('#added_unit_price').prop('required', true);

              $('#added_quantity').val('');
              $('#added_quantity').prop('readonly', false);
              $('#added_quantity').prop('required', true); 

              if(this.value == "0"){
                $( "#added_use_report" ).val('');
                $( "#report_tr" ).hide();

                $( "#ug_tr" ).hide();

                $( "#manual_tr" ).hide();
                $( "#added_input_manual").prop('checked',false);

                $( "#added_report_name" ).prop('required',false)
                $( "#added_use_report" ).prop('required',false)
              }else if(this.value == "1"){
                $( "#report_tr" ).show();
                $( "#added_use_report" ).prop('required',true)

              }
            });

            $( "#added_use_report" ).change(function(e) {
              if(this.value == "no"){
                $( "#ug_tr" ).hide();
                $( "#manual_tr" ).hide();

                $('#added_quantity').val('');
                $('#added_quantity').prop('readonly', false);
                $('#added_quantity').prop('required', true);

                $('#added_unit_price').val('');
                $('#added_unit_price').prop('readonly', false);
                $('#added_unit_price').prop('required', true);

                $( "#added_report_name" ).prop('required',false)
                $( "#added_use_report" ).prop('required',false)
              }else if(this.value == "1"){

                $( "#ug_tr" ).show();
                $( "#manual_tr" ).show();
                $("#added_report_name").prop('required',true)

                if ($('#added_input_manual').is(':checked')) {
                  $('#added_quantity').val('');
                  $('#added_quantity').prop('readonly', false);
                  $('#added_quantity').prop('required', true);

                  $('#added_unit_price').val('');
                  $('#added_unit_price').prop('readonly', false);
                  $('#added_unit_price').prop('required', true);
                }else if (!$('#added_input_manual').is(':checked')) {
                  $('#added_quantity').val('');
                  $('#added_quantity').prop('readonly', true);
                  $('#added_quantity').prop('required', false);

                  $('#added_unit_price').val('');
                  $('#added_unit_price').prop('readonly', true);
                  $('#added_unit_price').prop('required', false);
                }
                
              }
            });

            $( "#added_input_manual" ).click(function(e) { 
              if ($('#added_input_manual').is(':checked')) {

                $('#added_unit_price').val('');
                $('#added_unit_price').prop('readonly', false);
                $('#added_unit_price').prop('required', true);

                $('#added_quantity').val('');
                $('#added_quantity').prop('readonly', false);
                $('#added_quantity').prop('required', true); 
              }else if (!$('#added_input_manual').is(':checked')){
                $('#added_unit_price').val('');
                $('#added_unit_price').prop('readonly', true);
                $('#added_unit_price').prop('required', false);

                $('#added_quantity').val('');
                $('#added_quantity').prop('readonly', true);
                $('#added_quantity').prop('required', false); 
              }
            })
        })

        function inputRequiredSettingUpdate(){
          if ($('#edited_input_manual').is(':checked')) {

            $('#edited_unit_price').prop('readonly', false);
            $('#edited_unit_price').prop('required', true);

            $('#edited_quantity').prop('readonly', false);
            $('#edited_quantity').prop('required', true); 
          }else if (!$('#edited_input_manual').is(':checked')) {
            $('#edited_unit_price').prop('readonly', true);
            $('#edited_unit_price').prop('required', false);

            $('#edited_quantity').prop('readonly', true);
            $('#edited_quantity').prop('required', false); 
          }

          if ($( "#edited_use_period" ).val() == "0"){
            $( "#edited_report_tr" ).hide();

            $( "#edited_ug_tr" ).hide();

            $( "#edited_manual_tr" ).hide();
            $( "#edited_input_manual").prop('checked',false);

            $('#edited_unit_price').prop('readonly', false);
            $('#edited_unit_price').prop('required', true);

            $('#edited_quantity').prop('readonly', false);
            $('#edited_quantity').prop('required', true); 
            $( "#edited_report_name" ).prop('required',false)
            $( "#edited_use_report" ).prop('required',false)
          }else if ($( "#edited_use_period" ).val() == "1"){
            $( "#edited_report_tr" ).show();
            $("#edited_use_report").prop('required',true);
          }

          if ($( "#edited_use_report" ).val() == "0"){
            $( "#edited_ug_tr" ).hide();
            $( "#edited_manual_tr" ).hide();

            $('#edited_quantity').prop('readonly', false);
            $('#edited_quantity').prop('required', true);

            $('#edited_unit_price').prop('readonly', false);
            $('#edited_unit_price').prop('required', true);

            $( "#edited_report_name" ).prop('required',false)
          }else if ($( "#edited_use_report" ).val() == "1"){
            $( "#edited_ug_tr" ).show();
            $( "#edited_manual_tr" ).show();

            $( "#edited_report_name" ).prop('required',true)
            if ($('#edited_input_manual').is(':checked')) {
              $('#edited_quantity').prop('readonly', false);
              $('#edited_quantity').prop('required', true);

              $('#edited_unit_price').prop('readonly', false);
              $('#edited_unit_price').prop('required', true);
            }else if (!$('#edited_input_manual').is(':checked')) {
              $('#edited_quantity').prop('readonly', true);
              $('#edited_quantity').prop('required', false);

              $('#edited_unit_price').prop('readonly', true);
              $('#edited_unit_price').prop('required', false);
            }
          }
        }

        function inputRequiredSetting(){
          if ($('#added_input_manual').is(':checked')) {

            $('#added_unit_price').val('');
            $('#added_unit_price').prop('readonly', false);
            $('#added_unit_price').prop('required', true);

            $('#added_quantity').val('');
            $('#added_quantity').prop('readonly', false);
            $('#added_quantity').prop('required', true); 
          }else if (!$('#added_input_manual').is(':checked')) {
            $('#added_unit_price').val('');
            $('#added_unit_price').prop('readonly', true);
            $('#added_unit_price').prop('required', false);

            $('#added_quantity').val('');
            $('#added_quantity').prop('readonly', true);
            $('#added_quantity').prop('required', false); 
          }

          if ($( "#added_use_period" ).val() == "0"){
            $( "#added_use_report" ).val('');
            $( "#report_tr" ).hide();
            $( "#ug_tr" ).hide();

            $( "#manual_tr" ).hide();
            $( "#added_input_manual").prop('checked',false);
            $( "#added_report_name" ).prop('required',false)

            $('#added_unit_price').val('');
            $('#added_unit_price').prop('readonly', false);
            $('#added_unit_price').prop('required', true);

            $('#added_quantity').val('');
            $('#added_quantity').prop('readonly', false);
            $('#added_quantity').prop('required', true); 

            $( "#added_use_report" ).prop('required',false)
          }else if ($( "#added_use_period" ).val() == "1"){
            $( "#report_tr" ).show();
            $( "#added_use_report" ).prop('required',true)
          }

          if ($( "#added_use_report" ).val() == "0"){
            $( "#ug_tr" ).hide();
            $( "#manual_tr" ).hide();

            $('#added_quantity').val('');
            $('#added_quantity').prop('readonly', false);
            $('#added_quantity').prop('required', true);

            $('#added_unit_price').val('');
            $('#added_unit_price').prop('readonly', false);
            $('#added_unit_price').prop('required', true);

            $( "#added_use_report" ).prop('required',false)
            $( "#added_report_name" ).prop('required',false)
          }else if ($( "#added_use_report" ).val() == "1"){
            $( "#ug_tr" ).show();
            $( "#manual_tr" ).show();

            $( "#added_report_name" ).prop('required',true)
            if ($('#added_input_manual').is(':checked')) {
              $('#added_quantity').val('');
              $('#added_quantity').prop('readonly', false);
              $('#added_quantity').prop('required', true);

              $('#added_unit_price').val('');
              $('#added_unit_price').prop('readonly', false);
              $('#added_unit_price').prop('required', true);
            }else if (!$('#added_input_manual').is(':checked')) {
              $('#added_quantity').val('');
              $('#added_quantity').prop('readonly', true);
              $('#added_quantity').prop('required', false);

              $('#added_unit_price').val('');
              $('#added_unit_price').prop('readonly', true);
              $('#added_unit_price').prop('required', false);
            }
          }
        }
          function autocomplete(inp, arr) {
            /*the autocomplete function takes two arguments,
            the text field element and an array of possible autocompleted values:*/
            var currentFocus;
            /*execute a function when someone writes in the text field:*/
            inp.addEventListener("input", function(e) {
                var a, b, i, val = this.value;
                /*close any already open lists of autocompleted values*/
                closeAllLists();
                if (!val) { return false;}
                currentFocus = -1;
                /*create a DIV element that will contain the items (values):*/
                a = document.createElement("DIV");
                a.setAttribute("id", this.id + "autocomplete-list");
                a.setAttribute("class", "autocomplete-items");
                /*append the DIV element as a child of the autocomplete container:*/
                this.parentNode.appendChild(a);
                /*for each item in the array...*/
                for (i = 0; i < arr.length; i++) {
                  /*check if the item starts with the same letters as the text field value:*/
                  if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
                    /*create a DIV element for each matching element:*/
                    b = document.createElement("DIV");
                    /*make the matching letters bold:*/
                    b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
                    b.innerHTML += arr[i].substr(val.length);
                    /*insert a input field that will hold the current array item's value:*/
                    b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
                    /*execute a function when someone clicks on the item value (DIV element):*/
                        b.addEventListener("click", function(e) {
                        /*insert the value for the autocomplete text field:*/
                        inp.value = this.getElementsByTagName("input")[0].value;
                        /*close the list of autocompleted values,
                        (or any other open lists of autocompleted values:*/
                        closeAllLists();
                    });
                    a.appendChild(b);
                  }
                }
            });
            /*execute a function presses a key on the keyboard:*/
            inp.addEventListener("keydown", function(e) {
                var x = document.getElementById(this.id + "autocomplete-list");
                if (x) x = x.getElementsByTagName("div");
                if (e.keyCode == 40) {
                  /*If the arrow DOWN key is pressed,
                  increase the currentFocus variable:*/
                  currentFocus++;
                  /*and and make the current item more visible:*/
                  addActive(x);
                } else if (e.keyCode == 38) { //up
                  /*If the arrow UP key is pressed,
                  decrease the currentFocus variable:*/
                  currentFocus--;
                  /*and and make the current item more visible:*/
                  addActive(x);
                } else if (e.keyCode == 13) {
                  /*If the ENTER key is pressed, prevent the form from being submitted,*/
                  e.preventDefault();
                  if (currentFocus > -1) {
                    /*and simulate a click on the "active" item:*/
                    if (x) x[currentFocus].click();
                  }
                }
            });
            function addActive(x) {
              /*a function to classify an item as "active":*/
              if (!x) return false;
              /*start by removing the "active" class on all items:*/
              removeActive(x);
              if (currentFocus >= x.length) currentFocus = 0;
              if (currentFocus < 0) currentFocus = (x.length - 1);
              /*add class "autocomplete-active":*/
              x[currentFocus].classList.add("autocomplete-active");
            }
            function removeActive(x) {
              /*a function to remove the "active" class from all autocomplete items:*/
              for (var i = 0; i < x.length; i++) {
                x[i].classList.remove("autocomplete-active");
              }
            }
            function closeAllLists(elmnt) {
              /*close all autocomplete lists in the document,
              except the one passed as an argument:*/
              var x = document.getElementsByClassName("autocomplete-items");
              for (var i = 0; i < x.length; i++) {
                if (elmnt != x[i] && elmnt != inp) {
                x[i].parentNode.removeChild(x[i]);
              }
            }
          }
          /*execute a function when someone clicks in the document:*/
          document.addEventListener("click", function (e) {
              closeAllLists(e.target);
          });
        } 
    </script>