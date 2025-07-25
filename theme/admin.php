<div class="container-fluid">
    <?php include("offcanvas-adminmenu.php"); ?>
<section class="row">
<div class="col-12 p-2">
    <?php include("header-admin.php")?>
    <div class="d-block">
        <p class="display-3">Dashboard</p>
        <hr noshade/>
        <div class="p-2 m-2 border border-1 border-secondary rounded">
            <?php
            require_once("bin/entity/file.php");
            require_once("theme/function.php");
                $file = new file();
                $data = $file->getNewestFile();
                
                if(count($data) > 0){
                    foreach ($data as $d){
                    $fileSize = formatBytesHumanReadable($d['file_size']);
                    echo "<div class='mb-3'>
                    <span class='d-block display-6 text-primary mb-3'>Newest File</span>
                        <form>
                        <div class='mb-3'>
                        <label for='displayName'>Display Name</label>
                        <input type='text' class='form-control' readonly value='".$d['name']."'>
                        </div>
                        <div class='mb-3'>
                        <label for='displayName'>Size</label>
                        <input type='text' class='form-control' readonly value='".$fileSize."'>
                        </div>
                        </form>
                        <div class='d-flex justify-content-end'><button id='btnOpen' class='btn btn-primary'><i class='bi bi-arrow-right'></i> Open</button></div>
                        <script type='text/javascript'>
                            $('#btnOpen').on('click', function(){
                                window.open('".$global['full_url']."/index.php?r=file-info&f=".base64_encode($d['id'])."','_blank');
                            });
                        </script>
                    </div>";
                    }
                }
            ?>
        </div>
        <?php
        if($global['session_user_level'] > 1){
        ?>
        <div class="p-2 m-2 border border-1 border-secondary rounded">
            <span class='d-block display-6 text-primary mb-3'>Access Log</span>
            <table class="table-striped table-bordered table-hover m-2 mb-3" id="tableLogs">
                <thead>
                    <th>#</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Timestamp</th>
                </thead>
            </table>
            <script type="text/javascript">
                $(document).ready(function () {
                    const token = '<?= base64_encode($global['auth_token'])?>';
                        $('#tableLogs').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '<?= $global['full_url']?>/bin/api_login.php', // your API endpoint
                                type: 'POST',
                                contentType: 'application/json',   // tell server this is JSON
                                headers: {
                                "Authorization": "Bearer " + token
                                },
                                credentials: 'include',
                                data: function (d) {
                                    d.action = 'getLogs';           // append your action flag
                                    return JSON.stringify(d);        // convert to raw JSON
                                }
                            },
                            columns: [
                            { data: null,
                              render: function (data, type, row, meta) {
                                    return meta.row + 1 + meta.settings._iDisplayStart;
                                }
                             },
                            { data: 1 },
                            { data: 2 },
                            { data: 3 }
                            ],
                            error: function (xhr, error, thrown) {
                                console.error("DataTables error:", error);
                            },
                            createdRow: function (row, data, dataIndex) {
                                // store actual file ID in row attribute (previously data[0])
                                $(row).attr('data-id', data[0]);
                            }
                        });
                        });
            </script>
        </div>
        <?php
        }
        ?>
    </div>
</div>
</section>
</div>