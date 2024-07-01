@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
         
        </div>
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="reports_table">
                    <thead>
                    <tr>
                        
                        <th>Report Type</th>
                        <th>Description</th>
                        <th>Report Date</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reports as $report)
                            <tr >
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
    
    $(this).on('click','.btn_report_upload', function(){
            var formData =new FormData($('#form_report')[0]);
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
            
                        console.error('Error uploading file:', error);
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
