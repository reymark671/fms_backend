@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="service_category_table">
                    <thead>
                    <tr>
                        
                        <th>Category ID</th>
                        <th>Category Name</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(isset($service_categories))
                            @foreach($service_categories as $service_category)
                            <tr>
                                <td>{{$service_category->id}}</td>
                                <td>{{$service_category->category_description}}</td>
                                <td>
                                    <button class="button is-danger is-rounded is-small btn_delete" data-description="{{$service_category->category_description}}" data-id="{{$service_category->id}}">Delete</button>
                                    <button class="button is-success is-rounded is-small btn_update" data-id="{{$service_category->id}}">Update</button>
                                </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
    </div>
    @include('modals.service_category')
</div>
<script>
    $(document).ready(function() {
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        var table = new DataTable('#service_category_table',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            select: true,
            buttons: [
            {
                text: 'Create Category',
                className: 'btn-primary p-2 m-4  rounded-lg btn_add_category',
               
            }
        ]
        });

        $(this).on('click','.btn_add_category', function(){
            $('#service_category').modal('toggle');
        });
        $(this).on('click','.btn_submit_category', function(){

            var category_name = $('#category_name').val().trim();
            if(category_name.length <= 0)
            {
                return Swal.fire({
                    title: "Please Provide Category Name",
                    icon: "error"
                });
            }
            $.ajax({
                url: "{{ route('create_category') }}",
                method: 'post',
                data: {"category_name": category_name},
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
                error: function(error,response,message) {
                    console.error('Error:', error.responseJSON.message);
                    Swal.fire({
                        position: "bottom-end",
                        icon: "error",
                        title: error.responseJSON.message,
                        showConfirmButton: false,
                        timer: 1000
                      });
                }
            });


        });
        $(document).on('click', '.btn_delete', function() {
            var categoryId = $(this).data('id');
            var categoryDescription = $(this).data('description');

            Swal.fire({
                title: 'Are you sure?',
                text: `Do you really want to delete the category "${categoryDescription}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/service-categories/${categoryId}`,
                        type: 'DELETE',
                        headers: {
                        'X-CSRF-TOKEN': csrf
                        },
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire(
                                'Deleted!',
                                response.message,
                                'success'
                            );
                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        },
                        error: function(xhr) {
                            Swal.fire(
                                'Error!',
                                'There was a problem deleting the category.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
        $(document).on('click', '.btn_update', function() {
            var categoryId = $(this).data('id');
            var currentDescription = $(this).closest('tr').find('td:eq(1)').text();

            Swal.fire({
                title: 'Update Category',
                input: 'text',
                inputValue: currentDescription,
                showCancelButton: true,
                confirmButtonText: 'Update',
                showLoaderOnConfirm: true,
                preConfirm: (newDescription) => {
                    return $.ajax({
                        url: `/service-categories/${categoryId}`,
                        type: 'PUT',
                        headers: {
                        'X-CSRF-TOKEN': csrf
                        },
                        data: {
                            category_description: newDescription
                        }
                    }).then(response => {
                        return response;
                    }).catch(error => {
                        Swal.showValidationMessage(
                            `Request failed: ${error.responseJSON.message}`
                        )
                    });
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Updated!',
                        text: result.value.message,
                        icon: 'success'
                    });
                    $(this).closest('tr').find('td:eq(1)').text(result.value.category.category_description);
                }
            });
        });
    });
 </script>
 <style>
   
    .btn_add_category {
    background-color: #5498c4 !important;
    color: white;           
}
</style>
@endsection
