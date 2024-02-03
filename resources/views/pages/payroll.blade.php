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
            <th>Timesheet</th>
            <th>Payroll Period</th>
            <th>payroll</th>
            <th>Upload Payroll</th>
          </tr>
        </thead>
        <tbody>
          <tr id="noDataMessage">
            <td class="text-center" colspan="4">No payroll data available</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>
@include('pages.modal_embed')
<script>
  var payroll_data;
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
      $.ajax({
        url: "{{ route('api/fetch_payroll') }}",
        method: 'POST',
        data:{
          client_id: clientId,
        },
        success: function(response) {
          displayEmployeeData(response.data);
          payroll_data =response.data;
          $('#employeeTable').show();
        },
        error: function(error) {
          console.error('Error fetching employee data:', error);
        }
      });
    });
    function displayClientList(clients) 
    {
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
    function displayEmployeeData(payrollData) {
      var employeeTableBody = $('#employeeTable tbody');
      employeeTableBody.empty(); 
      if (payrollData.length <= 0) {
        var row = '<tr><td  class="text-center" colspan="4">No payables data available</td></tr>';
        employeeTableBody.append(row);
      } else {
        // Employee data available, hide the message and display employees
        $('#noDataMessage').hide()
        payrollData.forEach(function(payroll) {
          var row = '<tr>';
          row += '<td><a href="#" onclick="openFileModal('+payroll.id+', \''+payroll.time_sheet_file+'\')">View File</a></td>';
          row += '<td><b>'+payroll.payroll_start+' - '+payroll.payroll_end+'</b></td>';
          if(payroll.payroll_file)
          {
            row +=  '<td><a href="#" onclick="openFileModal('+payroll.id+', \''+payroll.payroll_file+'\')">View File</a></td>';
          }
          else 
          {
            row +=  '<td>No Payroll Uploaded</td>';
          }
          row += '<td>';
          row += '<form action="{{ route("update_payroll") }}" method="get" id="form_'+payroll.id+'" enctype="multipart/form-data">';
          row +='<input type="file" name="payroll_file[]" id="btn_'+payroll.id+'" multiple onchange="toggleFileButtons(this)">';
          row += '@csrf';
          row +='<input type="hidden" name="payroll_id"  value="'+payroll.id+'" >';
          row += '</form>';
          row += '<button type="button" id="save" class="btn-action btn_' + payroll.id + ' btn_mini_action btn-xs rounded-pill btn-success mr-1" style="display:none;" onclick="saveFile(' + payroll.id + ','+payroll.client_id+')"><i class="fas fa-check"></i></button>';
          row += '<button type="button" onclick="cancelFileUpload('+payroll.id+')" id="cancel" class="btn-action btn_' + payroll.id + ' btn_mini_action btn-xs rounded-pill btn-danger" style="display:none;"><i class="fas fa-ban"></i></button>';
          row +=  '</td>';
          row +=  '</tr>';
          employeeTableBody.append(row);
        });
      }
    }
    
    
  });
  function toggleFileButtons(input) {
    var fileId = $(input).attr('id');
  
    var fileButtons = $('.'+fileId);

    if (input.files.length > 0) {
      fileButtons.show();
    } else {
      fileButtons.hide();
    }
  }
  
  function openFileModal(payroll_id, fileDirs) {
    var payroll = payroll_data.find(e => e.id === payroll_id);
        $('#fileModal .modal-dialog').removeClass('modal-sm').addClass('modal-xl'); 
        var fileDirArray = fileDirs.split('|');
        var modalBodyContent = '';
        var basePath = 'uploads/payroll/timesheet/';
        fileDirArray.forEach(function(fileDir) {
          var fileName = fileDir.replace(basePath, '');
            modalBodyContent += `
                <h5><b>Period: ${payroll.payroll_start} - ${payroll.payroll_end}<br> <b>File Name: </b>${fileName}</b></h5>
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
    function cancelFileUpload(input) {
    $('.btn_'+input).hide();
    $('#btn_'+input).val('');
  }
    function saveFile(payroll_id,client_id=null) {
      var formData = new FormData($('#form_' + payroll_id)[0]);
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
                  url: "{{ route('update_payroll') }}",
                  type: 'post',
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                      cancelFileUpload(payroll_id);
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
</script>
<style>
    #fileModal {
        z-index: 1050;
    }
</style>
@endsection
