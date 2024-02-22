<div class="modal fade" id="ecourier_store_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
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
        <form id="ecourier_store_or_update_form" method="post">
          @csrf
            <!-- Modal Body -->
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center border p-2 mb-4">IFAD Info</h3>
                    </div>
                </div>
                <div class="row">
                    <input type="hidden" name="update_id" id="update_id"/>
                    <div class="form-group col-md-12 required">
                        <label for="ep_name" class="text-warning">eCommerce Partner</label>
                        <select name="ep_name" id="ep_name" required="" onchange="ecourier_partner_info(this.value)" class="form-control " data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            @foreach($ecourier_data as $ecourier)
                                <option value="">Select Please</option>
                                <option value="{{$ecourier->id}}">{{$ecourier->ep_name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" required="" name="ep_id" id="ep_id" class="form-control" value="" placeholder="Enter ep id">
                        <input type="hidden" required="" name="order_id" id="order_id" class="form-control"  placeholder="Enter order id">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 required">
                        <label for="pick_contact_person">Pick Contact Person</label>
                        <input type="text" readonly="" required="" name="pick_contact_person" id="pick_contact_person" class="form-control" value="" placeholder="Enter Pick Contact Person">
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="pick_district">Pick District</label>
                        <select name="pick_district" readonly="" required="" id="pick_district" class="form-control " data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="pick_thana">Pick Thana</label>
                        <select name="pick_thana" readonly="" required="" id="pick_thana" class="form-control " data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 required">
                        <label for="pick_hub" class="text-warning">Pick Hub/Branch</label>
                        <select name="pick_hub" id="pick_hub" required="" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                            @foreach($branches as $branche)
                                <option value="{{$branche->value}}">{{$branche->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="pick_union">Pick Union</label>
                        <input type="text" readonly="" name="pick_union" id="pick_union" class="form-control" value="N/A" placeholder="Enter Pick Union">
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="pick_mobile">Pick Person Contact Number</label>
                        <input type="number" readonly="" required="" name="pick_mobile" id="pick_mobile" class="form-control" value="" placeholder="Enter eCommerce Partner">
                    </div>
                    <div class="form-group col-md-12 required">
                        <label for="pick_address">Pick Address</label>
                        <textarea name="pick_address" readonly="" id="pick_address" class="form-control " placeholder="Enter Pick Address"></textarea>
                    </div>
                </div>

{{--                Customer info--}}
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="text-center border p-2 mb-4">Customer Info</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label for="recipient_name">Parcel Receiver’s Name</label>
                        <input type="text" name="recipient_name" required="" id="recipient_name" class="form-control" value="" placeholder="Enter Recipient Name">
                    </div>
                    <div class="form-group col-md-6 required">
                        <label for="recipient_mobile">Parcel Receiver’s Number</label>
                        <input type="text" name="recipient_mobile" required="" id="recipient_mobile" class="form-control" value="" placeholder="Enter Parcel Receiver’s Number">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4  required">
                        <label for="recipient_district">Parcel Receiver’s District</label>
                        <select name="recipient_district" required="" id="recipient_district" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                            @foreach($districts as $district)
                                <option value="{{$district->id}}">{{$district->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="recipient_city">Parcel Receiver’s City</label>
                        <select name="recipient_cityid" required="" id="recipient_cityid" onchange="getThanaOrUpozila(this.value,this.options[this.selectedIndex].text)" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                            @foreach($districts as $district)
                                <option value="{{$district->id}}">{{$district->name}}</option>
                            @endforeach
                        </select>
                        <input type="hidden" required="" name="recipient_city" id="recipient_city" class="form-control selectpicker" placeholder="Enter recipient city">
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="recipient_thana">Parcel Receiver’s Thana</label>
                        <select name="recipient_thana" required="" id="recipient_thana" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-4 required">
                        <label for="recipient_area">Parcel Receiver’s Area</label>
                        <input type="text" name="recipient_area" required="" id="recipient_area" class="form-control" value="" placeholder="Enter Parcel Receiver’s Area">
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="recipient_union">Parcel Receiver’s Union</label>
                        <input type="text" name="recipient_union" required="" id="recipient_union" class="form-control" value="" placeholder="Enter Pick Union">
                    </div>
                    <div class="form-group col-md-4 required">
                        <label for="package_code" class="text-warning">Package code</label>
                        <select name="package_code" id="package_code" required="" class="form-control selectpicker" data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                            @foreach($packages as $package)
                                <option value='{{$package->package_code}}'>{{$package->package_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <x-form.textarea labelName="Parcel Receiver Address" required="required" name="recipient_address" col="col-md-6" placeholder="Enter Parcel Receiver Address" />
                    <x-form.textarea labelName="Parcel Details" required="required" name="parcel_detail" col="col-md-6" placeholder="Enter Parcel Details" />
                </div>

                <div class="row">
                    <div class="form-group col-md-6 required">
                        <label for="number_of_item">Number of Item</label>
                        <input type="number" name="number_of_item" required="" id="number_of_item" class="form-control" value="" placeholder="Enter Total quantity">
                    </div>
                    <div class="form-group col-md-6 required">
                        <label for="product_price">Receive Amount from Parcel Receiver</label>
                        <input type="number" name="product_price" required="" id="product_price" class="form-control" value="" placeholder="Enter Receive Amount from Parcel Receiver">
                    </div>
                </div>

                <div class="row">
                    <div class="form-group col-md-6  required">
                        <label for="actual_product_price">Parcel Product Actual Price</label>
                        <input type="number" name="actual_product_price" required="" id="actual_product_price" class="form-control" value="" placeholder="Enter Parcel Product Actual Price">
                    </div>
                    <div class="form-group col-md-6 required">
                        <label for="payment_method">Payment Method</label>
                        <select name="payment_method" readonly="" id="payment_method" class="form-control " data-live-search="true" data-live-search-placeholder="Search" title="Choose one of the following">
                            <option value="">Select Please</option>
                            @foreach($payment_methods as $payment_method)
                                <option value="{{$payment_method->id}}">{{$payment_method->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            <!-- /modal body -->

            <!-- Modal Footer -->
            <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary btn-sm" id="save-btn-ecourier"></button>
            </div>
            <!-- /modal footer -->
        </form>
      </div>
      <!-- /modal content -->

    </div>
  </div>
