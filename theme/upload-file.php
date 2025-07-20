<?php
include("function.php");
$maxfilesize = parseSizeToBytes(UPLOAD_MAX_SIZE);
?>
<div class="container-fluid">
    <?php include("offcanvas-adminmenu.php"); ?>
    <section class="row">
        <div class="d-none d-md-block col-md-2 overflow-hidden position-relative">
            <?php include("sidemenu-admin.php");?>
        </div>
        <div class="col-12 col-md-10 p-3" style="min-width: 0; flex: 1 1 auto;">
            <?php include("header-admin.php")?>
            <div class="d-block">
                <p class="display-3">Input New File</p>
                <hr noshade/>
                <div class="p-2">
                <form id="formNewFile" enctype="multipart/form-data">
                    <input type="hidden" name="actualFilename" id="actualFilename" required>
                    <input type="hidden" name="fileSize" id="fileSize" required>
                    <input type="hidden" name="fileExtension" id="fileExtension" required>
                    <input type="hidden" name="fileType" id="fileType" required>
                    <input type="hidden" name="sha256" id="filesha256" required>
                    <p class="h4">1. Select a File</h4>
                    <div class="mb-3">
                            <label for="myFile" class="form-label"></label>
                            <input type="file" class="form-control filepond" name="filepond">
                            <small>Max size <?= formatBytesHumanReadable($maxfilesize) ?>.</small>
                    </div>
                    <hr/>
                    <p class="h4">2. Input File Info</h4>
                    <div class="mb-3">
                        <label for="fileName" class="form-label">File Name</label>
                        <input type="text" id="fileName" name="fileName" class="form-control" maxlength="100" placeholder="Display Name" required>
                    </div>
                    <div class="mb-3">
                        <label for="formVersion" class="form-label">Version</label>
                        <input type="text" id="fileVersion" class="form-control" maxlength="10" required>
                    </div>
                    <div class="mb-3">
                        <label for="fileOS" class="form-label">Operating System</label>
                        <select name="fileOS" id="fileOS" class="form-select" required>
                            <option value="Windows 7">Windows 7</option>
                            <option value="Windows 8.1">Windows 8.1</option>
                            <option value="Windows 10">Windows 10</option>
                            <option value="Windows 11">Windows 11</option>
                            <option value="Apple OS X">Apple OS X</option>
                            <option value="Ubuntu">Ubuntu</option>
                            <option value="Linux">Linux</option>
                            <option value="Other" selected>Other</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fileArchitecture" class="form-label">Architecture</label>
                        <select name="fileArchitecture" id="fileArchitecture" class="form-select" required>
                            <option value="x86">x86</option>
                            <option value="x64">x64</option>
                            <option value="AMD64">AMD64</option>
                            <option value="ARM">ARM</option>
                            <option value="Other" selected>Other</option>
                        </select>
                        <small>File Architecture, not OS Arch.</small>
                    </div>
                    <div class="mb3">
                        <label for="filePublisher" class="form-label">Publisher</label>
                        <input type="text" id="filePublisher" class="form-control mb-2" maxlength="100" placeholder="Ex: Microsoft" required>
                        <input type="url" class="form-control" id="filePublisherLink" name="filePublisherLink" placeholder="Input publisher link if exist.">
                    </div>
                    <div class="mb-3">
                        <label for="fileCategories" class="form-label">Categories</label>
                        <input type="tags" id="tagif" name="fileCategories" class="form-control" required>
                        <small>Press <p class="d-inline bg-dark text-white rounded">Enter</p> to register each categories.</small>
                    </div>
                    <div class="mb-3">
                        <label for="fileInfo" class="form-label">File Information</label>
                        <textarea name="fileInfo" id="fileInfo" class="form-control" maxlength="300" rows="5" placeholder="Extra Information"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="fileClearance" class="form-label">Clearance</label>
                        <select name="fileClearance" id="fileClearance" class="form-select">
                            <option value="0" selected>Public</option>
                            <option value="1">Registered User</option>
                            <option value="2">Admin Only</option>
                        </select>
                        <small>Clearance dictates on who can download this file.</small>
                    </div>
                    <div class="mb-3 d-flex flex-row justify-content-end">
                        <button type="submit" id="btnFormFile" class="btn btn-primary">Upload</button>
                    </div>
                </form>
                </div>
                <script type="text/javascript">
                const token               = '<?= htmlspecialchars(base64_encode($global['auth_token']), ENT_QUOTES, 'UTF-8') ?>';
                const disallowedExtensions= <?= json_encode(BANNED_FILE_EXTENSION) ?>;
                const rejectList          = disallowedExtensions.map(ext => `.${ext}`);
                const maxFileSizeBytes    = <?= $maxfilesize ?>; // ensure this is bytes!
                let sha256UploadedFile = "";
                const tagif = document.getElementById("tagif");
                const tag = new Tagify(tagif, {
                    originalInputValueFormat: valuesArr => valuesArr.map(item => item.value).join(',')
                });
                //–– Register FilePond plugins
                FilePond.registerPlugin(
                    FilePondPluginFileValidateType,
                    FilePondPluginFileValidateSize
                );

                //–– Create FilePond instance
                const pond = FilePond.create(document.querySelector('.filepond'), {
                    instantUpload: false,
                    allowFileTypeValidation: true,
                    allowFileSizeValidation: true,
                    fileValidateTypeRejectList: rejectList,
                    maxFileSize: `${maxFileSizeBytes}`,       // numeric string in bytes
                    labelIdle: 'Drag & Drop or <span class="filepond--label-action">Browse</span>',
                    server: {
                    process: {
                        url: '<?= $global['full_url']?>/bin/api_upload.php',
                        method: 'POST',
                        headers: { Authorization: `Bearer ${token}` },
                        onload: resp => {
                        const { filename, sha256, type } = JSON.parse(resp);
                        document.getElementById('actualFilename').value = filename;
                        document.getElementById('filesha256').value = sha256;
                        document.getElementById('fileType').value = type;
                        return filename;  // critical for revert
                        },
                        onerror: err => {
                        console.error('Upload failed:', err);
                        alert('Upload error. Please try again.');
                        }
                    },
                    revert: {
                        url: '<?= $global['full_url']?>/bin/api_upload.php',
                        method: 'DELETE',
                        headers: { Authorization: `Bearer ${token}` },
                        onerror: err => console.error('Revert failed:', err)
                    }
                    }
                });

                //–– Capture file metadata
                pond.on('addfile', (err, fileItem) => {
                    if (err){
                        pond.removeFile(fileItem.id);
                        Swal.fire({
                        icon: 'error',
                        title: 'Upload Blocked',
                        text: err.body || `File to Large or Filepond Error`,
                        confirmButtonText: 'OK'
                        });
                        return;
                    }
                    const { name, size } = fileItem.file;
                    const ext = name.split('.').pop().toLowerCase();
                    document.getElementById('actualFilename').value = name;
                    document.getElementById('fileExtension').value = ext;
                    document.getElementById('fileSize').value = size;
                });

                //–– SweetAlert progress
                pond.on('processfileprogress', (_, progress) => {
                    const pct = Math.round(progress * 100);
                    Swal.update({ html: `<strong>${pct}%</strong>` });
                });
                pond.on('processfile', () => Swal.close());

                pond.on('removefile', (file) => {
                    console.log('File removed:', file);
                });

                //–– Form submit
                document.getElementById('formNewFile').addEventListener('submit', async function(e) {
                    e.preventDefault();
                    const tags = tag.value.map(t => t.value); // gives you an array
                    const tagString = tags.join(',');

                    // Example client-side URL validation
                    const urlVal = this.querySelector('[name="filePublisherLink"]').value;
                    if (urlVal && !isValidUrl(urlVal)) {
                    Swal.fire({ icon:'error', title:'Invalid URL' });
                    return;
                    }

                    // Show the modal with Cancel option **before** upload starts
                    const swalResult = Swal.fire({
                        title: 'Uploading…',
                        html: `<strong>0%</strong>`,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showCancelButton: true,
                        cancelButtonText: 'Cancel Upload',
                        showConfirmButton: false,
                        didOpen: () => Swal.showLoading()
                    });

                    // If user clicks Cancel
                    swalResult.then(res => {
                    if (res.dismiss === Swal.DismissReason.cancel) {
                        pond.abortProcessing();
                        pond.removeFile();       // cleanup
                        Swal.close();
                    }
                    });

                    try {
                        // Start upload (returns promise)
                        console.log('Saving Started');
                        await pond.processFiles();

                        //–– All files uploaded, now submit form data via AJAX
                        const payload = {
                            action: 'add_file',
                            data: {
                            actualName:  document.getElementById('actualFilename').value,
                            name:        document.getElementById('fileName').value,
                            size:        document.getElementById('fileSize').value,
                            extension:   document.getElementById('fileExtension').value,
                            type:       document.getElementById('fileType').value,
                            operating_system: document.getElementById('fileOS').value,
                            publisher:   document.getElementById('filePublisher').value,
                            publisher_link: document.getElementById('filePublisherLink').value,
                            categories: tagString,
                            sha256: document.getElementById('filesha256').value,
                            information: document.getElementById('fileInfo').value,
                            clearance:   document.getElementById('fileClearance').value,
                            architecture: document.getElementById('fileArchitecture').value,
                            version: document.getElementById('fileVersion').value
                            }
                        };

                        const response = await fetch('<?= $global['full_url']?>/bin/api_login.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type':'application/json',
                                Authorization:`Bearer ${token}`
                            },
                            body: JSON.stringify(payload),
                            credentials: 'include'
                        });

                        let responseText;
                        try {
                            responseText = await response.text();
                        } catch (innerErr) {
                            throw new Error(`Failed to parse response: ${innerErr}`);
                        }

                        if (!response.ok) {
                            let reason = responseText;
                            try {
                            const parsed = JSON.parse(responseText);
                            reason = parsed.error || JSON.stringify(parsed);
                            } catch (_) {
                            // Keep raw text as fallback
                            }
                            throw new Error(`API error ${response.status}: ${reason}`);
                        }

                        // on success, close Swal & optionally redirect or reset form
                        Swal.close();
                        this.reset();
                        pond.removeFile(); 
                        window.location = "<?= $global['full_url']."/index.php?r=files-admin"?>";
                    }catch (err) {
                        console.error('Submission failed:', err);
                        Swal.fire({ icon:'error', title:'Error', text:String(err) });
                        // If form submit fails, revert the file
                        pond.getFiles().forEach(file => pond.removeFile(file.id));
                    }
                });

                function isValidUrl(str) {
                    try { new URL(str); return true; }
                    catch { return false; }
                }

                </script>
                </div>
            </div>
        </div>
    </section>
</div>