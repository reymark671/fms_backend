<div class="modal fade" id="client_spending_plan" tabindex="-1" role="dialog" aria-labelledby="payableLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Client Spending Plan Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                <form action="{{ route('client-spending-plan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="client_id" class="form-label">Client</label>
                        <select name="client_id" id="client_id" class="form-control" required>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->first_name }} {{ $client->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="total_budget" class="form-label">Total Budget</label>
                        <input type="number" step="0.01" name="total_budget" id="total_budget" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_budget" class="form-label">Date From</label>
                        <input type="date" name="from" id="from" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="total_budget" class="form-label">Date To</label>
                        <input type="date" name="to" id="to" class="form-control" required>
                    </div>
                    <div id="service_codes">
                        <div class="service-code-item">
                            <div class="mb-3">
                                <label for="service_code_id_0" class="form-label">Service Code</label>
                                <select name="service_codes[0][service_code_id]" id="service_code_id_0" class="form-control" required>
                                    @foreach($serviceCodes as $serviceCode)
                                        <option value="{{ $serviceCode->id }}">{{ $serviceCode->code }} - {{ $serviceCode->service_code_description }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="allocated_budget_0" class="form-label">Allocated Budget</label>
                                <input type="number" step="0.01" name="service_codes[0][allocated_budget]" id="allocated_budget_0" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add_service_code" class="btn btn-secondary">Add Another Service Code</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
