<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog" role="document">

      <!-- Modal Content -->
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header bg-primary">
          <h3 class="modal-title text-white" id="model-1"></h3>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <!-- /modal header -->
        <form id="store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <div class="col-md-12">
                        <h1>Discount Type</h1>
                    </div>
                    <x-form.textbox labelName="Condition Name" name="condition_name" id="condition_name" required="required" col="col-md-12" placeholder="Enter Discount Amount"/>
                    <x-form.selectbox labelName="Discount Type" name="condition_type" id="condition_type" col="col-md-12" class="selectpicker" required="required">
                                    <option value="free_delivery">Free Delivery</option>
                                    <option value="fixed_percentage_discount">Fixed Percentage Discount</option>
                                    <option value="fixed_amount_discount">Fixed Amount Discount</option>
                            
                    </x-form.selectbox>
                     <x-form.textbox type="date" labelName="Promotion Expiary Date" name="condition_exp_date" col="col-md-12" placeholder="Enter Promotion Expiary Date" required="required" />
                    <div class="col-md-12">
                        <h1>Discount Conditions</h1>
                    </div>
                    <x-form.textbox labelName="Discount Amount" name="discount_amount" id="discount_amount" col="col-md-12" placeholder="Enter Discount Amount" style="display: none;" />
                    <x-form.textbox labelName="Minimum Spend" name="min_spend" id="min_spend" col="col-md-12" placeholder="Minimum Spend"/>
                    <x-form.textbox labelName="Maximum Spend" name="max_spend" id="max_spend" col="col-md-12" placeholder="Maximum Spend"/>
                    <div class="col-md-12">
                        <label for="customer_group" class="form-label">Customer Group</label>
                        <select name="customer_group[]" id="customer_group" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                            @if (!$customer_group->isEmpty())
                                @foreach ($customer_group as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->group_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    <div class="col-md-12 mt-3">
                        <label for="district" class="form-label">District</label>
                            <select class="form-control selectpicker" data-actions-box="true" data-live-search="true" name="district_id[]" id="district_id" multiple>
                          <!-- Empty option added -->
                            @foreach($districts as $dc)
                                <option value="{{$dc->id}}">{{$dc->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="form-group col-md-12 mt-3">
                        <label for="name">Upazilla</label>
                            <select class="form-control" data-actions-box="true" data-live-search="true" name="upazila_id[]" id="upazila_id" multiple> 
                                <option value="">Select Upazilla </option>
                        </select>
                    </div> --}}
                    <div class="form-group col-md-12 mt-3">
                        <label for="name">Upazilla</label>
                            <select class="form-control selectpicker" data-actions-box="true" data-live-search="true" name="upazila_id[]" id="upazila_id" multiple> 
                            @foreach($upazilas as $uz)
                                <option value="{{$uz->id}}">{{$uz->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check col-md-12 ml-5">
                            <input class="form-check-input" type="checkbox" value="1" id="is_exclude_sale"
                            name="is_exclude_sale">
                            <label class="form-check-label" for="flexCheckDefault">
                                Exclude Sale Items
                            </label>
                    </div>

                
                </div>
            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>
