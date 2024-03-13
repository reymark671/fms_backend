@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <!-- <div class="col-md-12  d-flex justify-content-end">
            <button class="btn-primary p-2 m-4  rounded-lg btn_add_payroll">
                Upload Payroll
            </button>
        </div> -->
    </div>
    <div class="row m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="payablesTable" style="width:100%">
                    <thead>
                    <tr>
                        
                        <th>Upload Date</th>
                        <th>Description</th>
                        <th>Invoice</th>
                        <th>Client</th>
                        <!-- <th>Provider</th> -->
                        <th>Receipt</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($payables as $payable)
                            <tr >
                               <td><b>{{date("F d, Y", strtotime($payable->created_at))}}</b></td>
                               <td>{{$payable->description}}</td>
                               <td>
                                    @php
                                        $payable_file = explode("|", $payable->file_dir);
                                    @endphp

                                    @foreach($payable_file as $file)
                                    @php
                                        $urlParts = explode('/', $file);
                                        $fileName = array_pop($urlParts);
                                        $fileName = urldecode($fileName);
                                    @endphp
                                        <a href="#" data-url="{{ $file }}" data-filename="{{ $fileName }}" class="btn_download">{{  $fileName  }}</a><br>
                                    @endforeach
                               </td>
                               <td>{{$payable->client->first_name}} {{$payable->client->last_name}}</td>
                               <!-- <td>{{$payable->employee_id->first_name ?? ""}} {{$payable->employee_id->last_name ?? ""}}</td> -->
                               <td>
                                    @if($payable->response_file)
                                    @php
                                        $payable_file = explode("|", $payable->response_file);
                                    @endphp

                                    @foreach($payable_file as $file)
                                        @php
                                            $urlParts = explode('/', $file);
                                            $fileName = array_pop($urlParts);
                                            $fileName = urldecode($fileName);
                                        @endphp
                                        <a href="#" data-url="{{ $file }}" data-filename="{{ $fileName }}" class="btn_download">{{  $fileName  }}</a><br>
                                    @endforeach
                                    @else
                                    <span>No data</span>
                                    @endif
                               </td>
                               <td>
                                @if($payable->response_file)
                                    <button class="btn-info rounded-lg btn_upload" data-id="{{$payable->id}}">Replace Receipt</button>
                                @else
                                    <button class="btn-primary rounded-lg btn_upload" data-id="{{$payable->id}}">Upload File</button>
                                @endif
                               
                            </td>
                              
                            </tr>
                        @endforeach
                    </tbody>
                </table>
           
            </div>
    </div>
    @include('modals.payables')
</div>

<script>
    $(document).ready(function(){
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        var payables_id="";
        let table =  new DataTable('#payablesTable',{
            order: [[5, 'desc']],
            columnDefs: [
                {
                    target: [0, 2, 5],
                    searchable: false
                }
            ]
        });
        
    
    $(this).on('click','.btn_upload', function(){
        $('#payable_modal').modal('toggle');
        payables_id = $(this).data('id');
        $('#payable_id').val(payables_id);
    });

    $(this).on('click','.btn_payroll_upload', function(){
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
                  url: "{{ route('update_payables') }}",
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
    $(this).on('click','.btn_payroll_cancel', function(){
        $('#payable_modal').modal('toggle');
    });

    });

    $(document).on('click', '.btn_download', function(){
        var url = $(this).data('url');
        var filename = $(this).data('filename');
        getImageBlobFromS3(url,filename);
    })
    function getImageBlobFromS3(url,fileName) {
        $.ajax({
            url: url,
            type: 'GET',
            xhrFields: {
                responseType: 'blob' 
            },
            success: function(data) {
                downloadBlob(data,fileName);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching image:', errorThrown);
            }
        });
    function downloadBlob(blob, fileName) 
    {
        var a = document.createElement('a');
        a.href = window.URL.createObjectURL(blob);
        a.download = fileName;
        document.body.appendChild(a);
        a.click(); 
        document.body.removeChild(a);
    }   
    }
</script>
<style>
    #payable_modal {
        z-index: 1050;
    }
</style>
@endsection
