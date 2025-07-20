<?php
if(!isset($_GET['f']) || empty($_GET['f'])) header("Location = ".$global('full_url'));
$id = base64_decode($_GET['f']);
require_once("bin/entity/file.php");
require_once("theme/function.php");
$file = new file();
$file->setId($id);
$file->load();
$downloadCpunt = $file->getDownloadCount() ?? "none";
$filetype = $file->getExtension();
if($file->getFileType() != 'undefined') $filetype .= " - ".$file->getFileType();
include("header-public.php");
?>
<div class="container-fluid overflow-hidden">
<div class="row flex-row">
    <div class="col-12 pt-5">
        <div class="d-block text-center display-4">File Info</div>
    </div>
        <div class="d-sm-none d-md-block col-md-2 col-lg-2">

        </div>
        <div class="col-sm-12 col-md-8 col-lg-8">
            <div class="row border border-left-1 border-right-1 border-secondary p-2">
                <div class="col-sm-12 col-md-4 col-lg-3">
                    <div class="d-flex justify-content-center align-items-center border border-1 border-primary" style="min-width: 100px; min-height: 100px;">

                    </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-9">
                    <form id="formFile" action="#" class="">
                        <div class="row gap-3">
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileName" class="form-label fw-bold text-dark">File Name</label>
                        <input type="text" readonly id="fileName" name="fileName" class="form-control-plaintext" value="<?= $file->getName()?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileName" class="form-label fw-bold text-dark">File Type</label>
                        <input type="text" readonly id="fileName" name="fileName" class="form-control-plaintext" value="<?= $filetype?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileSize" class="form-label fw-bold text-dark">File Size</label>
                        <input type="text" readonly id="fileSize" name="fileSize" class="form-control-plaintext" value="<?= formatBytesHumanReadable($file->getFileSize())?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileArchitecture" class="form-label fw-bold text-dark">Architecture</label>
                        <input type="text" readonly id="fileArchitecture" name="fileArchitecture" class="form-control-plaintext" value="<?= $file->getArchitecture()?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileVersion" class="form-label fw-bold text-dark">Publisher</label>
                        <input type="text" readonly id="fileVersion" name="fileVersion" class="form-control-plaintext" value="<?= $file->getPublisher()?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileVersion" class="form-label fw-bold text-dark">Version</label>
                        <input type="text" readonly id="fileVersion" name="fileVersion" class="form-control-plaintext" value="<?= $file->getVersion()?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileTime" class="form-label fw-bold text-dark">Time Uploaded</label>
                        <input type="text" readonly class="form-control-plaintext" id="fileTime" placeholder="time" value="<?= $file->getTimestamp()?>">
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileCategories" class="form-label fw-bold text-dark">Category</label>
                        <textarea id="fileCategories" readonly class="form-control-plaintext" placeholder="Categories"><?= $file->getCategories()?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileinfo" class="form-label fw-bold text-dark">Info</label>
                        <textarea name="fileInfo" readonly id="fileInfo" class="form-control-plaintext"><?= $file->getInformation()?></textarea>
                    </div>
                    <div class="col-sm-12 col-md-5 mb-2">
                        <label for="fileDownload" class="form-label fw-bold text-dark">Download Count</label>
                        <input type="text" readonly class="form-control-plaintext" value="<?= $downloadCpunt?> times">
                    </div>
                    </div>
                    </form>
                </div>
                <div class="col-12 mb-2">
                    <div class="mb-2 bg-info p-2">
                        <p class="display-6"><i class="bi bi-lock"></i> File Security</p>
                        <div class="row">
                            <div class="col-4 p-2">
                                <p class="text-center">True File Name</p>
                            </div>
                            <div class="col-8 p-2">
                                <p class="text-wrap text-center"><?= $file->getFileName()?></p>
                            </div>
                            <div class="col-4 p-2">
                                <p class="text-center">sha256</p>
                            </div>
                            <div class="col-8 p-2">
                                <p class="text-wrap overflow-hidden"><?= $file->getSha256()?></p>
                            </div>
                            <div class="col-4 p-2">
                                <p class="text-center">Integrity</p>
                            </div>
                            <div class="col-8 p-2">
                                <p class="text-center"><i class='bi bi-question'></i>Unknown</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mb-2" id="lockContainer"></div>
                <div class="col-12 mb-2 p-2">
                    <div class="bg-warning text-dark text-center">
                        By Downloading this file, You are agreeing with our <a href="#">Terms and Conditions.</a>
                    </div>
                    <div class="p-4 text-center" id="downloadContainer">
                        <?php
                        if(!$file->getNeedClearance()){
                        ?>
                        <button id="btnDownload" class="btn btn-lg btn-primary"><i class="bi bi-arrow-down"></i> Download</button>
                        <script type="text/javascript">
                                                $(document).ready(function () {
                                                    const token = '<?= base64_encode($global['auth_token'])?>';
                                                    $('#btnDownload').on('click', function () {
                                                        // Clear previous elements if they exist
                                                        $('#spinnerWrapper').remove();
                                                        $('#downloadProgressContainer').remove();

                                                        // Create spinner
                                                        const spinnerWrapper = $(`
                                                            <div id="spinnerWrapper" class="text-center mb-3">
                                                                <div class="spinner-border text-primary" role="status">
                                                                    Downloading...
                                                                </div>
                                                            </div>
                                                        `);

                                                        // Create progress bar
                                                        const progressBarContainer = $(`
                                                            <div id="downloadProgressContainer" class="progress" style="height: 25px;">
                                                                <div id="downloadProgressBar"
                                                                    class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                                    role="progressbar" style="width: 0%;" aria-valuenow="0"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                    0%
                                                                </div>
                                                            </div>
                                                        `);

                                                        // Append elements to DOM (you can choose a specific container)
                                                        $('#loader').append(spinnerWrapper).append(progressBarContainer);

                                                        const fileID = <?= $file->getId()?>;
                                                        $.ajax({
                                                            type: "POST",
                                                            url: '<?= $global['full_url']?>/bin/api_download.php',
                                                            contentType: "application/json",
                                                            data: JSON.stringify({ fileID: Number(fileID) }),
                                                            xhrFields: {
                                                                responseType: 'blob'
                                                            },
                                                            xhr: function () {
                                                                const xhr = new XMLHttpRequest();
                                                                xhr.onloadstart = function () {
                                                                    startTime = performance.now();
                                                                    $('#downloadProgressBar').text('Starting...');
                                                                };
                                                                xhr.onprogress = function (event) {
                                                                    if (event.lengthComputable) {
                                                                        const now = performance.now();
                                                                        const elapsedSec = (now - startTime) / 1000;
                                                                        const kbLoaded = event.loaded / 1024;
                                                                        const speedKbps = (kbLoaded / elapsedSec).toFixed(2);

                                                                        const percent = ((event.loaded / event.total) * 100).toFixed(2);
                                                                        $('#downloadContainer').hide();
                                                                        $('#downloadProgressBar')
                                                                            .css('width', percent + '%')
                                                                            .attr('aria-valuenow', percent)
                                                                            .text(`${percent}% - ${speedKbps} KB/s`);
                                                                    } else {
                                                                        $('#downloadProgressBar').text('Streaming...');
                                                                    }
                                                                };

                                                                return xhr;
                                                            },
                                                            success: function (blob, status, xhr) {
                                                                let filename = '<?= str_replace(" ", "-", $file->getName()).".".$file->getExtension()?>';

                                                                const url = window.URL.createObjectURL(blob);
                                                                const a = document.createElement('a');
                                                                a.href = url;
                                                                a.download = filename;
                                                                document.body.appendChild(a);
                                                                a.click();
                                                                a.remove();
                                                                window.URL.revokeObjectURL(url);
                                                                $('#spinnerWrapper').remove();
                                                                $('#downloadProgressBar')
                                                                    .removeClass('progress-bar-animated')
                                                                    .addClass('bg-primary')
                                                                    .text('Completed!');

                                                            },
                                                            error: function (xhr) {
                                                                $('#spinnerWrapper').remove();
                                                                    $('#downloadProgressBar')
                                                                        .removeClass('bg-success')
                                                                        .addClass('bg-danger')
                                                                        .text('Download failed.');

                                                            }
                                                        });
                                                    });
                                                });
                                            </script>
                        <?php
                        } else {
                        ?>
                            <div class="p-2 ps-3 my-2">
                                <p class="h6 fw-bold text-danger"><i class="bi bi-key"></i> Protected</p>
                                <?php
                                    if(isset($global['session_id']) || !empty($global['session_id'])){
                                ?>   
                                        <?php
                                            if($global['session_user_level'] < $file->getClearanceLevel()){
                                        ?>
                                        <div class="alert alert-danger" role="alert">
                                            You don't have enough clearance to download this file!
                                        </div>
                                        <?php
                                            }else {

                                                ?>
                                                
                                                <button id="btnDownload" class="btn btn-lg btn-primary"><i class="bi bi-arrow-down"></i> Download</button>
                                            <script type="text/javascript">
                                                $(document).ready(function () {
                                                    const token = '<?= base64_encode($global['auth_token'])?>';
                                                    $('#btnDownload').on('click', function () {
                                                        // Clear previous elements if they exist
                                                        $('#spinnerWrapper').remove();
                                                        $('#downloadProgressContainer').remove();

                                                        // Create spinner
                                                        const spinnerWrapper = $(`
                                                            <div id="spinnerWrapper" class="text-center mb-3">
                                                                <div class="spinner-border text-primary" role="status">
                                                                    Downloading...
                                                                </div>
                                                            </div>
                                                        `);

                                                        // Create progress bar
                                                        const progressBarContainer = $(`
                                                            <div id="downloadProgressContainer" class="progress" style="height: 25px;">
                                                                <div id="downloadProgressBar"
                                                                    class="progress-bar progress-bar-striped progress-bar-animated bg-success"
                                                                    role="progressbar" style="width: 0%;" aria-valuenow="0"
                                                                    aria-valuemin="0" aria-valuemax="100">
                                                                    0%
                                                                </div>
                                                            </div>
                                                        `);

                                                        // Append elements to DOM (you can choose a specific container)
                                                        $('#loader').append(spinnerWrapper).append(progressBarContainer);

                                                        const fileID = <?= $file->getId()?>;
                                                        $.ajax({
                                                            type: "POST",
                                                            url: '<?= $global['full_url']?>/bin/api_download.php',
                                                            contentType: "application/json",
                                                            headers: {
                                                                "Authorization": "Bearer " + token
                                                            },
                                                            credentials: 'include',
                                                            data: JSON.stringify({ fileID: Number(fileID) }),
                                                            xhrFields: {
                                                                responseType: 'blob'
                                                            },
                                                            xhr: function () {
                                                                const xhr = new XMLHttpRequest();
                                                                xhr.onloadstart = function () {
                                                                    startTime = performance.now();
                                                                    $('#downloadProgressBar').text('Starting...');
                                                                };
                                                                xhr.onprogress = function (event) {
                                                                    if (event.lengthComputable) {
                                                                        const now = performance.now();
                                                                        const elapsedSec = (now - startTime) / 1000;
                                                                        const kbLoaded = event.loaded / 1024;
                                                                        const speedKbps = (kbLoaded / elapsedSec).toFixed(2);

                                                                        const percent = ((event.loaded / event.total) * 100).toFixed(2);
                                                                        $('#downloadContainer').hide();
                                                                        $('#downloadProgressBar')
                                                                            .css('width', percent + '%')
                                                                            .attr('aria-valuenow', percent)
                                                                            .text(`${percent}% - ${speedKbps} KB/s`);
                                                                    } else {
                                                                        $('#downloadProgressBar').text('Streaming...');
                                                                    }
                                                                };

                                                                return xhr;
                                                            },
                                                            success: function (blob, status, xhr) {
                                                                let filename = '<?= str_replace(" ", "-", $file->getName()).".".$file->getExtension()?>';

                                                                const url = window.URL.createObjectURL(blob);
                                                                const a = document.createElement('a');
                                                                a.href = url;
                                                                a.download = filename;
                                                                document.body.appendChild(a);
                                                                a.click();
                                                                a.remove();
                                                                window.URL.revokeObjectURL(url);
                                                                $('#spinnerWrapper').remove();
                                                                $('#downloadProgressBar')
                                                                    .removeClass('progress-bar-animated')
                                                                    .addClass('bg-primary')
                                                                    .text('Completed!');

                                                            },
                                                            error: function (xhr) {
                                                                $('#spinnerWrapper').remove();
                                                                    $('#downloadProgressBar')
                                                                        .removeClass('bg-success')
                                                                        .addClass('bg-danger')
                                                                        .text('Download failed.');

                                                            }
                                                        });
                                                    });
                                                });
                                            </script>
                                                <?php
                                            }
                                        } else{
                                        ?>
                                        <div class="text-center overflow-hidden text-wrap">This file is protected, you need to Login to download this file</div>
                                        <p class="p-2">Clearance Level : <?= $file->getClearanceLevel()?></p>
                                        <a href="<?=$global['full_url']?>/index.php?r=login" class="btn btn-primary text-white"><i class="bi bi-box-arrow-in"></i> Login</a>
                                        <?php
                                        }
                                        ?>
                            </div>    
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="col-12 mb-2 p-2 mb-3">
                        <div id="loader">
                        </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript">
    const taginput = document.getElementById('fileCategories');
    const tags = new Tagify(taginput, {
        readonly: true,
        whitelist: (taginput.value || '').split(',').map(tag => tag.trim()).filter(tag => tag.length > 0)
    });
<?php
if(isset($global['session_id']) || !empty($global['session_id'])){
    if($global['session_user_level'] >= 2){
?>
    $('#fileName').prop('readonly', false);
<?php
    }
}
?>
</script>
</div>
<?php 
include("footer-public.php");
?>