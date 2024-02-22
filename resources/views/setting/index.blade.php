@extends('layouts.app')

@section('title')
    {{ $page_title }}
@endsection

@push('stylesheet')

@endpush

@section('content')
<div class="dt-content">

    <!-- Grid -->
    <div class="row">
        <div class="col-xl-12 pb-3">
            <ol class="breadcrumb bg-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Dashboard</a></li>
                <li class="active breadcrumb-item">{{ $sub_title }}</li>
              </ol>
        </div>
        <!-- Grid Item -->
        <div class="col-xl-12">

            <!-- Entry Header -->
            <div class="dt-entry__header">

                <!-- Entry Heading -->
                <div class="dt-entry__heading">
                    <h2 class="dt-page__title mb-0 text-primary"><i class="{{ $page_icon }}"></i> {{ $sub_title }}</h2>
                </div>
                <!-- /entry heading -->

            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">
                    <div class="dt-card__body tabs-container tabs-vertical">

                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs flex-column" role="tablist">
                          <li class="nav-item">
                            <div class="nav-link active" data-toggle="tab" href="#general-setting"
                            role="tab" aria-controls="general-setting" aria-selected="true">General Setting
                            </div>
                          </li>
                          <li class="nav-item">
                            <div class="nav-link" data-toggle="tab" href="#ecourier-setup"
                            role="tab" aria-controls="ecourier-setup" aria-selected="true">eCourier Setup
                            </div>
                          </li>
                        </ul>
                        <!-- /tab navigation -->

                        <!-- Tab Content -->
                        <div class="tab-content">

                          <!-- Tab Pane -->
                          <div id="general-setting" class="tab-pane active">
                            <div class="card-body">
                                <form id="general-form" class="col-md-12" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <x-form.textbox labelName="Company Name" name="title" required="required" value="{{ config('settings.title') }}"
                                        col="col-md-8" placeholder="Enter title"/>
                                        <x-form.textarea labelName="Company Address" name="address" required="required" value="{{ config('settings.address') }}"
                                        col="col-md-8" placeholder="Enter address"/>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="form-group col-md-6 required">
                                                    <label for="logo">Logo</label>
                                                    <div class="col-md-12 px-0 text-center">
                                                        <div id="logo">

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="old_logo" id="old_logo" value="{{ config('settings.logo') }}">
                                                </div>
                                                <div class="form-group col-md-6 required">
                                                    <label for="logo">Favicon</label>
                                                    <div class="col-md-12 px-0 text-center">
                                                        <div id="favicon">

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="old_favicon" id="old_favicon" value="{{ config('settings.favicon') }}">
                                                </div>
                                                <div class="form-group col-md-6 required">
                                                    <label for="footer">Footer Logo</label>
                                                    <div class="col-md-12 px-0 text-center">
                                                        <div id="footerlogo">

                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="old_footer" id="old_footer" value="{{ config('settings.footerlogo') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <x-form.textbox labelName="Copyright" name="copyright" required="required" value="{{ config('settings.copyright') }}"
                                        col="col-md-8" placeholder="Enter copyright text"/>

                                        <x-form.textbox labelName="Email" name="email" required="required" value="{{ config('settings.email') }}"
                                        col="col-md-8" placeholder="Enter Company Email"/>
                                        <x-form.textbox labelName="Phone" name="phone" required="required" value="{{ config('settings.phone') }}"
                                        col="col-md-8" placeholder="Enter Company Phone"/>
                                        <x-form.textbox labelName="Hot Number" name="hotnumber" required="required" value="{{ config('settings.hotnumber') }}"
                                        col="col-md-8" placeholder="Enter Company Hot Number"/>

                                        <!-- <div class="form-group col-md-8">
                                            <label for="">Currency Position</label><br>
                                            <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="prefix" name="currency_position" value="prefix" class="custom-control-input"
                                                    {{ config('settings.currency_position') == 'prefix' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="prefix">prefix</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                <input type="radio" id="suffix" name="currency_position" value="suffix" class="custom-control-input"
                                                {{ config('settings.currency_position') == 'suffix' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="suffix">Suffix</label>
                                            </div>
                                        </div> -->

                                    </div>

                                    <div class="form-group col-md-12">
                                        <button type="reset" class="btn btn-danger btn-sm">Reset</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="general-save-btn" onclick="save_data('general')">Save</button>
                                    </div>
                                </form>
                            </div>
                          </div>
                          <!-- /tab pane-->

                            <!-- Tab Pane eCourier Setup-->
                          <div id="ecourier-setup" class="tab-pane">
                            <div class="card-body">
                                <form id="ecourier-form" class="col-md-12" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <input type="hidden" name="update_id" id="update_id"/>
                                        <x-form.textbox labelName="eCommerce Partner" name="ep_name" required="required" value=""
                                        col="col-md-6" placeholder="Enter ecommerce partner"/>

                                        <x-form.textbox labelName="Pick Contact Person" name="pick_contact_person" required="required" value=""
                                        col="col-md-6" placeholder="Enter pick contact person"/>

{{--                                        <x-form.textbox labelName="Pick District" name="pick_district" required="required" value=""--}}
{{--                                        col="col-md-6" placeholder="Enter pick district"/>--}}

                                        <div class="form-group col-md-6">
                                            <label for="district_id">Pick District</label>
                                            <select name="pick_district" id="pick_district" class="form-control selectpicker" data-live-search="true" >
                                                <option value=""> Select Please</option>
                                                @foreach ($districts as $district)
                                                    <option value="{{ $district->id }}">{{ $district->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

{{--                                        <x-form.textbox labelName="Pick Thana" name="pick_thana" required="required" value=""--}}
{{--                                        col="col-md-6" placeholder="Enter pick thana"/>--}}

                                        <div class="col-md-6">
                                            <label for="name">Pick Upazila/ Thana</label>
                                            <select name="pick_thana" id="pick_thana" class="form-control selectpicker" data-live-search="true" tabindex="null">
                                                <option value=""> Select Please</option>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="pick_hub">Pick Branch</label>
                                            <select name="pick_hub" id="pick_hub" class="form-control selectpicker" data-live-search="true" >
                                                <option value=""> Select Please</option>
                                                @foreach ($branches as $branche)
                                                    <option value="{{ $branche->value }}">{{ $branche->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <x-form.textbox labelName="Pick Union" name="pick_union" required="required" value=""
                                        col="col-md-6" placeholder="Enter pick union"/>

                                        <x-form.textbox type="number" labelName="Pick Person Contact Number" required="required" name="pick_mobile" col="col-md-6" placeholder="Enter pick person contact number" />
                                        <x-form.textarea labelName="Pick Address" required="required" name="pick_address" col="col-md-6" placeholder="Enter Pick Address" />

                                    </div>

                                    <div class="form-group col-md-12">
                                        <button type="reset" id="btn-reset" class="btn btn-danger btn-sm">Reset</button>
                                        <button type="button" class="btn btn-primary btn-sm" id="save-btn">Save</button>
                                    </div>
                                </form>


{{--                                eCourier setup--}}

                            <!-- Card -->
                                <div class="dt-card">

                                    <!-- Card Body -->
                                    <div class="dt-card__body">

                                        <form id="form-filter">

                                        </form>
                                        <table id="dataTable" class="table table-striped table-bordered table-hover">
                                            <thead class="bg-primary">
                                            <tr>
{{--                                                @if (permission('ecourier-bulk-delete'))--}}
                                                    <th>
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                                            <label class="custom-control-label" for="select_all"></label>
                                                        </div>
                                                    </th>
{{--                                                @endif--}}
                                                <th>Sl</th>
                                                <th>Title</th>
                                                <th>Pick Contact Person</th>
                                                <th>Pick Mobile</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody></tbody>
                                        </table>

                                    </div>
                                    <!-- /card body -->

                                </div>
                                <!-- /card -->

                            </div>
                          </div>
                          <!-- /tab pane-->



                        </div>
                        <!-- /tab content -->

                      </div>
                </div>
                <!-- /card body -->

            </div>
            <!-- /card -->

        </div>
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
<script>
$(document).ready(function(){
    $('#logo').spartanMultiImagePicker({
        fieldName: 'logo',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png|jpg|jpeg',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png, jpg and jpeg file format allowed!'});
        }
    });
    $('#favicon').spartanMultiImagePicker({
        fieldName: 'favicon',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png file format allowed!'});
        }
    });
    $('#footerlogo').spartanMultiImagePicker({
        fieldName: 'footerlogo',
        maxCount: 1,
        rowHeight: '200px',
        groupClassName: 'col-md-12 com-sm-12 com-xs-12',
        maxFileSize: '',
        dropFileLabel: 'Drop Here',
        allowExt: 'png',
        onExtensionErr: function(index, file){
            Swal.fire({icon:'error',title:'Oops...',text: 'Only png file format allowed!'});
        }
    });

    $('input[name="logo"],input[name="favicon"],input[name="footerlogo"]').prop('required',true);

    $('.remove-files').on('click', function(){
        $(this).parents('.col-md-12').remove();
    });

    @if(config('settings.logo'))
    $('#logo img.spartan_image_placeholder').css('display','none');
    $('#logo .spartan_remove_row').css('display','none');
    $('#logo .img_').css('display','block');
    $('#logo .img_').attr('src','{{ asset("storage/".LOGO_PATH.config("settings.logo")) }}');
    @endif

    @if(config('settings.favicon'))
    $('#favicon img.spartan_image_placeholder').css('display','none');
    $('#favicon .spartan_remove_row').css('display','none');
    $('#favicon .img_').css('display','block');
    $('#favicon .img_').attr('src','{{ asset("storage/".LOGO_PATH.config("settings.favicon")) }}');
    @endif

    @if(config('settings.footerlogo'))
    $('#footerlogo img.spartan_image_placeholder').css('display','none');
    $('#footerlogo .spartan_remove_row').css('display','none');
    $('#footerlogo .img_').css('display','block');
    $('#footerlogo .img_').attr('src','{{ asset("storage/".LOGO_PATH.config("settings.footerlogo")) }}');
    @endif

});

function save_data(form_id) {
    let form = document.getElementById(form_id+'-form');
    let formData = new FormData(form);
    let url;
    if(form_id == 'general'){
        url = "{{ route('general.setting') }}";
    }else{
        url = "{{ route('mail.setting') }}";
    }
    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function(){
            $('#'+form_id+'-save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        complete: function(){
            $('#'+form_id+'-save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
        },
        success: function (data) {
            $('#'+form_id+'-form').find('.is-invalid').removeClass('is-invalid');
            $('#'+form_id+'-form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#'+form_id+'-form input#' + key).addClass('is-invalid');
                    $('#'+form_id+'-form textarea#' + key).addClass('is-invalid');
                    $('#'+form_id+'-form select#' + key).parent().addClass('is-invalid');
                $('#'+form_id+'-form #' + key).parent().append(
                    '<small class="error text-danger">' + value + '</small>');
                });
            } else {
                notification(data.status, data.message);
            }
        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
}

//get upozilla/thana by district
$('#pick_district').change(function () {

    var dcId = $(this).val();
    console.log(dcId);

    if (dcId) {

        $.ajax({
            type: 'GET',
            url: '{{ route("get-upazillas") }}',
            data:{
                dcId:dcId
            },
            success: function (data) {
                console.log(data)

                // Add the default empty option
                $('#pick_thana').html('<option value="">Select State</option>');
                $.each(data, function (key, value) {
                    console.log('gg')
                    $("#pick_thana").append('<option value="'+ value.id+'">' + value.name +'</option>');
                    // $("#up_id").append('<option value="' + value.id + '" class="selectpicker">' + value.name + '</option>');
                });
                $("#pick_thana").addClass("selectpicker");
                $("#pick_thana").selectpicker('refresh');
            },error: function(error) {
                // Handle errors here
                console.log(error);
            }
        });
    } else {
        $('#pick_thana').empty();
        $('#pick_thana').append($('<option>', {
            value: '',
            text: 'Select Upazilla'
        }));
    }

});

</script>
{{--Datatable--}}
<script>
    var table;
    $(document).ready(function(){

        table = $('#dataTable').DataTable({
            "processing": true, //Feature control the processing indicator
            "serverSide": true, //Feature control DataTable server side processing mode
            "order": [], //Initial no order
            "responsive": true, //Make table responsive in mobile device
            "bInfo": true, //TO show the total number of data
            "bFilter": false, //For datatable default search box show/hide
            "lengthMenu": [
                [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
            ],
            "pageLength": 10, //number of data show per page
            "language": {
                processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                emptyTable: '<strong class="text-danger">No Data Found</strong>',
                infoEmpty: '',
                zeroRecords: '<strong class="text-danger">No Data Found</strong>'
            },
            "ajax": {
                "url": "{{route('ecourier.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.name        = $("#form-filter #name").val();
                    data._token      = _token;
                }
            },
            "columnDefs": [{
                @if (permission('ecourier-bulk-delete'))
                "targets": [0,5],
                @else
                "targets": [4],
                @endif
                "orderable": false,
                "className": "text-center"
            },
                {
                    @if (permission('ecourier-bulk-delete'))
                    "targets": [1,2,3,4,5],
                    @else
                    "targets": [0,1,3,4,5],
                    @endif
                    "className": "text-center"
                },
                {
                    @if (permission('ecourier-bulk-delete'))
                    "targets": [4,5],
                    @else
                    "targets": [3,5],
                    @endif
                    "className": "text-right"
                }
            ],
            "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

            "buttons": [
                    @if (permission('ecourier-report'))
                {
                    'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
                },
                {
                    "extend": 'print',
                    'text':'Print',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": " Category List",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    },
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                    },
                },
                {
                    "extend": 'csv',
                    'text':'CSV',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": " Category List",
                    "filename": "ecourier-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'excel',
                    'text':'Excel',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": " Category List",
                    "filename": "ecourier-list",
                    "exportOptions": {
                        columns: function (index, data, node) {
                            return table.column(index).visible();
                        }
                    }
                },
                {
                    "extend": 'pdf',
                    'text':'PDF',
                    'className':'btn btn-secondary btn-sm text-white',
                    "title": " Category List",
                    "filename": "ecourier-list",
                    "orientation": "landscape", //portrait
                    "pageSize": "A4", //A3,A5,A6,legal,letter
                    "exportOptions": {
                        columns: [1, 2, 3, 4]
                    },
                },
                    @endif
                    @if (permission('ecourier-bulk-delete'))
                {
                    'className':'btn btn-danger btn-sm delete_btn d-none text-white',
                    'text':'Delete',
                    action:function(e,dt,node,config){
                        multi_delete();
                    }
                }
                @endif
            ],
        });

        $('#btn-filter').click(function () {
            table.ajax.reload();
        });

        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();

            //select option reset start
            // Get the <select> elements by their IDs
            var pick_district = document.getElementById("pick_district");
            var pick_thana = document.getElementById("pick_thana");
            var pick_hub = document.getElementById("pick_hub");

            // Reset the selected options to the default (no option selected)
            $('#pick_district').val(null).trigger('change');
            $('#pick_thana').val(null).trigger('change');
            $('#pick_hub').val(null).trigger('change');
            //select option reset end

            $('#form-filter .selectpicker').selectpicker('refresh');
            table.ajax.reload();
        });

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('ecourier-form');
            let formData = new FormData(form);
            let url = "{{route('ecourier.store.or.update')}}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function(){
                    $('#save-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                complete: function(){
                    $('#save-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                },
                success: function (data) {
                    //select option reset start
                    // Get the <select> elements by their IDs
                    var pick_district = document.getElementById("pick_district");
                    var pick_thana = document.getElementById("pick_thana");
                    var pick_hub = document.getElementById("pick_hub");

                    // Reset the selected options to the default (no option selected)
                    $('#pick_district').val(null).trigger('change');
                    $('#pick_thana').val(null).trigger('change');
                    $('#pick_hub').val(null).trigger('change');
                    //select option reset end

                    $('#ecourier-form')[0].reset();
                    $('#ecourier-form #save-btn').text('Save');
                    $('#ecourier-form #update_id').val('');
                    $('#ecourier-form').find('.is-invalid').removeClass('is-invalid');
                    $('#ecourier-form').find('.error').remove();
                    table.ajax.reload();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#ecourier-form input#' + key).addClass('is-invalid');
                            $('#ecourier-form textarea#' + key).addClass('is-invalid');
                            $('#ecourier-form select#' + key).parent().addClass('is-invalid');
                            if(key == 'code'){
                                $('#ecourier-form #' + key).parents('.form-group').append(
                                    '<small class="error text-danger">' + value + '</small>');
                            }else{
                                $('#ecourier-form #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>');
                            }
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            if (method == 'update') {
                                table.ajax.reload(null, false);
                            } else {
                                table.ajax.reload();
                            }

                        }
                    }
                },

                error: function(jqXHR, textStatus, errorThrown) { // What to do if we fail
                    // Parse the JSON data from jqXHR.responseText
                    var errors = $.parseJSON(jqXHR.responseText);
                    $('#ecourier-form .error').remove();

                    $.each(errors.errors, function (key, value) {
                        // Remove the "is-invalid" class from all fields
                        $('#ecourier-form input#' + key).removeClass('is-invalid');
                        $('#ecourier-form textarea#' + key).removeClass('is-invalid');
                        $('#ecourier-form select#' + key).parent().removeClass('is-invalid');

                        if (key == 'code') {
                            $('#ecourier-form #' + key).parents('.form-group').append(
                                '<small class="error text-danger">' + value + '</small>'
                            );
                        } else {
                            $('#ecourier-form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>'
                            );
                        }
                    });

                    // Remove "is-invalid" class from fields where there is no error data
                    $.each(errors, function (key, value) {
                        if (!value) {
                            $('#ecourier-form input#' + key).removeClass('is-invalid');
                            $('#ecourier-form textarea#' + key).removeClass('is-invalid');
                            $('#ecourier-form select#' + key).parent().removeClass('is-invalid');
                        }
                    });
                }

            });

        });

        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            $('#ecourier-form')[0].reset();
            $('#ecourier-form').find('.is-invalid').removeClass('is-invalid');
            $('#ecourier-form').find('.error').remove();
            $('#pick_thana').empty();
            if (id) {
                $.ajax({
                    url: "{{route('ecourier.edit')}}",
                    type: "POST",
                    data: { id: id,_token: _token},
                    dataType: "JSON",
                    success: function (data) {

                        var pick_thana ='';
                        data.get_upazila.map(function(upozilla,key){

                            if (parseInt(String(data.pick_thana).trim(), 10) === parseInt(String(upozilla.id).trim(), 10)) {

                                console.log('hi');
                                pick_thana += '<option selected value="'+upozilla.id+'">'+upozilla.name+'</option>';
                            }else{
                                pick_thana += '<option value="'+upozilla.id+'">'+upozilla.name+'</option>';
                            }

                        });
                        $('#pick_thana').append(pick_thana);

                        $('#update_id').val(data.id);
                        $('#ep_name').val(data.ep_name);
                        $('#pick_contact_person').val(data.pick_contact_person);
                        $('#pick_district').val(data.pick_district);
                        $('#pick_thana').val(data.pick_thana);
                        $('#pick_hub').val(data.pick_hub);
                        $('#pick_union').val(data.pick_union);
                        $('#pick_mobile').val(data.pick_mobile);
                        $('#pick_address').val(data.pick_address);
                        $('.selectpicker').selectpicker('refresh');
                        $('#ecourier-form #save-btn').text('Update');

                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            }
        });

        $(document).on('click', '.delete_data', function () {
            let id    = $(this).data('id');
            let name  = $(this).data('name');
            let row   = table.row($(this).parent('tr'));
            let url   = "{{ route('ecourier.delete') }}";
            delete_data(id, url, table, row, name);
        });

        function multi_delete(){
            let ids = [];
            let rows;
            $('.select_data:checked').each(function(){
                ids.push($(this).val());
                rows = table.rows($('.select_data:checked').parents('tr'));
            });
            if(ids.length == 0){
                Swal.fire({
                    type:'error',
                    title:'Error',
                    text:'Please checked at least one row of table!',
                    icon: 'warning',
                });
            }else{
                let url = "{{route('ecourier.bulk.delete')}}";
                bulk_delete(ids,url,table,rows);
            }
        }

    });
</script>

@endpush
