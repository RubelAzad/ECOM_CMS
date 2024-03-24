<div class="modal fade" id="store_or_update_modal" tabindex="-1" role="dialog" aria-labelledby="model-1" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">

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
                 <input type="hidden" name="update_id" id="update_id"/>
                <div class="row">
                <div class="form-group col-md-6">
                    <label for="gender">Voucher Type</label>
                    <select name="voucher_type" id="voucher_type" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Please</option>
                        <option value="Debit">Debit</option>
                        <option value="Credit">Credit</option>
                    </select>
                </div>
                <x-form.textbox labelName="Voucher No" name="voucher_no" required="required" col="col-md-6" placeholder="Enter Voucher No" id="voucher_no" />

                <div class="form-group col-md-6">
                    <label for="payment_type">Payment Type</label>
                    <select name="payment_type" id="payment_type" class="form-control selectpicker" data-live-search="true">
                        <option value="">Select Please</option>
                        <option value="cash">Cash In Hand</option>
                        <option value="online">Online Transaction</option>
                        <option value="bank">Bank Account</option>
                    </select>
                </div>

                <div class="col-md-6" id="cash_provider_name_container" style="display: none;">
                    <x-form.textbox name="cash_person_name" id="cash_person_name" labelName="Cash Provider Name" placeholder="Enter Cash Provider Name" />
                </div>

                <div class="col-md-6" id="online_mobile_container" style="display: none;">
                    <x-form.textbox name="online_mobile" id="online_mobile" labelName="Mobile Number" placeholder="Enter Mobile Number" />
                </div>

                <div class="col-md-6" id="online_transaction_number_container" style="display: none;">
                    <x-form.textbox name="online_transaction_number" id="online_transaction_number" labelName="Transaction Number" placeholder="Enter Transaction Number" />
                </div>

                <div class="col-md-6" id="bank_name_container" style="display: none;">
                    <x-form.textbox name="bank_name" id="bank_name" labelName="Bank Account Name" placeholder="Enter Bank Account Name" />
                </div>

                <div class="col-md-6" id="bank_account_container" style="display: none;">
                    <x-form.textbox name="bank_account" id="bank_account" labelName="Bank Account Number" placeholder="Enter Bank Account Number" />
                </div>

                <x-form.textbox labelName="Date" type="date" name="voucher_date"  col="col-md-6" placeholder="Enter Date"/>
                <x-form.textbox labelName="Invoice Number" name="invoice_id" col="col-md-6" placeholder="Enter Invoice Number"/>
                <x-form.selectbox labelName="Vendor Name" name="vendor_id" id="vendor_id" required="required" col="col-md-6" class="selectpicker">
                    @if (!$vendors->isEmpty())
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    @endif
                </x-form.selectbox>

                <x-form.textbox labelName="Wallet Amount" name="wallet_amount" id="wallet_amount" col="col-md-6" readonly/>
                <x-form.textbox labelName="Payment Amount" name="payment_amount" required="required" type="number" col="col-md-6" placeholder="Enter Payment Amount"/>



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
