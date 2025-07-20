<?php
require_once("bin/entity/file.php");
require_once("bin/entity/user.php");
$file = new file();
$data = $file->getAllFilesNotDeleted();
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
                <p class="display-3">File Management</p>
                <hr noshade/>
                <a href="<?= $global['full_url']?>/index.php?r=upload-new-file" class="btn btn-primary text-light">+ Upload New File</a>
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
                    <script type='text/javascript'>
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
                                        window.location = '<?= $global['full_url']?>/index.php?r=file-info&f=' + btoa(id);
                                });
                    </script>
                </div>
                <script type="text/javascript">
                const token = '<?= htmlspecialchars(base64_encode($global['auth_token']))?>';
                </script>
                </div>
            </div>
        </div>
    </section>
</div>