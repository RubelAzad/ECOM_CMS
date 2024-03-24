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
                @if (permission('vtranscation-add'))
                    <button class="btn btn-primary btn-sm" onclick="showFormModal('Add New vtranscation','Save')">
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
                            <div class="form-group col-md-4">
                                <label for="name">vtranscation Name</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="Enter vtranscation Name">
                            </div>
                            <div class="form-group col-md-8 pt-24">
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
                                @if (permission('vtranscation-bulk-delete'))
                                <th>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="select_all" onchange="select_all()">
                                        <label class="custom-control-label" for="select_all"></label>
                                    </div>
                                </th>
                                @endif
                                <th>Id</th>
                                <th>Voucher Name</th>
				                <th>Voucher Type</th>
                                <th>Payment Type</th>
                                <th>Date</th>
                                <th>Mobile</th>
                                <th>Transaction Id</th>
                                <th>Bank Name</th>
                                <th>Bank Account</th>
                                <th>Payment Amount</th>
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
@include('vendor::vendor-transaction-modal')
{{-- @include('vtranscation::view_modal') --}}
@endsection

@push('script')
 <script src="js/spartan-multi-image-picker-min.js"></script>
<script>
var table;
$(document).ready(function(){

    $('#voucher_type').change(function() {
        var selectedType = $(this).val();
        var value = '';

        if (selectedType === 'Debit') {
            value = 'DV';
        } else if (selectedType === 'Credit') {
            value = 'CV';
        }

        $('#voucher_no').val(value);
    });

    $('#payment_type').change(function() {
        var selectedType = $(this).val();

        // Hide all input fields and their labels
        $('#cash_provider_name_container, #online_mobile_container, #online_transaction_number_container, #bank_name_container, #bank_account_container').hide();

        // Show input fields and their labels based on selected payment type
        if (selectedType === 'cash') {
            $('#cash_provider_name_container').show();
        } else if (selectedType === 'online') {
            $('#online_mobile_container, #online_transaction_number_container').show();
        } else if (selectedType === 'bank') {
            $('#bank_name_container, #bank_account_container').show();
        }
    });






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
            "url": "{{route('vtranscation.datatable.data')}}",
            "type": "POST",
            "data": function (data) {
                data.name = $("#form-filter #name").val();
                data._token    = _token;
            }
        },
        "columnDefs": [{
                @if (permission('vtranscation-bulk-delete'))
                "targets": [0,4],
                @else
                "targets": [3],
                @endif
                "orderable": false,
                "className": "text-center"
            },
            {
                @if (permission('vtranscation-bulk-delete'))
                "targets": [1,3],
                @else
                "targets": [0,2],
                @endif
                "className": "text-center"
            }
        ],
        "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

        "buttons": [
            @if (permission('vtranscation-report'))
            {
                'extend':'colvis','className':'btn btn-secondary btn-sm text-white','text':'Column'
            },
            {
                "extend": 'print',
                'text':'Print',
                'className':'btn btn-secondary btn-sm text-white',
                "title": "Content Type List",
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
                "title": "Content Type List",
                "filename": "vtranscation",
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
                "title": "Content Type List",
                "filename": "vtranscation",
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
                "title": "Content Type List",
                "filename": "vtranscation",
                "orientation": "landscape", //portrait
                "pageSize": "A4", //A3,A5,A6,legal,letter
                "exportOptions": {
                    columns: [1, 2, 3]
                },
            },
            @endif
            @if (permission('vtranscation-bulk-delete'))
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
        table.ajax.reload();
    });

    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('store_or_update_form');
        let formData = new FormData(form);
        let url = "{{route('vtranscation.store.or.update')}}";
        let id = $('#update_id').val();
        let method;
        if (id) {
            method = 'update';
        } else {
            method = 'add';
        }
        store_or_update_data(table, method, url, formData);
    });



    $(document).on('click', '.edit_data', function () {
        let id = $(this).data('id');
        $('#store_or_update_form')[0].reset();
        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
        $('#store_or_update_form').find('.error').remove();
        if (id) {
            $.ajax({
                url: "{{route('vtranscation.edit')}}",
                type: "POST",
                data: { id: id,_token: _token},
                dataType: "JSON",
                success: function (data) {
                    $('#store_or_update_form #update_id').val(data.vendor.id);
                    $('#store_or_update_form #voucher_type').val(data.vendor.voucher_type);
                    $('#store_or_update_form #voucher_no').val(data.vendor.voucher_no);
                    $('#store_or_update_form #payment_type').val(data.vendor.payment_type);
                    $('#store_or_update_form #cash_person_name').val(data.vendor.cash_person_name);
                    $('#store_or_update_form #online_mobile').val(data.vendor.online_mobile);
                    $('#store_or_update_form #online_transaction_number').val(data.vendor.online_transaction_number);
                    $('#store_or_update_form #bank_name').val(data.vendor.bank_name);
                    $('#store_or_update_form #bank_account').val(data.vendor.bank_account);
                    $('#store_or_update_form #voucher_date').val(data.vendor.voucher_date);
                    $('#store_or_update_form #vendor_id').val(data.vendor.vendor_id);
                    $('#store_or_update_form #invoice_id').val(data.vendor.invoice_id);
                    $('#store_or_update_form #wallet_amount').val(data.vendor.wallet_amount);
                    $('#store_or_update_form #payment_amount').val(data.vendor.payment_amount);
                    $('#store_or_update_form #remark').val(data.vendor.remark);
                     $('#store_or_update_form .selectpicker').selectpicker('refresh');


                     // Show corresponding input fields based on payment type
                    showPaymentFields(data.vendor.payment_type);

                    $('#store_or_update_modal').modal({
                        keyboard: false,
                        backdrop: 'static',
                    });
                    $('#store_or_update_modal .modal-title').html(
                        '<i class="fas fa-edit"></i> <span>Edit ' + data.name + '</span>');
                    $('#store_or_update_modal #save-btn').text('Update');

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
        let url   = "{{ route('vtranscation.delete') }}";
        delete_data(id, url, table, row, name);
    });

       $('#image').spartanMultiImagePicker({
            fieldName: 'image',
            maxCount: 1,
            rowHeight: '150px',
            groupClassName: 'col-md-12 com-sm-12 com-xs-12',
            maxFileSize: '',
            dropFileLabel: 'Drop Here',
            allowExt: 'png|jpg|jpeg',
            onExtensionErr: function(index, file){
                Swal.fire({icon:'error',title:'Oops...',text: 'Only png,jpg,jpeg file format allowed!'});
            }
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
            let url = "{{route('vtranscation.bulk.delete')}}";
            bulk_delete(ids,url,table,rows);
        }
    }

    $('#vendor_id').change(function() {
            var vendorId = $(this).val();
            if (vendorId) {
                $.ajax({
                    url: '{{ route("vtranscation.get.wallet.amount") }}',
                    type: 'POST',
                    data: { vendor_id: vendorId, _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        $('#wallet_amount').val(response.wallet_amount);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#wallet_amount').val('');
            }
        });



        function setReadonly() {
        // Get the textarea element by its ID
        var textarea = document.getElementById('wallet_amount');

        // Set the readOnly property to true
        textarea.readOnly = true;
        }

        // Call the setReadonly function initially
        setReadonly();

        // Periodically check and reset the readonly attribute
        setInterval(function() {
            setReadonly();
        }, 100);


        function showPaymentFields(paymentType) {
            // Hide all payment-related fields
            $('#cash_provider_name_container').hide();
            $('#online_mobile_container').hide();
            $('#online_transaction_number_container').hide();
            $('#bank_name_container').hide();
            $('#bank_account_container').hide();

            // Show fields corresponding to the selected payment type
            if (paymentType === 'cash') {
                $('#cash_provider_name_container').show();
            } else if (paymentType === 'online') {
                $('#online_mobile_container').show();
                $('#online_transaction_number_container').show();
            } else if (paymentType === 'bank') {
                $('#bank_name_container').show();
                $('#bank_account_container').show();
            }
        }



});
</script>
@endpush

