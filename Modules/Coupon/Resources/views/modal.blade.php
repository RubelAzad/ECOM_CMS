<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1"
    aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

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

                        <input type="hidden" name="update_id" id="update_id" />

                        <div class="col-md-12">
                            <div class="row">
                               <div class="col-md-12">
                                    <h1>Coupon</h1>
                               </div>
                              <x-form.textbox labelName="Coupon Code" name="coupon_code" required="required" col="col-md-6"
                                placeholder="Enter Coupon Code" />
                                <x-form.textbox labelName="Coupon Description" name="coupon_description" col="col-md-6"  placeholder="Enter Coupon Description" />
                                 <x-form.selectbox labelName="Discount Type" name="coupon_discount_type" col="col-md-6" class="selectpicker" required="required">
                                    <option value="fixed_amount_discount">Fixed Amount Discount</option>
                                    <option value="percentage_discount">Percentage Discount</option>
                                    <option value="fixed_product_discount">Fixed Product Discount</option>
                            
                                </x-form.selectbox>
                                <x-form.textbox  labelName="Coupon Amount" name="coupon_amount" col="col-md-6" placeholder="Enter Coupon Amount" required="required" />
                                <x-form.textbox type="date" labelName="Coupon Expiary Date" name="coupon_exp_date" col="col-md-6" placeholder="Enter Coupon Expiary Date" required="required" />

                               <div class="form-check col-md-6 ml-4 mb-3">
                                    <input class="form-check-input" type="checkbox" value="1" id="is_free_delivery"
                                    name="is_free_delivery">
                                    <label class="form-check-label" for="flexCheckDefault">
                                       Allow Free Delivery
                                    </label>
                                </div>

                                

                               <div class="col-md-12 mt-3">
                                    <h1>Usage Restriction</h1>
                               </div>

                                <x-form.textbox labelName="Min Spend" name="coupon_min_spend" col="col-md-6" placeholder="Enter Min Spend" />
                                <x-form.textbox labelName="Max Spend" name="coupon_max_spend" col="col-md-6" placeholder="Enter Max Spend" />


                               <div class="col-md-6">
                                    <label for="product_id" class="form-label">Products</label>
                                    <select name="product_id[]" id="product_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                        @if (!$products->isEmpty())
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                               </div>

                                <div class="col-md-6">
                                    <label for="exclude_id" class="form-label">Exclude Products</label>
                                    <select name="exclude_id[]" id="exclude_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                        @if (!$products->isEmpty())
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                               </div>

                                <div class="col-md-6 mt-5">
                                    <label for="category_id" class="form-label">Categories</label>
                                    <select name="category_id[]" id="category_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                        @if (!$categories->isEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                               </div>

                                <div class="col-md-6 mt-5">
                                    <label for="exclude_category_id" class="form-label">Exclude Categories</label>
                                    <select name="exclude_category_id[]" id="exclude_category_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                        @if (!$categories->isEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                               </div>
                                <div class="col-md-6 mt-5">
                                    <label for="combo_id" class="form-label">Include Combo</label>
                                    <select name="combo_id[]" id="combo_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                        @if (!$combos->isEmpty())
                                            @foreach ($combos as $combo)
                                                <option value="{{ $combo->id }}">{{ $combo->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                               </div>

                                <div class="col-md-6 mt-5">
                                    <label for="exclude_combo_id" class="form-label">Exclude Combo</label>
                                    <select name="exclude_combo_id[]" id="exclude_combo_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                        @if (!$combos->isEmpty())
                                            @foreach ($combos as $combo)
                                                <option value="{{ $combo->id }}">{{ $combo->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                               </div>


                             
                            <div class="col-md-6 mt-5 mb-5">
                                <label for="customer_id" class="form-label">User Group</label>
                                <select name="customer_id[]" id="customer_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple>
                                    @if (!$customer_group->isEmpty())
                                        @foreach ($customer_group as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->group_name }}</option>

                                        @endforeach
                                    @endif
                                </select>
                            </div>


                                <div class="col-md-6 mt-5">
                                    <label for="include_customer_id" class="form-label">Allowed Individual User</label>
                                    <select name="include_customer_id[]" id="include_customer_id" data-live-search="true"
                                    data-actions-box="true" class="form-control selectpicker" multiple>
                                        {{-- <option value="" disabled selected>Select Any</option> --}}
                                        @if (!$customers->isEmpty())
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                               

                                 

                              <div class="row">
                                {{-- <div class="col-md-6">
                                    <div class="form-check ml-4 mb-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_individual" name="is_individual">
                                        <label class="form-check-label" for="is_individual">
                                            Individual Use Only
                                        </label>
                                    </div>
                                </div> --}}
                                {{-- <div class="col-md-6">
                                    <div class="form-check ml-4 mb-3">
                                        <input class="form-check-input" type="checkbox" value="1" id="is_exclude_sale" name="is_exclude_sale">
                                        <label class="form-check-label" for="is_exclude_sale">
                                            Exclude Sale Items
                                        </label>
                                    </div>
                                </div> --}}
                              </div>
                              <div class="col-md-6">
                                   
                              </div>

                               <div class="col-md-12 mt-3">
                                    <h1>Usage Limit</h1>
                               </div>




                                <x-form.textbox labelName="Usage Limit Per Coupon" name="limit_per_coupon" col="col-md-6" placeholder="Enter Usage Limit Per Coupon" />

                                {{-- <x-form.textbox labelName="Limit Usage Times" name="limit_usage_times" col="col-md-6" placeholder="Enter Limit Usage Times" />

                                <x-form.textbox labelName="Limit Per User" name="limit_per_user" col="col-md-6" placeholder="Enter Limit Per User" /> --}}

                               
                            
                               
                       
                            </div>
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
