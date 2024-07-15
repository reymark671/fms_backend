@extends('layouts.app')

@section('content')
<div class="container p-2">
    <table id="employeesTable" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Client Name</th>
                <th>Service Code</th>
                <th>Uploaded File</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $data)
                <tr>
                    <td>{{$data->id}}</td>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->last_name}}</td>
                    <td>{{$data->email}}</td>
                    <td>{{$data->client->first_name }}  {{$data->client->last_name }}</td>
                    <td>{{$data->service_code ?? 'None'}} </td>
                    <td>
                        <a href="#" onclick="openModalFromTable({{ $data->id }}, '{{ $data->file_dir }}')">View File</a>
                    </td>
                    <td>

                        <div class="buttons are-small ">
                            <button class="button {{$data->service_code ? 'is-primary': 'is-info'}} is-rounded btn_add_service_code" data-id="{{ $data->id }}">{{$data->service_code ? 'Update Service Code': 'Add Service Code'}}</button>
                          
                        </div>
                    </td>
                </tr>                      
            @endforeach
        </tbody>
        <tfoot></tfoot>
    </table>
    @include('pages.modal_clients')
</div>

<script>
    $(document).ready(function () {
        new DataTable('#employeesTable');
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        $(document).on('click', '.btn_add_service_code', function() {
         
            const employeeId = $(this).data('id');
            Swal.fire({
                title: 'Enter Service Code',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Submit',
                showLoaderOnConfirm: true,
                preConfirm: (serviceCode) => {
                    return $.ajax({
                        url: "{{ route('update_employee') }}",  
                        type: 'POST',
                        data: {
                            id: employeeId,
                            service_code: serviceCode
                        },
                        headers: {
                            'X-CSRF-TOKEN': csrf 
                        }
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                        Swal.showValidationMessage(
                            `Request failed: ${errorThrown}`
                        );
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: `Service code updated successfully!`,
                        icon: 'success'
                    });
                    setTimeout(function () {
                            location.reload();
                        }, 2000);
                }
            });
        });

    });

    function openFileModal(employeeId, fileDirs) {
        var fileDirArray = fileDirs.split('|');
        var fileModalBody = $('#fileModal .modal-body');
        fileModalBody.empty();
        var basePath = 'uploads/employees/';
        fileDirArray.forEach(function (fileDir) {
        
            var urlParts = fileDir.split('/');
            var fileName = urlParts.pop();
            fileName = decodeURIComponent(fileName);
            fileModalBody.append(`
                ${isPdfFile(fileDir) ?
                    `<iframe src="${fileDir}" width="100%" height="500px" ></iframe>` :
                    `<img src="${fileDir}" class="d-block mx-auto img-fluid" alt="Uploaded File">
                    <center><h5>${fileName}</h5></center>`
                }
                <div class="text-center mt-2">
                    <a href="#" download="${fileName}" class="btn btn-primary btn_download" data-url="${fileDir}" data-filename="${fileName}">Download File</a>
                </div>
                <hr>
            `);
        });
      

        $('#fileModal .modal-dialog').removeClass('modal-sm').addClass('modal-xl');
        $('#fileModal').modal('show');
    }
    $(document).on('click', '.btn_download', function() {
        var file_name = $(this).data('filename'); 
        var url = $(this).data('url'); 
        getImageBlobFromS3(url,file_name) ;
    });
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
    function downloadBlob(blob, fileName) {
    var a = document.createElement('a');
    a.href = window.URL.createObjectURL(blob);
    a.download = fileName;
    document.body.appendChild(a);
    a.click(); 
    document.body.removeChild(a);
}

}

    function openModalFromTable(employeeId, fileDirs) {
        openFileModal(employeeId, fileDirs);
    }

    function isPdfFile(fileUrl) {
        return fileUrl.toLowerCase().endsWith('.pdf');
    }
</script>


@endsection
