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
                @if (permission('coupon-add'))
                <button class="btn btn-primary btn-sm" onclick="showStoreFormModal('Add New Coupon','Save')">
                    <i class="fas fa-plus-square"></i> Add New
                 </button>
                @endif


            </div>
            <!-- /entry header -->

            <!-- Card -->
            <div class="dt-card">

                <!-- Card Body -->
                <div class="dt-card__body">

                    <form id="form-filter">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="name">Coupon Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter coupon name">
                            </div>
                            <div class="form-group col-md-12">
                               <button type="button" class="btn btn-danger btn-sm float-right" id="btn-reset"
                               data-toggle="tooltip" data-placement="top" data-original-title="Reset Data">
                                   <i class="fas fa-redo-alt"></i>
                                </button>
                               <button type="button" class="btn btn-primary btn-sm float-right mr-2" id="btn-filter"
                               data-toggle="tooltip" data-placement="top" data-original-title="Filter Data">
                                   <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    <table id="dataTable" class="table table-striped table-bordered table-hover">
                        <thead class="bg-primary">
                            <tr>
                                @if (permission('coupon-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Sl</th>
                                <th>Coupon Code</th>
                                <th>Discount Type</th>
                                <th>Discount Amount</th>
                                <th>Expiary Date</th>
                                <th>Status</th>
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
        <!-- /grid item -->

    </div>
    <!-- /grid -->

</div>
@include('coupon::modal')
@endsection

@push('script')
<script src="js/spartan-multi-image-picker-min.js"></script>
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
        "pageLength": 25, //number of data show per page
        "language": {
            processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
            emptyTable: '<strong class="text-danger">No Data Found</strong>',
            infoEmpty: '',
            zeroRecords: '<strong class="text-danger">No Data Found</strong>'
        },
        "ajax": {
            "url": "{{route('coupon.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name        = $("#form-filter #name").val();
                data._token      = _token;
            }
        },
      
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('coupon-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "coupon List",
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
                "title": "coupon List",
                "filename": "coupon-list",
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
                "title": "coupon List",
                "filename": "coupon-list",
                "exportOptions": {
                    columns: function (index, data, node) {
                        return table.column(index).visible();
                    }
                }
            },
       
            @endif
            @if (permission('coupon-bulk-delete'))
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
        $('#form-filter .selectpicker').selectpicker('refresh');
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('coupon.store.or.update')}}";
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
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (data.status == false) {
                $.each(data.errors, function (key, value) {
                    $('#store_or_update_form input#' + key).addClass('is-invalid');
                    $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                    $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                    if(key == 'code'){
                        $('#store_or_update_form #' + key).parents('.form-group').append(
                        '<small class="error text-danger">' + value + '</small>');
                    }else{
                        $('#store_or_update_form #' + key).parent().append(
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
                    $('#store_or_update_modal').modal('hide');
                }
            }

        },
        error: function (xhr, ajaxOption, thrownError) {
            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
        }
    });
    });

        $(document).on('click', '.edit_data', function () {
        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        if (id) {
            $.ajax({
                url: "{{route('coupon.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {
                    $('#store_or_update_form #update_id').val(data.id);
                    $('#store_or_update_form #coupon_code').val(data.coupon_code);
                    $('#store_or_update_form #coupon_description').val(data.coupon_description);
                    $('#store_or_update_form #coupon_discount_type').val(data.coupon_discount_type);
                    $('#store_or_update_form #coupon_amount').val(data.coupon_amount);
                    $('#store_or_update_form #coupon_exp_date').val(data.coupon_exp_date);
                    $('#store_or_update_form #is_free_delivery').prop('checked', data.is_free_delivery == '1').change(); // Assuming string value.change(); // Trigger UI updates
                    $('#store_or_update_form #coupon_min_spend').val(data.coupon_min_spend);

                    $('#store_or_update_form #coupon_max_spend').val(data.coupon_max_spend);
                    // $('#store_or_update_form #product_id').val(data.product_id);
                   
                     $('#store_or_update_form #is_individual').prop('checked', data.is_individual ==='1').change();
                     $('#store_or_update_form #is_exclude_sale').prop('checked', data.is_exclude_sale ==='1').change();
                    $('#store_or_update_form #limit_per_coupon').val(data.limit_per_coupon);
                    $('#store_or_update_form #limit_usage_times').val(data.limit_usage_times);
                    $('#store_or_update_form #limit_per_user').val(data.limit_per_user);

          

    // Set the selected values
                    $('#store_or_update_form #product_id').val(data.product_id);
                    $('#store_or_update_form #coupon_discount_type').val(data.coupon_discount_type);
                    $('#store_or_update_form #exclude_id').val(data.exclude_id);
                    $('#store_or_update_form #category_id').val(data.category_id);
                    $('#store_or_update_form #exclude_category_id').val(data.exclude_category_id);
                    $('#store_or_update_form #customer_id').val(data.customer_id);
                    $('#store_or_update_form #include_customer_id').val(data.include_customer_id);
                    $('#store_or_update_form #combo_id').val(data.combo_id);
                    $('#store_or_update_form #exclude_combo_id').val(data.exclude_combo_id);

                    //   const is_free_delivery = document.getElementById('is_free_delivery');
                    //   const is_individual = document.getElementById('is_individual');
                    //   const is_exclude_sale = document.getElementById('is_exclude_sale');
                    //         if(data.is_free_delivery==1){
                    //             is_free_delivery.checked= !is_free_delivery.checked;
                    //         }
                    //         if(data.is_individual==1){
                    //             is_individual.checked= !is_individual.checked;
                    //         }
                    //         if(data.is_exclude_sale==1){
                    //             is_exclude_sale.checked= !is_exclude_sale.checked;
                    //         }
                    $('#store_or_update_form #product_id').selectpicker('refresh');
                    $('#store_or_update_form #coupon_discount_type').selectpicker('refresh');
                    $('#store_or_update_form #exclude_id').selectpicker('refresh');
                    $('#store_or_update_form #category_id').selectpicker('refresh');
                    $('#store_or_update_form #exclude_category_id').selectpicker('refresh');
                    $('#store_or_update_form #customer_id').selectpicker('refresh');
                    $('#store_or_update_form #include_customer_id').selectpicker('refresh');

                    $('#store_or_update_form #combo_id').selectpicker('refresh');
                    $('#store_or_update_form #exclude_combo_id').selectpicker('refresh');

                   


                    $('#store_or_update_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#store_or_update_modal .modal-title').html(
                        '<i class="fas fa-edit"></i> <span>Edit ' + data.coupon_code + '</span>');
                    $('#store_or_update_modal #save-btn').text('Update');

                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        }
    });

  

    $(document).on('click', '.view_data', function () {
        let id = $(this).data('id');
        if (id) {
            $.ajax({
                url: "{{route('coupon.show')}}",
                type: "POST",
                data: { id: id,_token: _token},
                success: function (data) {

                    $('#view_modal .details').html();
                    $('#view_modal .details').html(data);

                    $('#view_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#view_modal .modal-title').html(
                        '<i class="fas fa-eye"></i> <span>Supplier Details</span>');
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
        let url   = "{{ route('coupon.delete') }}";
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
            let url = "{{route('coupon.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $(document).on('click', '.change_status', function () {
        let id    = $(this).data('id');
        let status = $(this).data('status');
        let name  = $(this).data('name');
        let row   = table.row($(this).parent('tr'));
        let url   = "{{ route('coupon.change.status') }}";
        change_status(id,status,name,table,url);

    });





    $('.remove-files').on('click', function(){
        $(this).parents('.col-md-12').remove();
    });



});






function showStoreFormModal(modal_title, btn_text)
{
    $('#store_or_update_form')[0].reset();
    $('#store_or_update_form #update_id').val('');
    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
    $('#store_or_update_form').find('.error').remove();

    $('.selectpicker').selectpicker('refresh');
    $('#store_or_update_modal').modal({
        keyboard: false,
        backdrop: 'static',
    });
    $('#store_or_update_modal .modal-title').html('<i class="fas fa-plus-square"></i> '+modal_title);
    $('#store_or_update_modal #save-btn').text(btn_text);
}
</script>
@endpush
