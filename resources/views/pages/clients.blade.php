@extends('layouts.app')

@section('content')

<div class="container-fluid">
    <form  method="get" id="statusForm">
        <label for="status">Filter by Status:</label>
        <select name="status" id="status" class="form-control form-select-sm mb-3">
            <option value="all">All</option>
            <option value="active">Active</option>
            <option value="pending">Pending</option>
            <option value="declined">Declined</option>
        </select>
    </form>
    <table class="table" id="clientsTable">
        <thead>
            <tr>
                <th scope="col" class="col-sm-1 col-md-2 col-lg-4">First Name</th>
                <th scope="col" class="col-sm-1 col-md-2 col-lg-4">Last Name</th>
                <th scope="col" class="col-md-2 col-lg-2">Status</th>
                <th scope="col" class="col-md-1 col-lg-3">Action</th>
            </tr>
        </thead>
        <tbody id="clientsTableBody">
        </tbody>
    </table>
</div>
<div class="modal" id="modal-loading" data-backdrop="static">
  <div class="modal-dialog modal-dialog-centered modal-sm">
    <div class="modal-content">
      <div class="modal-body text-center">
        <div class="loading-spinner mb-2"></div>
        <div>Loading</div>
      </div>
    </div>
  </div>
</div>

<div class="modal" id="modal-employee-data" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Employee Data</h5>
        <button type="button" class="close close_modal" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <table class="table" id="employeeDataTable">
          <thead>
            <!-- Add your DataTables header columns here -->
            <th>First Name</th>
            <th>Last Name</th>
            
            <!-- ... -->
          </thead>
          <tbody>
            <!-- DataTables content will be dynamically added here -->
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary close_modal" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
   var allClientsData;
    jQuery(document).ready(function () {
        fetchClients('all');
        $('#modal-employee-data').modal();
        $('#status').on('change', function () {
          submitForm();
        });
        $(this).on('click','.btn-action', function()
        {
          var clientLastName = $(this).data('last_name');
          var clientFirstName = $(this).data('first_name');
          var clientId = $(this).data('id');
          var btn_action = $(this).attr('id');
          if(btn_action=="delete")
          {
            Swal.fire({
                  title: "Are you sure you want to delete the record of "+clientFirstName +" "+ clientLastName+" ?",
                  text: "You won't be able to revert this!",
                  icon: "warning",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Yes, delete it!"
                }).then((result) => {
                  if (result.isConfirmed) {
                    $.ajax({
                            url: "{{ route('delete_client') }}", 
                            method: 'POST',
                            data: { id: clientId },
                            headers: {
                              'X-CSRF-TOKEN': getCsrfToken()
                            },
                            success: function(response) {
                                fetchClients('all');
                                Swal.fire({
                                    title: "Deleted!",
                                    text: "Record has been deleted.",
                                    icon: "success"
                                });
                            },
                            error: function(error) {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Record not found.",
                                    icon: "error"
                                });
                            }
                        });
                    
                  }
                });
          }
          if(btn_action=="view")
          {
            var clientDetails = allClientsData.find(client => client.id === clientId);
              Swal.fire({
                title: "<strong>"+clientFirstName+" "+clientLastName+"</strong>",
                icon: "info",
                html: `
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <th scope="row" class="text-right">SS Number:</th>
                        <td class="text-left">${clientDetails.ss_number}</td>
                      </tr>
                      <tr>
                        <th scope="row" class="text-right">Email Address:</th>
                        <td class="text-left">${clientDetails.email}</td>
                      </tr>
                      <tr>
                        <th scope="row" class="text-right">Contact Number:</th>
                        <td class="text-left">${clientDetails.contact_number}</td>
                      </tr>
                      <tr>
                        <th scope="row" class="text-right">Address:</th>
                        <td class="text-left">${clientDetails.address}</td>
                      </tr>
                      <tr>
                        <th scope="row" class="text-right">City:</th>
                        <td class="text-left">${clientDetails.city}</td>
                      </tr>
                      <tr>
                        <th scope="row" class="text-right">State:</th>
                        <td class="text-left">${clientDetails.state}</td>
                      </tr>
                      <tr>
                        <th scope="row" class="text-right">Postal Code:</th>
                        <td class="text-left">${clientDetails.zip_code}</td>
                      </tr>
                    </tbody>
                  </table>
                  
                `,
                showCloseButton: true,
                showCancelButton: false,
                focusConfirm: false,
                confirmButtonText: `
                  <i class="fa fa-close"></i> Close
                `,
                
              });
          }
          if(btn_action=="approved")
          {
            Swal.fire({
                  title: "Are you sure you want to approve the record of "+clientFirstName +" "+ clientLastName+" ?",
                  icon: "info",
                  showCancelButton: true,
                  confirmButtonColor: "#3085d6",
                  cancelButtonColor: "#d33",
                  confirmButtonText: "Approve"
                }).then((result) => {
                  if (result.isConfirmed) {
                    $('#modal-loading').modal('show');
                    $.ajax({
                            url: "{{ route('approve_client') }}", 
                            method: 'POST',
                            data: { id: clientId },
                            headers: {
                              'X-CSRF-TOKEN': getCsrfToken()
                            },
                            success: function(response) {
                              $('#modal-loading').modal('hide');
                                fetchClients('all');
                                Swal.fire({
                                    title: "Updated!",
                                    text: "Record has been updated.",
                                    icon: "success"
                                });
                            },
                            error: function(error) {
                              Swal.fire({
                                    title: "Error!",
                                    text: "Record not found.",
                                    icon: "error"
                                });
                            }
                        });
                    
                  }
                });

          }
          if(btn_action=="employee_data")
          {

            $('#modal-employee-data').modal('show');
            fetchAllClientsData(clientId);
          }
        })
        $(this).on('click','.close_modal', function () {
           
          $('#modal-employee-data').modal('hide');
        });
      });

    function submitForm() {
        var status = $('#status').val();
        fetchClients(status);
    }

    function fetchClients(status) {
        $.ajax({
            url: "{{ route('fetch_clients') }}",
            method: 'GET',
            data: { status: status },
            success: function(response) {
              allClientsData =response;
                updateTable(response);
            },
            error: function(error) {
                console.error('Ajax request failed:', error);
            }
        });
    }

    function updateTable(data) {
      console.log(allClientsData)
        var tableBody = $('#clientsTableBody');
        tableBody.empty();
        $.each(data, function(index, client) {
            var statusClass;
            if (client.status > 0) {
                statusClass = "success";
            } else if (client.status == 0) {
                statusClass = "info";
            } else {
                statusClass = "danger";
            }

            var rowHtml = '<tr>' +
                '<td>' + client.first_name + '</td>' +
                '<td>' + client.last_name + '</td>' +
                '<td><div class="bg-' + statusClass + '"><center>' + getStatusLabel(client.status) + '</center></div></td>' +
                '<td>'+
                    '<button type="button" id="approved" data-id="'+client.id+'" data-first_name="'+client.first_name+'" data-last_name="'+client.last_name+'" class="btn-action btn-sm rounded-pill btn-success"><i class="fas fa-check"></i></button>'  +
                    '<button type="button" id="view" data-id="'+client.id+'" data-first_name="'+client.first_name+'" data-last_name="'+client.last_name+'" class="btn-action btn-sm rounded-pill btn-info mr-1 ml-1"><i class="fas fa-eye"></i></button>'  +
                    '<button type="button" id="delete" data-id="'+client.id+'" data-first_name="'+client.first_name+'" data-last_name="'+client.last_name+'" class="btn-action btn-sm rounded-pill btn-danger "><i class="fas fa-trash"></i></button>'  +
                    '<button type="button" id="employee_data" data-id="'+client.id+'" data-first_name="'+client.first_name+'" data-last_name="'+client.last_name+'" class="btn-action btn-sm rounded-pill btn-warning ml-1"><i class="fas fa-book"></i></button>'  +
                '</td>' +
                '</tr>';

            tableBody.append(rowHtml);
        });
    }

    function getStatusLabel(status) {
        if (status > 0) {
            return "Active";
        } else if (status == 0) {
            return "Pending";
        } else {
            return "Declined";
        }
    }
    function getCsrfToken() {
    return $('#logout-form').find('input[name="_token"]').val();
}

function fetchAllClientsData(client_id) {
  $.ajax({
    url: "{{ route('fetch_employees') }}", // Adjust the route name
    method: 'POST',
    data: { client_id: client_id },
    headers: {
              'X-CSRF-TOKEN': getCsrfToken()
            },
    success: function (response) {
      updateEmployeeDataTable(response.data);
    },
    error: function (error) {
      console.error('Ajax request failed:', error);
    }
  });
}
function updateEmployeeDataTable(data) {
  var employeeDataTable = $('#employeeDataTable').DataTable({
    destroy: true, 
    data: data,
    columns: [
      
      { data: 'first_name' },
      { data: 'last_name' },
    ]
  });
}
</script>
<style>
  .loading-spinner{
  width:30px;
  height:30px;
  border:2px solid indigo;
  border-radius:50%;
  border-top-color:#0001;
  display:inline-block;
  animation:loadingspinner .7s linear infinite;
}
@keyframes loadingspinner{
  0%{
    transform:rotate(0deg)
  }
  100%{
    transform:rotate(360deg)
  }
}
#modal-employee-data {
    z-index: 1050; 
}
.modal-backdrop {
    z-index: 1040; 
}
</style>
@endsection
