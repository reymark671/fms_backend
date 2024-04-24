@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
        <div class="col-md-12  d-flex justify-content-end">
         
        </div>
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="vendors_table">
                    <thead>
                    <tr>
                        
                        <th>Company Name</th>
                        <th>Registered Name</th>
                        <th>Email Address</th>
                        <th>Mobile Number</th>
                        <th>Phone Number</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Action</th>
                        
                    </tr>
                    </thead>
                    <tbody>
              
                      @if(isset($vendors))
                        @foreach($vendors as $vendor)
                            <tr>
                                <td> {{ $vendor->company_name }} </td>
                                <td> {{ $vendor->first_name }}  {{ $vendor->last_name }}</td>
                                <td> {{ $vendor->email }} </td>
                                <td> {{ $vendor->mobile }} </td>
                                <td> {{ $vendor->phone }} </td>
                                <td> {{ $vendor->address_1 }} {{ $vendor->address_2 ?? '' }} {{ $vendor->city }} {{ $vendor->state }}, {{ $vendor->zipcode }} </td>
                                <td> 
                                    @if($vendor->is_active === '1')
                                        <span class="badge badge-primary">Activated</span>
                                    @elseif($vendor->is_active === '0')
                                        <span class="badge badge-info">Pending For Activation</span>
                                    @elseif($vendor->is_active === '-1')
                                        <span class="badge badge-danger">Declined</span>
                                    @else
                                        <span class="badge badge-secondary">Unknown Status</span>
                                    @endif
                                </td>
                                <td>
                                        @if($vendor->is_active === '1' || $vendor->is_active === '0')
                                        <button type="button" data-id="{{ $vendor->id }}" data-status="-1" data-name="{{ $vendor->company_name }}" class="button is-warning is-rounded is-small btn_set_status"> Decline</button>
                                        @endif
                                        @if($vendor->is_active === '1'  || $vendor->is_active === '-1')
                                        <button type="button" data-id="{{ $vendor->id }}" data-status="0" data-name="{{ $vendor->company_name }}" class="button is-info is-rounded is-small btn_set_status"> Deactivate</button>
                                        @endif
                                        @if($vendor->is_active === '0'  || $vendor->is_active === '-1')
                                        <button type="button" data-id="{{ $vendor->id }}" data-status="1" data-name="{{ $vendor->company_name }}" class="button is-primary is-rounded is-small btn_set_status"> Activate</button>
                                        @endif

                                </td>
                            </tr>
                        @endforeach
                      @endif
                    </tbody>
                </table>
            </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        var table = new DataTable('#vendors_table',{
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
                    url: "{{ route('change_vendor_status') }}",
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

        })
    });
 </script>
@endsection
