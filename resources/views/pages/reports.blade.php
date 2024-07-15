@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
         
        </div>
    </div>
    <div class="row m-4">
        <div class="col-md-12">
            <label for="filter_portal">Filter by Portal Destination:</label>
            <select id="filter_portal" class="form-select">
                <option value="">All</option>
                <option value="1">Coordinator</option>
                <option value="2">Employee</option>
                <option value="3">Client</option>
            </select>
        </div>
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="reports_table">
                    <thead>
                    <tr>
                        <th>Report Destination Portal</th>
                        <th>Accounts</th>
                        <th>Report Type</th>
                        <th>Description</th>
                        <th>Report Date</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                            <tr class="portal-type-{{ $report->report_destination_type }}">
                                <td>
                                    @php
                                        $typeBadgeClass = 'badge badge-secondary';
                                        $typeName = 'Unknown'; // Default type name
                                        switch ($report->report_destination_type) {
                                            case 1:
                                                $typeName = 'Coordinator';
                                                $typeBadgeClass = 'badge badge-primary';
                                                break;
                                            case 2:
                                                $typeName = 'Employee';
                                                $typeBadgeClass = 'badge badge-success';
                                                break;
                                            case 3:
                                                $typeName = 'Client';
                                                $typeBadgeClass = 'badge badge-info';
                                                break;
                                        }
                                    @endphp
                                    <span class="{{ $typeBadgeClass }}">{{ $typeName }}</span>
                                </td>
                                <td>
                                @if ($report->destination_account_full_names)
                                    @php
                                        $names = explode(',', $report->destination_account_full_names);
                                    @endphp
                                    @foreach ($names as $name)
                                        <span class="badge badge-primary">{{ $name }}</span>
                                    @endforeach
                                @else
                                    <span class="badge badge-secondary">No Accounts</span>
                                @endif
                                </td>
                                <td>{{ $report->report_type }}</td>
                                <td>{{ $report->description }}</td>
                                <td>{{ $report->report_date }}</td>
                                <td>
                                    <button class="button is-info is-rounded is-small btn_download" data-url="{{ $report->report_file }}" data-filename="{{ $report->report_type }}">Download</button>
                                    <button class="button is-danger is-rounded is-small btn_delete"  data-report_name="{{ $report->report_type }}" data-id="{{ $report->id }}">Delete</button>
                                </td>
                            
                             
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
    </div>
</div>
@include('modals.reports_add_modal')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
    var csrf= $('#logout-form').find('input[name="_token"]').val();
    var table = new DataTable('#reports_table',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            buttons: [
            {
                text: 'Upload Reports',
                className: 'btn-primary p-2 m-4  rounded-lg btn_reports_add',
               
            }
        ]
        });
    $(this).on('click','.btn_reports_add', function(){
        $('#report_modal').modal('toggle');
    });
    $('#filter_portal').change(function(){
        var selectedPortal = $(this).val();
        
        if (selectedPortal) {
            $('#reports_table tbody tr').hide();
            $('.portal-type-' + selectedPortal).show();
        } else {
            $('#reports_table tbody tr').show();
        }
    });
    $(this).on('click','.btn_report_cancel', function(){
                $('#report_modal').modal('toggle');
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
                function downloadBlob(blob, fileName) {
                var a = document.createElement('a');
                a.href = window.URL.createObjectURL(blob);
                a.download = fileName;
                document.body.appendChild(a);
                a.click(); 
                document.body.removeChild(a);
            }
        
            $(this).on('click','.btn_delete', function(){
            var data_id=$(this).data('id');
            var report_name=$(this).data('report_name');
            Swal.fire({
                title: "Are you sure you want to delete "+report_name+" resource file?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete_report') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                        },
                    data: {
                        id:data_id
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
                            $('#data_resources').val('');
                            $('#data_description').val('');
                            location.reload();
                        }, 2000);
                    },
                    error: function(error) {
                        console.error('Error uploading file:', error);
                    }
                    });
                
                }
            });
        })
    $(this).on('change','#report_destination', function(){
        var report_dest = $(this).val();
        console.log(report_dest);
        var fetch_route;
        if (report_dest === '1') fetch_route = "{{ route('fetch_coordinators') }}";
        if (report_dest === '2') fetch_route = "{{ route('fetch_employees_data') }}";
        if (report_dest === '3') fetch_route = "{{ route('fetch_clients_data') }}";
        $.ajax({
            url: fetch_route,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': csrf
                },
            success: function(response) {
                var select = $('#destination_account');
                        select.empty();
                        $.each(response, function(key, value) {
                            select.append('<option value="' + value.id + '">' + value.first_name + " " + value.last_name + '</option>');
                        });
                        select.select2({
                            dropdownParent: $('#report_modal')
                        });
            },
            error: function(error) {
                
            }
            });
                
                
    });
    
    $(this).on('click','.btn_report_upload', function(){
            var formData =new FormData($('#form_report')[0]);
            let reportType = $('#report_type').val();
            let description = $('#description').val();
            let reportDate = $('#report_date').val();
            let reportFile = $('input[name="report_file[]"]')[0].files;

            if (!reportType || !description || !reportDate || reportFile.length === 0) {
                Swal.fire({
                    title: 'Error!',
                    text: 'Please fill all fields and select at least one file.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
                return;
            }
            Swal.fire({
                title: "Are you sure you want to save this file?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Uploading...',
                        text: 'Please wait while the file is being uploaded.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });
                $.ajax({
                    url: "{{ route('upload_report') }}",
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
                            $('#data_resources').val('');
                            $('#data_description').val('');
                            location.reload();
                        }, 2000);
                    },
                    error: function(error) {
                        
                    }
                    });
                
                }
            });
        });
});
 </script>
 <style>

    .btn_reports_add {
    background-color: #5498c4 !important;
    color: white;           
}
</style>
@endsection
