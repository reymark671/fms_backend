@extends('layouts.app')

@section('content')
<div class="">
  <div class="row">
    <div class="col-md-4 mr-0">
    <div class="header p-2">
    <span><center><b>Clients List</b></center></span>
    </div>
      <div class="list-group " id="clientList">
        <span>No Clients available</span>
      </div>
    </div>
    <div class="col-md-8 ml-0">
      <table class="table table-bordered" id="employeeTable">
        <thead>
          <tr>
            <th>Description</th>
            <th>Uploaded Files</th>
            <th>Confirmation Upload</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr id="noDataMessage">
            <td class="text-center" colspan="4">No payables data available</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('pages.modal_embed')
<script>
    var employeesData;
  $(document).ready(function() {
    // Initial state: Hide the employee table
    // $('#employeeTable').hide();

    // Fetch clients and populate the list
    $.ajax({
      url: "{{ route('fetch_clients') }}",
      method: 'GET',
      data:{
        status: "all"
      },
      success: function(clients) {
        displayClientList(clients);
      },
      error: function(error) {
        console.error('Error fetching clients:', error);
      }
    });

    // Client click event
    $('#clientList').on('click', 'a', function() {
      var clientId = $(this).data('client-id');
  
      // AJAX call to fetch employee data based on clientId
      $.ajax({
        url: "{{ route('fetch_payables') }}",
        method: 'GET',
        headers: {
          'X-CSRF-TOKEN': getCsrfToken()
        },
        data:{
          client_id: clientId,
        },
        success: function(response) {
          // Display employee data in the table
          var employeeData = response.data;
          employeesData = response.data;
          displayEmployeeData(employeeData);

          // Show the employee table
          $('#employeeTable').show();
        },
        error: function(error) {
          console.error('Error fetching employee data:', error);
        }
      });
    });

    // Function to display client list
    function displayClientList(clients) {
    var clientList = $('#clientList');
    clientList.empty();

    if (clients.length <= 0) {
        var link = '<a href="#" class="list-group-item list-group-item-action" data-client-id="">No Clients Available to view</a>';
        clientList.append(link);
    } else {
        clients.forEach(function (client) {
            var link = $('<a>', {
                href: '#',
                class: 'list-group-item list-group-item-action',
                'data-client-id': client.id,
                text: client.first_name + " " + client.last_name
            });

            link.click(function () {
                clientList.find('a').removeClass('active');
                link.addClass('active');
            });

            clientList.append(link);
        });
    }
}

    // Function to display employee data in the table
   
  

    function getCsrfToken() {
      return $('#logout-form').find('input[name="_token"]').val();
    }
  });
  function getCsrfToken() {
      return $('#logout-form').find('input[name="_token"]').val();
    }
  function toggleFileButtons(input) {
    var fileId = $(input).attr('id');
    var fileButtons = $('.'+fileId);
    if (input.files.length > 0) {
      fileButtons.show();
    } else {
      fileButtons.hide();
    }
  }
  function cancelFileUpload(input) {
    $(input).val('');
    $('.btn_id-'+input).hide();
    $('#btn_id-'+input).val('');
  }
  function saveFile(employeeId,client_id=null) {
    var formData = new FormData($('#form_'+employeeId)[0]);

    
          Swal.fire({
            title: "Are you sure you want to save this file?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Confirm"
          }).then((result) => {
            if (result.isConfirmed) {
              $.ajax({
                  url: "{{ route('update_payables') }}",
                  type: 'POST',
                  data: formData,
                  headers: {
                    'X-CSRF-TOKEN': getCsrfToken()
                  },
                  processData: false,
                  contentType: false,
                  success: function(response) {
                      cancelFileUpload(employeeId);
                      Swal.fire({
                        position: "top-end",
                        icon: "success",
                        title: "saved",
                        showConfirmButton: false,
                        timer: 1500
                      });
                      $('#clientList a[data-client-id="' + client_id + '"]').click();
                  },
                  error: function(error) {
                      // Handle the error response
                      console.error('Error uploading file:', error);
                  }
              });
              
            }
          });
    
  }
  function openFileModal(employee_id, fileDirs) {
    var employee = employeesData.find(e => e.id === employee_id);
        $('#fileModal .modal-dialog').removeClass('modal-sm').addClass('modal-xl'); 
        var fileDirArray = fileDirs.split('|');
        var modalBodyContent = '';
        var basePath = 'uploads/employees/';
        fileDirArray.forEach(function(fileDir) {
          var fileName = fileDir.replace(basePath, '');
            modalBodyContent += `
                <h5><b>Description: </b>${employee.description} <br> <b>File Name: </b>${fileName}</h5>
                <!-- Add other content based on your requirements -->
                ${isPdfFile(fileDir) ? 
                    `<iframe src="${fileDir}" width="100%" height="500px"></iframe>` :
                    `<img src="${fileDir}" class="img-fluid" alt="Uploaded File">`
                }

                <!-- Centered Download button -->
                <div class="text-center mt-2">
                    <a href="${fileDir}" download="file_name" class="btn btn-primary">Download File</a>
                </div>
                <hr>
            `;
        });

        $('#fileModal .modal-body').html(modalBodyContent);
        $('#fileModal').modal('show');
    }

    function isPdfFile(fileUrl) {
        return fileUrl.toLowerCase().endsWith('.pdf');
    }
    function displayEmployeeData(employeeData) {
      var employeeTableBody = $('#employeeTable tbody');
      employeeTableBody.empty(); 
      if (employeeData.length <= 0) {
        var row = '<tr><td  class="text-center" colspan="4">No payables data available</td></tr>';
        employeeTableBody.append(row);
      } else {
        // Employee data available, hide the message and display employees
        $('#noDataMessage').hide()
        employeeData.forEach(function(payables) {
          var row = '<tr>';
          row += '<td>' + payables.description+'</td>';
          row += '<td><a href="#" onclick="openFileModal('+payables.id+', \''+payables.file_dir+'\')">View File</a></td>';
          // row +=  '<td><a href="#" onclick="openFileModal('+payables.id+', \''+payables.response_file+'\')">View File</a></td>';
          if (payables.response_file !== null) {
            row += '<td><a href="#" onclick="openFileModal(' + payables.id + ', \'' + payables.response_file + '\')">View File</a></td>';
          } else {
            row += '<td>No file uploaded yet</td>';
          }
          row += '<td>';
          row += '<form action="{{ route("update_payables") }}" method="post" id="form_'+payables.id+'" enctype="multipart/form-data">';
          row +='<input type="file" name="upload_file[]" id="btn_id-'+payables.id+'" multiple onchange="toggleFileButtons(this)">';
          row += '@csrf';
          row +='<input type="hidden" name="payable_id"  value="'+payables.id+'" >';
          row += '</form>';
          row += '<button type="button" id="save" class="btn-action btn_id-' + payables.id + ' btn_mini_action btn-xs rounded-pill btn-success mr-1" style="display:none;" onclick="saveFile(' + payables.id + ','+payables.client_id+')"><i class="fas fa-check"></i></button>';
          row += '<button type="button" onclick="cancelFileUpload('+payables.id+')" id="cancel" class="btn-action btn_id-' + payables.id + ' btn_mini_action btn-xs rounded-pill btn-danger" style="display:none;"><i class="fas fa-ban"></i></button>';
          row +=  '</td>';
          row +=  '</tr>';
          employeeTableBody.append(row);
        });
      }
    }
</script>

<style>
    #fileModal {
        z-index: 1050;
    }
</style>
@endsection
