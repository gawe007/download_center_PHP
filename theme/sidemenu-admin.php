<div class="d-block h-100 overflow-hidden p-1 bg-primary" style="width: 100%">
    <div class='d-flex flex-column'>
        <h4 class="text-center text-light">Admin Panel</h4>
        <h6 class="text-center text-light">Download Center</h6>
        <div class="text-center">
            <div class="d-inline position-relative btn-group" role="group">
                <a href="<?= $global['full_url']?>" class="btn btn-light text-dark border border-1 border-secondary" data-toggle="tooltip" data-placement="bottom" title="Home"><i class="bi bi-house"></i></a>
                <span id="btnLogout" class="btn btn-danger text-light border border-1 border-secondary" data-toggle="tooltip" data-placement="bottom" title="Logout"><i class="bi bi-door-closed-fill"></i></span>
            </div>
        </div>
        <span id="dashboardButton" class="px-2 py-1 my-1 rounded bg-light" style="cursor: pointer;"><span style="width:20px;" class="text-center"><i class="bi bi-house-gear"></i></span> Dashboard</span>
        <hr noshade/>
        <span id="userButton" class="px-2 py-1 my-1 rounded bg-light" style="cursor: pointer;"><span style="width:20px;" class="text-center"><i class="bi bi-person-fill"></i></span> User</span></span>
        <span id="filesButton" class="px-2 py-1 my-1 rounded bg-light" style="cursor: pointer;"><span style="width:20px;" class="text-center"><i class="bi bi-box-fill"></i></span> Files</span></span>
    </div>
</div>

<script type="text/javascript">
        const tokenlogout = '<?=htmlspecialchars(base64_encode($global['auth_token']))?>';
        $('#dashboardButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=admin';
        });
        $('#userButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=user-admin';
        });
        $('#filesButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=files-admin';
        });
        $('#btnLogout').on('click', function(){
            $.ajax({
                    type: "POST",
                    url: '<?= $global['full_url']?>/bin/api_login.php',
                    contentType: "application/json",
                    headers: {
                                "Authorization": "Bearer " + tokenlogout
                            },
                    credentials: 'include',
                    data: JSON.stringify({action: "logout"}),
                    success : function (response){
                        if(response.status){
                            window.location = "<?= $global['full_url']?>/index.php?r=index.php";
                        }
                    },
                    error : function(xhr, status, error){
                       Swal.fire({
                              icon: "error",
                              title: "Error",
                              text: "Logout failed",
                            });
                    }
                });
        });
    </script>