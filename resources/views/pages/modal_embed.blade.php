
<div class="modal fade" id="payroll_modal" tabindex="-1" role="dialog" aria-labelledby="payrollLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                <div class="mb-2">
                    <form enctype="multipart/form-data" id="payroll_form">
                        <label for="from_date" class="form-label">From</label>
                        <input type="date" name="from_date" class="form-control from_date" >
                        <label for="to_date" class="form-label">To</label>
                        <input type="date" name="to_date"  class="form-control to_date">
                        <label for="recipient" class="form-label">Recipient</label>
                        <select name="recipient" id="recipientSelect" class="form-control">
                        <option value="0">Select Recipient</option>
                        </select>
                        <label for="provider"  class="form-label">Provider</label>
                        <select name="provider" id="providerSelect" class="form-control select">
                            <option value="0">No Service Providers</option>
                        </select>
                        <label for="payroll_file" class="form-label">Upload</label>
                        <input type="file" name="payroll[]" class="form-control file mb-2" id="payroll[]" multiple>
                        <button  class="form-control btn-primary mt-4 btn_submit_payroll" type="button">
                                Submit
                        </button>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
