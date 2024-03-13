@extends('layouts.app')

@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
         
        </div>
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="employeeTable">
                    <thead>
                    <tr>
                        
                        <th>Payroll Period</th>
                        <th>Recipient</th>
                        <th>Provider</th>
                        <!-- <th>Timesheet</th> -->
                        <th>payroll</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($payroll as $pr_data)
                            <tr >
                                <td><b>{{ date('F d, Y', strtotime($pr_data->payroll_start)) }} - {{date('F d, Y', strtotime($pr_data->payroll_end))}}</b></td>
                                <td>{{ $pr_data->client->first_name }} {{ $pr_data->client->last_name }}</td>
                                <td>{{ $pr_data->employee->first_name }} {{ $pr_data->employee->last_name }}</td>
                                <!-- <td></td> -->
                                <td>
                                    
                                    @php
                                        $pr_files = explode("|", $pr_data->payroll_file);
                                    @endphp

                                    @foreach($pr_files as $file)
                                    @php
                                            $urlParts = explode('/', $file);
                                            $fileName = array_pop($urlParts);
                                            $fileName = urldecode($fileName);
                                        @endphp
                                        <a href="#" data-url="{{ $file }}" data-filename="{{ $fileName }}" class="btn_download">{{  $fileName  }}</a><br>
                                    @endforeach 
                                    
                                </td>
                              
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
            </div>
    </div>
    @include('pages.modal_embed')
</div>

<script>
     $(document).ready(function() {
        var table = new DataTable('#employeeTable',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            buttons: [
            {
                text: 'Upload Payroll',
                className: 'btn-primary p-2 m-4  rounded-lg btn_add_payroll',
               
            }
        ]
        });
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        $(this).on('click','.btn_add_payroll', function(){
            $('#payroll_modal').modal('toggle');
        })

        $.ajax({
            url: "{{ route('fetch_clients') }}",
            method: 'get',
            success: function(clients) {
                var select = $('#recipientSelect');
                select.empty(); 
                select.append('<option value="0"> Select Recipient</option>');
                clients.forEach(function(client) {
                    select.append('<option value="' + client.id + '">'+ client.first_name +" "+ client.last_name + '</option>');
                });
               
            },
            error: function(error) {
                console.error('Error fetching clients:', error);
            }
        });
        $(this).on('change', '#recipientSelect', function(){
            var client_id = $('#recipientSelect').val();
            if(client_id>0) 
            {
                $.ajax({
                    url: "{{ route('fetch_employees') }}",
                    method: 'post',
                    data:
                    {
                        client_id:client_id
                    },
                    headers: {
                    'X-CSRF-TOKEN': csrf
                    },
                    success: function(response) {
                        var select = $('#providerSelect');
                        select.empty(); 

                        if (response.data.length > 0) {
                            select.append('<option value="0">Select Provider</option>');
                            
                            response.data.forEach(function(employee) {
                                select.append('<option value="' + employee.id + '">' + employee.first_name + ' ' + employee.last_name + '</option>');
                            });
                        } else {
                            select.append('<option value="0">No Service Providers</option>');
                        }
                    },
                    error: function(error) {
                        console.error('Error fetching clients:', error);
                    }
                });
            }
            
        });
        $(this).on('click','.btn_submit_payroll', function(){
            
                var formData = new FormData($('#payroll_form')[0]);
            $.ajax({
                url: "{{ route('create_payroll') }}",
                method: 'post',
                data: formData,
                processData: false,
                contentType: false, 
                headers: {
                'X-CSRF-TOKEN': csrf
                },
                success: function(response) {
                    Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                      });
                      setTimeout(function () {
                        location.reload();
                    }, 2000);
                
                },
                error: function(error) {
                    console.error('Error fetching clients:', error.responseText);
                    Swal.fire({
                        position: "bottom-end",
                        icon: "error",
                        title: "some fields are invalid",
                        showConfirmButton: false,
                        timer: 1000
                      });
                }
            });
          
        });
     });
     $(document).on('click', '.btn_download', function(){
        var url = $(this).data('url');
        var filename = $(this).data('filename');
        getImageBlobFromS3(url,filename);
    })
    function getImageBlobFromS3(url,fileName) 
    {
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
    #payroll_modal {
        z-index: 1050;
    }
    .btn_add_payroll {
    background-color: #5498c4 !important;
    color: white;           
}
</style>
@endsection
