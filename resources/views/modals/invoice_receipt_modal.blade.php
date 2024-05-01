
<div class="modal fade" id="invoice_receipt" tabindex="-1" role="dialog" aria-labelledby="payableLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="mb-2">
                   <form action="" id="form_payable" enctype="multipart/form-data">
                   <label class="label">Please Select File</label>
                        <div class="file has-name is-fullwidth">
                            <label class="file-label">
                                <input class="file-input" type="file" name="upload_file[]" multiple>
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
                                <button type="button" class="button is-link btn_invoice_receipt_upload">Submit</button>
                            </div>
                            <div class="control">
                                <button type="button" class="button is-link is-danger btn_invoice_receipt_cancel">Cancel</button>
                            </div>
                        </div>
                        <input type="hidden" name="invoice_id" id="invoice_id" value="">
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const fileInput = document.querySelector('#form_payable input[type=file]');
    fileInput.onchange = () => {
        const fileName = document.querySelector('#form_payable .file-name');
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
