@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
        <div class="col-md-12">
                <table class="table is-striped" id="resources" style="width:100%">
                    <thead>
                    <tr>
                        
                        <th>Upload Date</th>
                        <th>Resource Name</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($resources as $resource)
                            <tr >
                                <td>{{ $resource->created_at }}</td>
                                <td>{{ $resource->resource_name }}</td>
                                <td>{{ $resource->description }}</td>
                                <td>
                                    <button class="btn btn-info btn_download" data-url="{{ $resource->url }}" data-filename="{{ $resource->resource_name }}">Download</button>
                                    <button class="btn btn-danger btn_delete"  data-resource_name="{{ $resource->resource_name }}" data-id="{{ $resource->id }}">Delete</button>
                                </td>
                             
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @include('modals.resource_modal')
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            var csrf= $('#logout-form').find('input[name="_token"]').val();
            var table = new DataTable('#resources',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            buttons: [
            {
                text: 'Upload Resources',
                className: 'btn-primary p-2 m-4  rounded-lg btn_resources_add',
               
            }
        ]
        });
        $(this).on('click','.btn_delete', function(){
            var data_id=$(this).data('id');
            var resource_name=$(this).data('resource_name');
            Swal.fire({
                title: "Are you sure you want to delete "+resource_name+" resource file?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('delete_resources') }}",
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

        $(this).on('click','.btn_resources_add', function(){
                $('#resource_modal').modal('toggle');
            });
        $(this).on('click','.btn_resource_cancel', function(){
                $('#resource_modal').modal('toggle');
            });
        $(this).on('click','.btn_resource_upload', function(){
            var formData =new FormData($('#form_resource')[0]);
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
                    url: "{{ route('add_resources') }}",
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
        
        });
    </script>
    <style>
    #resource_modal {
        z-index: 1050;
    }
    .btn_resources_add {
    background-color: #5498c4 !important;
    color: white;           
}
</style>
@endsection
