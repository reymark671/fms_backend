@extends('layouts.app')
@section('content')
<div class="">
    <div class="row">
    </div>
    <div class="row  m-4">
            <div class="col-md-12">
                <table class="table is-striped" id="spending_plan_table">
                    <thead>
                    <tr>
                        
                        <th>Client Name</th>
                        <th>Total Budget</th>
                        <th>Total Budget Used</th>
                        <th>Total Funds Available</th>
                        <th>UCI</th>
                        <th>SDP YEAR</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if($clientSpendingPlans)
                            @foreach($clientSpendingPlans as $clientplan)
                            <tr>
                                <td>{{ $clientplan->client->first_name }} {{ $clientplan->client->last_name }}</td>
                                <td>{{ number_format($clientplan->total_budget, 2) }}</td>
                                <td>{{ number_format($clientplan->total_budget_used, 2) }}</td>
                                <td>{{ number_format($clientplan->total_budget - $clientplan->total_budget_used, 2) }}</td>
                                <td>{{ $clientplan->client->ss_number }}</td>
                                <td>{{ date('M d, Y', strtotime($clientplan->from)) }} - {{ date('M d, Y', strtotime($clientplan->to)) }}</td> 
                                <td>
                                <a href="{{ route('client-spending-plan.download', $clientplan->id) }}" class="btn btn-primary">Download Summary</a>
                            </td>
                            </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
    </div>
    @include('modals.client_spending_plan')
</div>
<script>
    $(document).ready(function() {
        var csrf= $('#logout-form').find('input[name="_token"]').val();
        var table = new DataTable('#spending_plan_table',{
            dom: 'Bfrtip',
            order: [[0, 'desc']],
            select: true,
            buttons: [
            {
                text: 'Add Spending Plan',
                className: 'btn-primary p-2 m-4  rounded-lg btn_add_service_code',
               
            }
        ]
        });
        $('.btn_add_service_code').on('click', function () {
            $('#client_spending_plan').modal('show');
        });
        $('#add_service_code').on('click', function () {
            var serviceCodeIndex = $('#service_codes .service-code-item').length;
            var serviceCodeDiv = `
                <div class="service-code-item">
                    <div class="mb-3">
                        <label for="service_code_id_${serviceCodeIndex}" class="form-label">Service Code</label>
                        <select name="service_codes[${serviceCodeIndex}][service_code_id]" id="service_code_id_${serviceCodeIndex}" class="form-control" required>
                            @foreach($serviceCodes as $serviceCode)
                                <option value="{{ $serviceCode->id }}">{{ $serviceCode->code }} - {{ $serviceCode->service_code_description }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="allocated_budget_${serviceCodeIndex}" class="form-label">Allocated Budget</label>
                        <input type="number" step="0.01" name="service_codes[${serviceCodeIndex}][allocated_budget]" id="allocated_budget_${serviceCodeIndex}" class="form-control" required>
                    </div>
                </div>
            `;
            $('#service_codes').append(serviceCodeDiv);
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
