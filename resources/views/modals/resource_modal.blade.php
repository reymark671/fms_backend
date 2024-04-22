
<div class="modal fade" id="resource_modal" tabindex="-1" role="dialog" aria-labelledby="resourceLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="mb-2">
                   <form action="" id="form_resource" enctype="multipart/form-data">
                   
                        <div class="has-name is-fullwidth mb-2">
                            <label for="title">Resource Title</label>
                                <input class="form-control" type="text" name="title" id="data_resources" placeholder="title of the resource file">
                        </div>
                        <div class="has-name is-fullwidth mb-2">
                            <label for="description">Description</label>
                                <input class="form-control" type="text" name="description" id="data_description" placeholder="description of the resource file">
                        </div>
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
                                <button type="button" class="button is-link btn_resource_upload">Submit</button>
                            </div>
                            <div class="control">
                                <button type="button" class="button is-link is-danger btn_resource_cancel">Cancel</button>
                            </div>
                        </div>
                   </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const fileInput = document.querySelector('#form_resource input[type=file]');
    fileInput.onchange = () => {
        const fileName = document.querySelector('#form_resource .file-name');
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
