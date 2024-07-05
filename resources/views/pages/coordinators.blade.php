@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
            <!-- Add your button here to trigger the modal if needed -->
        </div>
    </div>
    <div class="row  m-4">
        <div class="col-md-12">
        <div class="table-responsive">
    <table class="table table-striped" id="coordinators_table">
        <thead>
            <tr>
                <th>Regional Center</th>
                <th>Registered Name</th>
                <th>Email Address</th>
                <th>Assigned Clients</th>
                <th>Phone Number</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if($coordinators)
                @foreach($coordinators as $coordinator)
                <tr>
                    <td>{{ $coordinator->region_center }}</td>
                    <td>{{ $coordinator->first_name }} {{ $coordinator->last_name }}</td>
                    <td>{{ $coordinator->email }}</td>
                    <td>
                    @if($coordinator->assignments->isNotEmpty()) 
                        @foreach($coordinator->assignments as $assignment)
                            <span class="badge badge-info">
                                {{ $assignment->client->first_name }} {{ $assignment->client->last_name }} ({{ $assignment->client->email }})
                            </span><br>
                        @endforeach
                    @else 
                        <span class="badge badge-warning">No assignments yet</span>
                    @endif
                    </td>
                    <td>{{ $coordinator->phone }}</td>
                    <td>{{ $coordinator->address_1 }} {{ $coordinator->address_2 ?? '' }} {{ $coordinator->city }} {{ $coordinator->state }}, {{ $coordinator->zipcode }}</td>
                    <td> 
                        @if($coordinator->is_active === '1')
                            <span class="badge badge-primary">Activated</span>
                        @elseif($coordinator->is_active === '0')
                            <span class="badge badge-info">Pending For Activation</span>
                        @elseif($coordinator->is_active === '-1')
                            <span class="badge badge-danger">Declined</span>
                        @else
                            <span class="badge badge-secondary">Unknown Status</span>
                        @endif
                    </td>
                    <td>
                        @if($coordinator->is_active === '1' || $coordinator->is_active === '0')
                        <button type="button" data-id="{{ $coordinator->id }}" data-status="-1" data-name="{{ $coordinator->company_name }}" class="btn btn-warning btn-sm btn_set_status"> Decline</button>
                        @endif
                        @if($coordinator->is_active === '1'  || $coordinator->is_active === '-1')
                        <button type="button" data-id="{{ $coordinator->id }}" data-status="0" data-name="{{ $coordinator->company_name }}" class="btn btn-info btn-sm btn_set_status"> Deactivate</button>
                        @endif
                        @if($coordinator->is_active === '0'  || $coordinator->is_active === '-1')
                        <button type="button" data-id="{{ $coordinator->id }}" data-status="1" data-name="{{ $coordinator->company_name }}" class="btn btn-primary btn-sm btn_set_status"> Activate</button>
                        @endif
                        @if($coordinator->is_active === '1')
                        <button type="button" data-id="{{ $coordinator->id }}"  data-name="{{ $coordinator->company_name }}" class="btn btn-success btn-sm btn_assign"> Assign Client</button>
                        @endif
                    </td>
                </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

        </div>
        @include('modals.assign_clients_to_coordinator_modal')
    </div>
</div>

<!-- Include Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- Include Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
    .select2-container {
        width: 100% !important;
    }
</style>

<script>
    $(document).ready(function() {
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        var table = new DataTable('#coordinators_table',{
            dom: 'frtip',
            order: [[0, 'desc']],
            select: true
        });

        $(this).on('click', '.btn_set_status', function(){
            var data_id=$(this).data('id');
            var status=$(this).data('status');
            var name=$(this).data('name');
            var message= "";
            if(status =="1") message = "Activate";
            if(status =="-1")message = "Decline";
            if(status =="0")message = "Deactivate";
            Swal.fire({
                title: "Are you sure you want to "+ message +" " +name+"?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirm"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('change_coordinator_status') }}",
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': csrf
                        },
                        data: {
                            id:data_id,
                            status:status
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
                            console.error('Error uploading file:', error);
                        }
                    });
                }
            });
        });

        $(this).on('click','.btn_assign', function(){
            var coordinator_id = $(this).data('id');
            $.ajax({
                url: "{{ route('fetch_available_clients') }}",
                type: 'post',
                headers: {
                    'X-CSRF-TOKEN': csrf
                },
                data: {
                    coordinator_id:coordinator_id
                },
                success: function(response) {
                 if(response.length>0)
                    {
                        $('#coordinator_id').val(coordinator_id);
                        $('#client_assignment').modal('show');
                        var select = $('#client_name_select');
                        select.empty();
                        $.each(response, function(key, value) {
                            select.append('<option value="' + value.id + '">' + value.first_name + " " + value.last_name +"("+value.email + ')</option>');
                        });
                        select.select2({
                            dropdownParent: $('#client_assignment')
                        });
                    }
                    else
                    return  Swal.fire({
                                icon: "error",
                                title: "No available clients yet",
                            });
                    
                },
                error: function(error) {
                    console.error('Error fetching clients:', error);
                }
            });
        });
        $(this).on('click', '.btn_submit_assignments', function(){
            var selectedClients = $('#client_name_select').val();
            if(selectedClients.length<=0)
            {
                return  Swal.fire({
                                icon: "error",
                                title: "Please select a client",
                            });
            }
            
            var coordinator_id = $('#coordinator_id').val();
            $.ajax({
                    url: "{{ route('clients_assignment') }}",
                    type: 'post',
                    headers: {
                        'X-CSRF-TOKEN': csrf
                    },
                    data: {
                        coordinator_id:coordinator_id,
                        selected_clients:selectedClients
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: "success",
                            title: "Client Assigned",
                        }).then((result) => {
                            if (result.isConfirmed || result.isDismissed) {
                                location.reload();
                            }
                        });
                    },
                    error: function(error) {
                        console.error('Error fetching clients:', error);
                    }
                });
        });
    });
 </script>
@endsection
