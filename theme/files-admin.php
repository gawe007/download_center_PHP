<?php
require_once("bin/entity/file.php");
require_once("bin/entity/user.php");
$file = new file();
$data = $file->getAllFilesNotDeleted();
?>
<div class="container-fluid">
    <?php include("offcanvas-adminmenu.php"); ?>
    <section class="row">
        <div class="col-12 col-md-10 p-3" style="min-width: 0; flex: 1 1 auto;">
            <?php include("header-admin.php")?>
            <div class="d-block">
                <p class="display-3">File Management</p>
                <hr noshade/>
                <ul class="nav">
                    <li class="nav-item"><a href="<?= $global['full_url']?>/index.php?r=upload-new-file" class="nav-link">+ Upload New File</a></li>
                    <li class="nav-item"><a href="<?= $global['full_url']?>/index.php?r=deleted-files" class="nav-link"><i class="bi bi-trash"></i> Deleted Files</a></li>
                </ul>
                <div class="p-2 table-responsive">
                <table id="filesTable" class="table table-striped m-2 w-100 mb-2">
                    <caption>Table Files</caption>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Publisher</th>
                            <th>Download</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(count($data) < 1){
                        echo "<tr><td class='text-center' colspan='5'>No Data</td></tr>";
                    }else{
                        foreach($data as $f){
                            $user = new user(null);
                            $user->setId($f['id_user']);
                            $user->load();
                            echo "<tr>
                                <td>{$f['id']}</td>
                                <td>{$user->getName()}</td>
                                <td>{$f['name']}</td>
                                <td>{$f['publisher']}</td>
                                <td>{$f['downloaded_count']}</td>
                                </tr>
                            ";
                        }
                    }

                    ?>
                    </tbody>
                    </table>
                </div>
                <div class="card mb-3" id="editField" style="display: none;">
                    <div class="card-body p-2">
                    <h5 class="card-title">File Properties</h5>
                    <form action="#" id="editForm">
                        <input type="hidden" id="editId" name="editId" val="" required>
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name</label>
                            <input type="text" name="editName" id="editName" class="form-control" maxlength="100" value="" required>
                        </div>
                        <div class="mb-3">
                            <label for="editVersion" class="form-label">Version</label>
                            <input type="text" id="editVersion" class="form-control" maxlength="10" value="" required>
                        </div>
                        <div class="mb-3">
                            <label for="editCategories" class="form-label">Categories</label>
                            <input type="text" id="editCategories" name="editCategories" class="form-control" value="" required>
                        </div>
                        <div class="mb-3">
                            <label for="editClearanceLevel" class="form-label">Clearance Level</label>
                            <select name="editClearanceLevel" id="editClearanceLevel" class="form-select">
                                <option value="0">Public</option>
                                <option value="1">Signed In</option>
                                <option value="2">Admin</option>
                                <option value="3">System</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="editOS" class="form-label">Operating System</label>
                            <select name="editOS" id="editOS" class="form-select" required>
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
                            <label for="editArchitecture" class="form-label">Architecture</label>
                            <select name="editArchitecture" id="editArchitecture" class="form-select" required>
                                <option value="x86">x86</option>
                                <option value="x64">x64</option>
                                <option value="AMD64">AMD64</option>
                                <option value="ARM">ARM</option>
                                <option value="Other" selected>Other</option>
                            </select>
                            <small>File Architecture, not OS Arch.</small>
                        </div>
                        <div class="mb3">
                            <label for="editPublisher" class="form-label">Publisher</label>
                            <input type="text" id="editPublisher" class="form-control mb-2" maxlength="100" placeholder="Ex: Microsoft" required>
                            <input type="url" class="form-control" id="editPublisherLink" name="editPublisherLink" placeholder="Input publisher link if exist.">
                        </div>
                        <div class="mb-3">
                            <label for="editInfo" class="form-label">Information</label>
                            <textarea name="editInfo" id="editInfo" class="form-control" maxlength="300" rows="5" placeholder="Extra Information"></textarea>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="button" id="btnUpdate" class="btn btn-primary"><i class="bi bi-floppy-fill"></i> Update</button>
                        </div>
                        <hr/>
                        <div class="d-flex justify-content-around mb-3">
                            <button class="btn btn-danger" id="btnDelete"><i class="bi bi-trash"></i> Delete This File</button>
                            <button class="btn btn-primary" id="btnOpen"><i class="bi bi-arrow-right-square-fill"></i> Open This File</button>
                            <button class="btn btn-secondary" id="btnClose"><i class="bi bi-file-earmark-x-fill"></i> Close this Properties</button>
                        </div>
                    </form>
                    </div>
                </div>
                <script type="text/javascript">
                 $(document).ready(function(){
                    const token = '<?= htmlspecialchars(base64_encode($global['auth_token']))?>';
                    const $editField = $('#editField');
                    const $editForm = $('#editForm');
                    const $editId = $('#editId');
                    const $editName = $('#editName');
                    const $editVersion = $('#editVersion');
                    const $editCategories = $('#editCategories');
                    const $editClearanceLevel = $('#editClearanceLevel');
                    const $editOS = $('#editOS');
                    const $editArchitecture = $('#editArchitecture');
                    const $editPublisher = $('#editPublisher');
                    const $editPublisherLink = $('#editPublisherLink');
                    const $editInfo = $('#editInfo');
                    let id;
                    const tag = new Tagify($editCategories[0]);

                    $editField.hide();

                    function loadFileProperties(id, name, version, categories, needC, cLevel, os, arch, pub, pubLink, info){
                        if ($('#filesTable').find('td').eq(0).text().trim() === 'No Data') {
                                        return; 
                                    }
                        $editForm[0].reset();
                        $editName.val(name);
                        $editId.val(id);
                        $editVersion.val(version);
                        if(needC == 1){
                            $editClearanceLevel.val(cLevel);
                        }
                        $editOS.val(os);
                        $editArchitecture.val(arch);
                        $editPublisher.val(pub);
                        $editPublisherLink.val(pubLink);
                        $editInfo.val(info);
                        let rawString = categories;
                        let convertedArray = rawString.split(",");
                        tag.addTags(convertedArray);
                        $('#btnUpdate').off('click').on('click', function(e){
                            e.preventDefault();
                            validateUpdate().then(response => {
                                    sendUpdate();
                                }).catch(error => {
                                    Swal.fire({
                                    icon: "error",
                                    title: "Error",
                                    text: error.message
                                    });
                                });
                        });
                        $('#btnClose').off('click').on('click', function(e){
                            e.preventDefault();
                            $(this).off('click');
                            removeFileField();
                        });
                        $('#btnDelete').off('click').on('click', function(e){
                            e.preventDefault();
                            if(!$editId.val()){
                                return;
                            }
                            deleteFile();
                        });
                        $('#btnOpen').off('click').on('click', function(e){
                            e.preventDefault();
                            if(!$editId.val()){
                                return;
                            }
                            openFile();
                        });
                        $editField.show();
                    }

                    function loadFormProperties(id){
                        if(!Number.isInteger(id) || id <= 0){
                            return;
                        }

                        $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify({action: "loadFile", id: btoa(id)}),
                            success : function (response){
                                    let data = response.data;
                                    loadFileProperties(id, data.name, data.version, data.categories, data.needClearance, data.clearanceLevel, data.os, data.architecture, data.publisher, data.publisherLink, data.info);
                            },
                            error : function(error){
                                console.error(error.message);
                                Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Load failed ",
                                        });
                                }
                            });

                    }

                    function removeFileField(){
                        tag.removeAllTags();
                        $editForm[0].reset();
                        $editField.hide();
                        $('#btnUpdate').off('click');
                    }

                    function validateUpdate(){
                        return $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                            "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify({action: 'validateEdit', id: btoa($editId.val())})
                        });
                    }


                    function sendUpdate(){
                        let urlVal = $editPublisherLink.val();
                        if (urlVal && !isValidUrl(urlVal)) {
                            Swal.fire({ icon:'error', title:'Invalid URL' });
                            return;
                        }

                        const tags = tag.value.map(t => t.value); // gives you an array
                        if(tags.length < 1){
                            return;
                        }
                        const tagString = tags.join(',');

                        const payload = {
                            action: 'updateFile',
                            data: {
                                id : $editId.val(),
                                name : $editName.val(),
                                version : $editVersion.val(),
                                categories : tagString,
                                clearanceLevel : $editClearanceLevel.val(),
                                os : $editOS.val(),
                                architecture : $editArchitecture.val(),
                                publisher : $editPublisher.val(),
                                publisherLink : $editPublisherLink.val(),
                                info : $editInfo.val()
                            }
                        };

                        console.log("Clearance level: " + $editClearanceLevel.val());

                        $.ajax({
                            type: "POST",
                            url: '<?= $global['full_url']?>/bin/api_login.php',
                            contentType: "application/json",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            credentials: 'include',
                            data: JSON.stringify(payload),
                            success : function (response){
                                    Swal.fire({
                                        title: 'Success!',
                                        text: response.message,
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    });
                                    removeFileField();
                            },
                            error : function(error){
                                console.error(error.message);
                                Swal.fire({
                                        icon: "error",
                                        title: "Error",
                                        text: "Update failed ",
                                        });
                                }
                            });
                    }

                    function deleteFile(){
                        Swal.fire({
                            title: 'Are you sure?',
                            text: 'This action will permanently delete the item.',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, delete it!',
                            cancelButtonText: 'Cancel'
                            }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                type: "POST",
                                url: '<?= $global['full_url']?>/bin/api_login.php',
                                contentType: "application/json",
                                headers: {
                                    "Authorization": "Bearer " + token
                                },
                                credentials: 'include',
                                data: JSON.stringify({action : 'deleteFile', id : btoa($editId.val())}),
                                success : function (response){
                                        Swal.fire({
                                            title: 'Success!',
                                            text: response.message,
                                            icon: 'success',
                                            confirmButtonText: 'OK'
                                        });
                                        removeFileField();
                                        window.location.reload();
                                },
                                error : function(error){
                                    console.error(error.message);
                                    Swal.fire({
                                            icon: "error",
                                            title: "Error",
                                            text: "Update failed ",
                                            });
                                    }
                                });
                            }
                            });

                    }

                    function openFile(){
                        const id = $editId.val();
                        window.open('<?= $global['full_url']?>/index.php?r=file-info&f='+btoa(id),'_blank');
                    }

                    function isValidUrl(str) {
                        try { new URL(str); return true; }
                        catch { return false; }
                    }

                    const table = new DataTable('#filesTable', {
                                    columnDefs: [{
                                    searchable: false,
                                    orderable: false,
                                    targets: 0
                                    }]
                                });
                    table.on('click', 'tbody tr', function() {
                                    if ($(this).find('td').eq(0).text().trim() === 'No Data') {
                                        return; 
                                    }
                                        const id = $(this).find('td').eq(0).text(); // Get the first cell value (ID)
                                        loadFormProperties(Number(id));
                                });
                   
                    });   
                </script>
                </div>
            </div>
        </div>
    </section>
</div>