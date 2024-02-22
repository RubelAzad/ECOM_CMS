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
                                    <h1>User Group</h1>
                               </div>
                              <x-form.textbox labelName="Group Name" name="group_name" required="required" col="col-md-6"
                                placeholder="Enter Group Name" />
                                <x-form.textbox labelName="Group Description" name="group_description" col="col-md-6"  placeholder="Enter Group Description" />


                                

                               <div class="col-md-12 mt-3">
                                    <h1>Group Members</h1>
                               </div>

                               <div class="col-md-6 mt-5 mb-5">
                                    <label for="customer_id" class="form-label">User Group<span style="color: red">*</span></label>
                                    <select name="customer_id[]" id="customer_id" data-live-search="true" data-actions-box="true" class="form-control selectpicker" multiple required>
                                        @if (!$customers->isEmpty())
                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>

                                            @endforeach
                                        @endif
                                    </select>
                                </div>

                                


                             
                            
                               

                                 

                      

                            
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
