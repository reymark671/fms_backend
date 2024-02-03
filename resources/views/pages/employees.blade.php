@extends('layouts.app')

@section('content')
<div class="container p-2">
    <table id="employeesTable" class="ui celled table" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Client ID</th>
                <th>Uploaded File</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $data)
                <tr>
                    <td>{{$data->id}}</td>
                    <td>{{$data->first_name}}</td>
                    <td>{{$data->last_name}}</td>
                    <td>{{$data->client->first_name }}  {{$data->client->last_name }}</td>
                    <td>
                        <a href="#" onclick="openModalFromTable({{ $data->id }}, '{{ $data->file_dir }}')">View File</a>
                    </td>
                </tr>                      
            @endforeach
        </tbody>
        <tfoot></tfoot>
    </table>
    @include('pages.modal_embed')
</div>

<script>
    $(document).ready(function () {
        new DataTable('#employeesTable');
    });

    function openFileModal(employeeId, fileDirs) {
        var fileDirArray = fileDirs.split('|');
        var fileModalBody = $('#fileModal .modal-body');
        fileModalBody.empty();
        var basePath = 'uploads/employees/';
        fileDirArray.forEach(function (fileDir) {
            var fileName = fileDir.replace(basePath, '');
            fileModalBody.append(`
                <h5>${fileName}</h5>
                ${isPdfFile(fileDir) ?
                    `<iframe src="${fileDir}" width="100%" height="500px"></iframe>` :
                    `<img src="${fileDir}" class="img-fluid" alt="Uploaded File">`
                }
                <div class="text-center mt-2">
                    <a href="${fileDir}" download="file_name" class="btn btn-primary">Download File</a>
                </div>
                <hr>
            `);
        });

        $('#fileModal .modal-dialog').removeClass('modal-sm').addClass('modal-xl');
        $('#fileModal').modal('show');
    }

    function openModalFromTable(employeeId, fileDirs) {
        openFileModal(employeeId, fileDirs);
    }

    function isPdfFile(fileUrl) {
        return fileUrl.toLowerCase().endsWith('.pdf');
    }
</script>

<style>
    #fileModal {
        z-index: 1050;
    }
</style>
@endsection
