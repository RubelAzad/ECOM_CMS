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
                <x-form.textbox labelName="Vendor Name" name="name" required="required" col="col-md-6" placeholder="Enter customer name"/>
                <x-form.textbox labelName="Vendor Email" name="email" required="required" col="col-md-6" placeholder="Enter Email"/>
                <x-form.textbox labelName="Vendor Address" name="address" required="required" col="col-md-12" placeholder="Enter address"/>
                <x-form.textbox labelName="New Password" name="password" type="password" required="required" col="col-md-6" placeholder="Enter New Password"/>
                <x-form.textbox labelName="Phone" name="phone_number" required="required" col="col-md-6" placeholder="Enter Phone"/>
                <x-form.textbox labelName="Date Of Birth" type="date" name="date_of_birth"  col="col-md-6" placeholder="Enter Date Of Birth"/>
                 <div class="form-group col-md-6">
                    <label for="gender">Select Gender</label>
                <select name="gender" id="gender" class="form-control selectpicker"  data-live-search="true" >
                                <option value=""> Select Please</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                  
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
