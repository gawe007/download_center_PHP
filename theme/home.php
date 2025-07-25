<!-- ===============================================--><!--    Main Content--><!-- ===============================================-->
<?php 
include("header-public.php");
?>
<div class="container-fluid" style="min-height: 600px;">
<div class="row mb-5">
    <div class="clr-fix" style="height: 200px;"></div>
    <p class="display-3 text-center text-primary">Download Center</p>
    <p class="display-5 text-center">Fk. Teknik Universitas Pancasila</p>
    <div class="row justify-content-center align-items-center mb-5">
        <div class="col-sm-12 col-md-6">
            <div class="input-group">
                <input type="text" id="searchQ" name="searchQ" class="form-control border border-1 border-primary p-2" placeholder="Type Something" maxlength="100" required>
            </div>
            <script type="text/javascript">
                function filterDuplicates(data) {
                    const seen = new Set();
                    return $.grep(data, function(file) {
                        if (seen.has(file.id)) {
                            return false;
                        } else {
                            seen.add(file.id);
                            return true;
                        }
                    });
                }
                $('#searchQ').on('input keyup', function(){
                    let query = $(this).val();
                    const $container = $('#searchResult');
                            $container.removeClass('d-none').addClass('d-block');
                            $container.empty(); // Clear previous results
                    const $spinner = $('#loadingSpinner');
                    if(query.length < 2) return;
                    $spinner.show();
                    $.ajax({
                        type: "POST",
                        url: '<?= $global['full_url']?>/bin/api_public.php',
                        contentType: "application/json",
                        data: JSON.stringify({action: "liveSearch", param: query}),
                        success : function (data){
                            $spinner.hide();
                             const filteredData = filterDuplicates(data); // 🚫 Filter duplicate entries

                            if (filteredData.length === 0) {
                                $container.append(
                                    $('<div>')
                                        .text('No matching data')
                                        .css({ padding: '5px', color: '#888', fontStyle: 'italic' })
                                );
                                return;
                            }

                            $.each(filteredData, function(index, file) {
                                const $item = $('<div class="p-2 text-wrap">')
                                    .text(`${file.name} [.${file.extension}] - ${file.categories}`)
                                    .css({ cursor: 'pointer', padding: '5px' })
                                    .on('click', function () {
                                        window.location.href = `<?= $global['full_url']?>/index.php?r=file-info&f=${btoa(file.id)}`;
                                    });
                                $container.append($item);
                            });

                        },
                        error : function(xhr, status, error){
                            $spinner.hide();
                            console.error('Ajax failed');
                        }
                    });
                });
            </script>
            <div class=" my-2 p-2 rounded border border-1 border-secondary d-none" id="searchResult">
                <div id="loadingSpinner" class="text-center py-2" style="display:none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
include("footer-public.php");
?>