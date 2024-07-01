@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
         
        </div>
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="vendors_invoice_table">
                    <thead>
                    <tr>
                        
                        <th>Company Name</th>
                        <th>Registered Name</th>
                        <th>Email Address</th>
                        <th>Mobile Number</th>
                        <th>Client Name</th>
                        <th>Location</th>
                        <th>Invoice File</th>
                        <th>Receipt File</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                      @if($vendors_invoice)
                        @foreach($vendors_invoice as $invoice)
                        <tr>
                            <td>{{$invoice->vendor->company_name}}</td>
                            <td>{{$invoice->vendor->first_name}} {{$invoice->vendor->last_name}}</td>
                            <td>{{$invoice->vendor->email}}</td>
                            <td>{{$invoice->vendor->mobile}}</td>
                            <td>{{$invoice->client_name}}</td>
                            <td>{{$invoice->vendor->address_1}} {{$invoice->vendor->address_2 ?? ""}} {{$invoice->vendor->city}} {{$invoice->vendor->state}}</td>
                            <td>
                                <button class="button is-info is-rounded is-small btn_download" data-url="{{ $invoice->invoice_file }}" data-filename="{{ $invoice->vendor->company_name }}">Download</button>
                            </td>
                            <td>
                                @if($invoice->reciept_file)
                            <button class="button is-primary is-rounded is-small btn_download" data-url="{{ $invoice->reciept_file }}" data-filename="{{ $invoice->vendor->company_name }}_receipt">Download</button>
                            @endif
                            </td>
                            <td>{{ (double) $invoice->invoice_price }}</td>
                            <td>{{$invoice->is_complete ? 'Completed':'Pending'}}</td>
                            <td>
                            <button data-id="{{$invoice->id}}" class="button is-info is-rounded is-small btn_upload_receipt">Upload Receipt</button>
                            <button data-id="{{$invoice->id}}" class="button is-danger is-rounded is-small btn_delete">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                      @endif
                    </tbody>
                </table>
            </div>
    </div>
</div>
@include('modals.invoice_receipt_modal')
<script>
   $(document).ready(function(){
    var table = new DataTable('#vendors_invoice_table',{
        dom: 'frtip',
        order: [[0, 'desc']],
    
    });
    var csrf= $('#logout-form').find('input[name="_token"]').val();
    $(this).on('click', '.btn_delete', function(){
        var id = $(this).data('id');
        Swal.fire({
                    title: "Are you sure you want to delete this invoice?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Delete"
                }).then((result) => {
                    if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('delete_vendor_invoice') }}",
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                            },
                        data: {
                            id: id
                        },
                        success: function(response) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "saved",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        },
                        error: function(error) {
                            // Handle the error response
                            console.error('Error uploading file:', error);
                        }
                        });
                    
                    }
                });
    });
    $(this).on('click','.btn_download', function(){
        
            var url = $(this).data("url");
            var filename =  $(this).data("filename");
            Swal.fire({
                title: "Are you sure you want to download this file?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                        $.ajax({
                        url: url,
                        type: 'GET',
                        xhrFields: {
                            responseType: 'blob' 
                        },
                        success: function(data) {
                            downloadBlob(data,filename);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.error('Error fetching image:', errorThrown);
                        }
                    });
                
                }
            });
            });

            $(this).on('click', '.btn_upload_receipt', function(){
                $('#invoice_receipt').modal('toggle');
                invoice_id = $(this).data('id');
                $('#invoice_id').val(invoice_id);
            });
            $(this).on('click','.btn_invoice_receipt_cancel', function(){
                $('#invoice_receipt').modal('toggle');
            });
            $(this).on('click','.btn_invoice_receipt_upload', function(){
                var formData =new FormData($('#form_payable')[0]);
                Swal.fire({
                    title: "Are you sure you want to save this file?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirm"
                }).then((result) => {
                    if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('update_vendor_invoice') }}",
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                            },
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            Swal.fire({
                                position: "top-end",
                                icon: "success",
                                title: "saved",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        },
                        error: function(error) {
                            // Handle the error response
                            console.error('Error uploading file:', error);
                        }
                        });
                    
                    }
                });
                
            });
                function downloadBlob(blob, fileName) {
                var a = document.createElement('a');
                a.href = window.URL.createObjectURL(blob);
                a.download = fileName;
                document.body.appendChild(a);
                a.click(); 
                document.body.removeChild(a);
            }
   })
 </script>
 
@endsection
