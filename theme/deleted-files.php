<?php
require_once("bin/entity/file.php");
require_once("bin/entity/user.php");
$file = new file();
$data = $file->getDeletedFiles();
?>
<div class="container-fluid">
    <?php include("offcanvas-adminmenu.php"); ?>
    <section class="row">
        <div class="col-12 col-md-10 p-3" style="min-width: 0; flex: 1 1 auto;">
            <?php include("header-admin.php")?>
            <div class="d-block">
               <p class="display-3">Deleted Files Management</p>
                <hr noshade/>
                <ul class="nav">
                    <li class="nav-item"><a href="<?= $global['full_url']?>/index.php?r=files-admin" class="nav-link"><i class="bi bi-arrow-left"> Back to Files Management</i></a></li>
                </ul>
                <div class="p-2 table-responsive">
                <table id="filesTable" class="table table-striped m-2 w-100 mb-2">
                    <caption>Table Deleted Files</caption>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Name</th>
                            <th>Publisher</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                    if(count($data) < 1){
                        echo "<tr><td class='text-center' colspan='5'>No Data</td></tr>";
                    }else{
                        $i = 1;
                        foreach($data as $f){
                            $user = new user(null);
                            $user->setId($f['id_user']);
                            $user->load();
                            echo "<tr data-id='".$f['id']."'>
                                <td>".$i++."</td>
                                <td>".$user->getName()."</td>
                                <td>".$f['name']."</td>
                                <td>".$f['publisher']."</td>
                                <td class='text-center'><div class='d-flex justify-content-around'><button class='d-inline btn btn-warning' id='btnRestore'>Restore</button><button class='d-inline btn btn-danger' id='btnDestroy'>Destroy</button></div><td>
                                </tr>
                            ";
                        }
                    }

                    ?>
                    </tbody>
                    </table>
                <script type="text/javascript">
                const token = '<?= htmlspecialchars(base64_encode($global['auth_token']))?>';
                $(document).ready(function () {
                $("#filesTable").on("click", "#btnRestore, #btnDestroy", function () {
                    const $btn = $(this);
                    const $row = $btn.closest("tr");
                    const fileId = $row.data("id");
                    const isRestore = $btn.attr("id") === "btnRestore";
                    const endpoint = "<?= $global['full_url']?>/bin/api_login.php";
                    const payload = isRestore ? {action : 'restoreFile', id: btoa(fileId)} : {action: 'destroyFile', id: btoa(fileId)};
                    // Show blocking loading modal
                    Swal.fire({
                    title: `${isRestore ? "Restoring" : "Destroying"} file...`,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                    });

                    $.ajax({
                    url: endpoint,
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(payload),
                    headers: {
                        Authorization: "Bearer " + token
                    },
                    success: function (response) {
                        Swal.close();
                        $row.fadeOut(300, function () {
                            $(this).remove();
                        });
                        Swal.fire({
                            icon: "success",
                            title: `${isRestore ? "Restored" : "Destroyed"} successfully`,
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.close();
                        Swal.fire({
                        icon: "error",
                        title: "Request error",
                        text: "Something went wrong. Please try again."
                        });
                        console.error("AJAX error:", error);
                    }
                    });
                });
                });
                </script>
                </div>
            </div>
        </div>
    </section>
</div>