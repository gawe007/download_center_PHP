<?php
include("header-public.php");
?>
<div class="container-fluid pt-5">
    <div class="row gap-1">
        <div class="d-sm-none d-md-block col-md-1"></div>
        <div class="col-sm-12 col-md-10" style="min-height: 600px;">
            <div class="display-4 text-primary">Files</div>
            <hr noshade/>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover" id="filesTable">
                    <thead>
                        <tr>
                            <th class="p-1">#</th>
                            <th class="p-1">Name</th>
                            <th class="p-1">Ext</th>
                            <th class="p-1">Categories</th>
                            <th class="p-1">OS</th>
                            <th class="p-1">Version</th>
                            <th class="p-1">Publisher</th>
                            <th class="p-1">Information</th>
                            <th class="p-1">Arch</th>
                            <th class="p-1"><i class="bi bi-arrow-down"></i></th>
                        </tr>
                    </thead>
                </table>
                <script type='text/javascript'>
                    $(document).ready(function () {
                        $('#filesTable').DataTable({
                            processing: true,
                            serverSide: true,
                            ajax: {
                                url: '<?= $global['full_url']?>/bin/api_public.php', // your API endpoint
                                type: 'POST',
                                contentType: 'application/json',   // tell server this is JSON
                                data: function (d) {
                                    d.action = 'getFiles';           // append your action flag
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
                            { data: 3,
                              render: function (data) {
                                    const tags = data.split(',').map(tag =>
                                    `<span class="tag-manual" data-tag="${tag.trim()}">${tag.trim()}</span>`
                                    ).join(' ');
                                    return tags;
                                }
                            },
                            { data: 4 },
                            { data: 5 },
                            { data: 6 },
                            { data: 7 },
                            { data: 8 },
                            { data: 9 }
                            ],
                            error: function (xhr, error, thrown) {
                                console.error("DataTables error:", error);
                            },
                            createdRow: function (row, data, dataIndex) {
                                // store actual file ID in row attribute (previously data[0])
                                $(row).attr('data-id', data[0]);
                            },
                            drawCallback: function () {
                                // re-bind tag click listener if needed
                                $('.tag-manual').off('click').on('click', function () {
                                    const tagValue = $(this).data('tag');
                                    const table = $('#filesTable').DataTable();

                                    // highlight active tag
                                    $('.tagify-badge').removeClass('active');
                                    $(this).addClass('active');

                                    table.search(tagValue).draw();
                                });
                            }

                        });
                        $('#filesTable').on('click', '.tag-manual', function () {
                            const tagValue = $(this).data('tag');
                            const table = $('#filesTable').DataTable();
                            
                            table.search(tagValue).draw();
                        });

                        $('#filesTable tbody').on('click', 'tr', function () {
                            const fileId = $(this).data('id');
                            if (fileId !== undefined) {
                                window.location.href = `<?=$global['full_url']?>/index.php?r=file-info&f=${btoa(fileId)}`;
                            }
                        });


                        });
                </script>
            </div>
        </div>
        <div class="d-sm-none d-md-block col-md-1"></div>
    </div>
</div>
<?php
include("footer-public.php")
?>