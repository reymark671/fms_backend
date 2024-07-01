<div class="modal fade" id="service_code" tabindex="-1" role="dialog" aria-labelledby="payableLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Service Code Registry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <form id="service_code_form">
                        <input type="hidden" id="form_mode" name="form_mode" value="add">
                        <div class="mb-3">
                            <label for="code" class="form-label">Code</label>
                            <input type="text" class="form-control" id="code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label for="service_code_description" class="form-label">Description</label>
                            <textarea class="form-control" id="service_code_description" name="service_code_description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="service_code_category_id" class="form-label">Category</label>
                            <select class="form-control" id="service_code_category_id" name="service_code_category_id" required>
                                <option value="">Select a category</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
