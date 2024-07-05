<div class="modal fade" id="client_assignment" tabindex="-1" role="dialog" aria-labelledby="payableLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Client Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <form>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Client</label>
                            <select class="form-control" id="client_name_select" name="client_name_select[]" multiple="multiple" required>
                                <option value="">Select Client</option>
                            </select>
                            <input type="hidden" id="coordinator_id"></input>
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <button class="btn btn-info me-md-2 btn_submit_assignments" type="button">Submit</button>
                            <button class="btn btn-danger" data-bs-dismiss="modal" type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
