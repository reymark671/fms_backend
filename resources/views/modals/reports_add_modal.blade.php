
<div class="modal fade" id="report_modal" tabindex="-1" role="dialog" aria-labelledby="reportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                
                <div class="mb-2">
                   <form action="" id="form_report" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Report Destinaton Portal</label>
                            <select class="form-control" id="report_destination" name="report_destination" required>
                                <option value="">Select Destination Portal</option>
                                <option value="1">Coordinators</option>
                                <option value="2">Employees</option>
                                <option value="3">Clients</option>
                            </select>
                            <input type="hidden" id="coordinator_id"></input>
                        </div>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Select Account</label>
                            <select class="form-control" id="destination_account" name="destination_account[]" multiple="multiple" required>
                                <option value="">Select Account</option>
                            </select>
                            <input type="hidden" id="coordinator_id"></input>
                        </div>
                        <div class="has-name is-fullwidth mb-2">
                            <label for="title">Report Type</label>
                                <input class="form-control" type="text" name="report_type" id="report_type" placeholder="type of the report file">
                        </div>
                        <div class="has-name is-fullwidth mb-2">
                            <label for="description">Description</label>
                                <input class="form-control" type="text" name="description" id="description" placeholder="description of the report file">
                        </div>
                        <div class="has-name is-fullwidth mb-2">
                            <label for="report_date">Report Date</label>
                                <input class="form-control" type="date" name="report_date" id="report_date" placeholder="date of the report file">
                        </div>
                     
                        <div class="file has-name is-fullwidth">
                            <label class="file-label">
                                <input class="file-input" type="file" name="report_file[]" multiple>
                                <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="file-label">
                                    Choose a file…
                                </span>
                                </span>
                                <span class="file-name">
                                    No file selected
                                </span>
                            </label>
                        </div>
                        <div class="field is-grouped is-grouped-centered">
                            <div class="control">
                                <button type="button" class="button is-link btn_report_upload">Submit</button>
                            </div>
                            <div class="control">
                                <button type="button" class="button is-link is-danger btn_report_cancel">Cancel</button>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const fileInput = document.querySelector('#form_report input[type=file]');
    fileInput.onchange = () => {
        const fileName = document.querySelector('#form_report .file-name');
        const filesCount = fileInput.files.length;

        if (filesCount > 1) {
            fileName.textContent = filesCount + (filesCount > 1 ? ' files' : ' file') + ' selected';
        } 
        else if(filesCount==1)
        {
            fileName.textContent = fileInput.files[0].name;
        }
        else {
            fileName.textContent = 'No file selected';
        }
    }
</script>
