@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="service_code_table">
                    <thead>
                    <tr>
                        
                        <th>Code</th>
                        <th>Category</th>
                        <th>Description</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($service_codes) && $service_codes->count() > 0)
                        @foreach($service_codes as $service_code)
                        <tr>
                            <td>{{$service_code->code}}</td>
                            <td>{{$service_code->category->category_description}}</td>
                            <td>{{$service_code->service_code_description}}</td>
                            <td>
                                <button class="button is-danger is-rounded is-small btn_delete" 
                                        data-description="{{$service_code->code}}" 
                                        data-id="{{$service_code->id}}">Delete</button>
                                <button class="button is-success is-rounded is-small btn_update" 
                                        data-id="{{$service_code->id}}">Update</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">No service codes found.</td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
    </div>
    @include('modals.service_code')
</div>
<script>
    $(document).ready(function() {
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        var table = new DataTable('#service_code_table',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            select: true,
            buttons: [
            {
                text: 'Add Service Code',
                className: 'btn-primary p-2 m-4  rounded-lg btn_add_service_code',
               
            }
        ]
        });
        $(document).on('click', '.btn_add_service_code', function() {
            $('#form_mode').val('add');
            $('#service_code_form').trigger('reset');
            fetch_categories();
            $('#service_code').modal('show');
        });
        function fetch_categories(){
            $.ajax({
                url: '/service-categories', 
                type: 'GET',
                success: function(response) {
                    var select = $('#service_code_category_id');
                    select.empty();
                    select.append('<option value="">Select a category</option>');
                    $.each(response.categories, function(key, value) {
                        select.append('<option value="' + value.id + '">' + value.category_description + '</option>');
                    });
                }
            });
        }


        $(document).on('click', '.btn_add_service_code', function() {
            $('#form_mode').val('add');
            $('#service_code_form').trigger('reset');
            fetch_categories();
            $('#service_code').modal('show');
        });

        $('#service_code_form').on('submit', function(e) {
            e.preventDefault();
            var formData = $(this).serialize();
            var formMode = $('#form_mode').val();

            if (formMode === 'add') {
                $.ajax({
                    url: '/service-codes',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    success: function(response) {
                        $('#service_code').modal('hide');
                        Swal.fire('Success', 'Service code added successfully', 'success');
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to add service code', 'error');
                    }
                });
            } else if (formMode === 'update') {
                var id = $(this).attr('action').split('/').pop();
                $.ajax({
                    url: '/service-codes/' + id,
                    type: 'PUT',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    success: function(response) {
                        $('#service_code').modal('hide');
                        Swal.fire('Success', 'Service code updated successfully', 'success');
                        location.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Failed to update service code', 'error');
                    }
                });
            }
        });
        $(document).on('click', '.btn_delete', function() {
            var id = $(this).data('id');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '/service-codes/' + id,
                        type: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        },
                        success: function(response) {
                            Swal.fire('Deleted!', 'Service code has been deleted.', 'success');
                            location.reload();
                        },
                        error: function(xhr) {
                            Swal.fire('Error', 'Failed to delete service code', 'error');
                        }
                    });
                }
            });
        });
        $(document).on('click', '.btn_update', function() {
            var id = $(this).data('id');
            fetch_categories();
            $.ajax({
                url: '/service-codes/' + id + '/edit',
                type: 'GET',
                success: function(response) {
                    $('#form_mode').val('update');
                    $('#service_code_form').attr('action', '/service-codes/' + id);
                    $('#service_code_category_id').val(response.service_code.service_code_category_id);
                    $('#service_code_description').val(response.service_code.service_code_description);
                    $('#code').val(response.service_code.code);
                    $('#service_code').modal('show');
                }
            });
        });
        
       
    });
 </script>
 <style>
   
    .btn_add_service_code {
    background-color: #5498c4 !important;
    color: white;           
}
</style>
@endsection
