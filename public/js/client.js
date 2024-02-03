
  var allClientsData;

  $(document).ready(function () {
    fetchClients('all');
    $('#modal-employee-data').modal();

    $('#status').on('change', submitForm);

    $(document).on('click', '.btn-action', function () {
      var btn = $(this);
      var clientId = btn.data('id');
      var clientFirstName = btn.data('first_name');
      var clientLastName = btn.data('last_name');
      var btnAction = btn.attr('id');

      function confirmAction(title, icon, callback) {
        Swal.fire({
          title: title,
          icon: icon,
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes",
        }).then(callback);
      }

      switch (btnAction) {
        case "delete":
          confirmAction("Are you sure you want to delete the record of " + clientFirstName + " " + clientLastName + " ?", "warning", function (result) {
            if (result.isConfirmed) {
              executeAction("{{ route('delete_client') }}", { id: clientId }, "Deleted!", "Record has been deleted.");
            }
          });
          break;

        case "view":
          var clientDetails = allClientsData.find(client => client.id === clientId);
          Swal.fire({
            title: "<strong>" + clientFirstName + " " + clientLastName + "</strong>",
            icon: "info",
            html: generateClientDetailsTable(clientDetails),
            showCloseButton: true,
            showCancelButton: false,
            focusConfirm: false,
            confirmButtonText: '<i class="fa fa-close"></i> Close',
          });
          break;

        case "approved":
          confirmAction("Are you sure you want to approve the record of " + clientFirstName + " " + clientLastName + " ?", "info", function (result) {
            if (result.isConfirmed) {
              $('#modal-loading').modal('show');
              executeAction("{{ route('approve_client') }}", { id: clientId }, "Updated!", "Record has been updated.");
            }
          });
          break;

        case "employee_data":
          $('#modal-employee-data').modal('show');
          fetchAllClientsData(clientId);
          break;
      }
    });

    $(document).on('click', '.close_modal', function () {
      $('#modal-employee-data').modal('hide');
    });

    function submitForm() {
      fetchClients($('#status').val());
    }

    function fetchClients(status) {
      $.ajax({
        url: "{{ route('fetch_clients') }}",
        method: 'GET',
        data: { status: status },
        success: function (response) {
          allClientsData = response;
          updateTable(response);
        },
        error: function (error) {
          console.error('Ajax request failed:', error);
        }
      });
    }

    function updateTable(data) {
      var tableBody = $('#clientsTableBody');
      tableBody.empty();

      $.each(data, function (index, client) {
        var statusClass = getStatusClass(client.status);

        var rowHtml = '<tr>' +
          '<td>' + client.first_name + '</td>' +
          '<td>' + client.last_name + '</td>' +
          '<td><div class="bg-' + statusClass + '"><center>' + getStatusLabel(client.status) + '</center></div></td>' +
          '<td>' +
          generateButton("approved", "success") +
          generateButton("view", "info mr-1 ml-1") +
          generateButton("delete", "danger") +
          generateButton("employee_data", "warning ml-1") +
          '</td>' +
          '</tr>';

        tableBody.append(rowHtml);
      });
    }

    function getStatusLabel(status) {
      return (status > 0) ? "Active" : ((status == 0) ? "Pending" : "Declined");
    }

    function getStatusClass(status) {
      return (status > 0) ? "success" : ((status == 0) ? "info" : "danger");
    }

    function generateButton(action, buttonClass) {
      return '<button type="button" id="' + action + '" data-id="' + clientId + '" data-first_name="' + client.first_name +
        '" data-last_name="' + client.last_name + '" class="btn-action btn-sm rounded-pill btn-' + buttonClass +
        '"><i class="fas ' + getButtonIcon(action) + '"></i></button>';
    }

    function getButtonIcon(action) {
      switch (action) {
        case "delete": return "fa-trash";
        case "view": return "fa-eye";
        case "approved": return "fa-check";
        case "employee_data": return "fa-book";
        default: return "";
      }
    }

    function executeAction(url, data, successTitle, successText) {
      $.ajax({
        url: url,
        method: 'POST',
        data: data,
        headers: { 'X-CSRF-TOKEN': $('#logout-form').find('input[name="_token"]').val() },
        success: function (response) {
          if (successTitle && successText) {
            Swal.fire({ title: successTitle, text: successText, icon: "success" });
          }
          fetchClients('all');
        },
        error: function (error) {
          Swal.fire({ title: "Error!", text: "Record not found.", icon: "error" });
        },
        complete: function () {
          if (btnAction === "approved") {
            $('#modal-loading').modal('hide');
          }
        }
      });
    }

    function generateClientDetailsTable(clientDetails) {
      var tableHtml = '<table class="table table-striped">';
      tableHtml += '<tbody>';
      tableHtml += generateClientDetailsRow("SS Number", clientDetails.ss_number);
      tableHtml += generateClientDetailsRow("Email Address", clientDetails.email);
      tableHtml += generateClientDetailsRow("Contact Number", clientDetails.contact_number);
      tableHtml += generateClientDetailsRow("Address", clientDetails.address);
      tableHtml += generateClientDetailsRow("City", clientDetails.city);
      tableHtml += generateClientDetailsRow("State", clientDetails.state);
      tableHtml += generateClientDetailsRow("Postal Code", clientDetails.zip_code);
      tableHtml += '</tbody>';
      tableHtml += '</table>';
      return tableHtml;
    }

    function generateClientDetailsRow(label, value) {
      return '<tr><th scope="row" class="text-right">' + label + ':</th><td class="text-left">' + value + '</td></tr>';
    }

    function fetchAllClientsData(clientId) {
      $.ajax({
        url: "{{ route('fetch_employees') }}",
        method: 'POST',
        data: { client_id: clientId },
        headers: { 'X-CSRF-TOKEN': $('#logout-form').find('input[name="_token"]').val() },
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
  });

