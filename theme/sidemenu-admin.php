<div class="d-block vh-100 overflow-hidden p-1 bg-primary" style="width: 200px">
    <div class='d-flex flex-column'>
        <h4 class="text-center text-light">Admin Panel</h4>
        <h6 class="text-center text-light">Download Center</h6>
        <div class="text-center">
            <div class="d-inline position-relative btn-group" role="group">
                <a href="<?= $_SESSION['full_url']?>/index.php?r=index" class="btn btn-light text-dark border border-1 border-secondary"><i class="bi bi-house"></i></a>
                <span id="btnlogout" class="btn btn-danger text-light border border-1 border-secondary"><i class="bi bi-door-closed-fill"></i></span>
            </div>
        </div>
        <span id="dashboardButton" class="px-2 py-1 my-1 rounded bg-light" style="cursor: pointer;"><span style="width:20px;" class="text-center"><i class="bi bi-house-gear"></i></span> Dashboard</span>
        <hr/>
        <span id="userButton" class="px-2 py-1 my-1 rounded bg-light" style="cursor: pointer;"><span style="width:20px;" class="text-center"><i class="bi bi-person-fill"></i></span> User</span></span>
        <span id="dataButton" class="px-2 py-1 my-1 rounded bg-light" style="cursor: pointer;"><span style="width:20px;" class="text-center"><i class="bi bi-box-fill"></i></span> Data</span></span>
    </div>
    <script type="text/javascript">
        $('#dashboardButton').on('click', function(){
            window.location = '<?= $_SESSION['full_url']?>/index.php?r=admin';
        });
        $('#userButton').on('click', function(){
            window.location = '<?= $_SESSION['full_url']?>/index.php?r=user-admin';
        });
        $('#dataButton').on('click', function(){
            window.location = '<?= $_SESSION['full_url']?>/index.php?r=data-admin';
        });
        $('#btnLogout').on('click', function(){
            $.ajax({
                    type: "POST",
                    url: '<?= $_SESSION['full_url']?>/bin/api_login.php',
                    contentType: "application/json",
                    data: JSON.stringify({action: "logout"}),
                    success : function (response){
                        if(response.status){
                            window.location = "<?= $_SESSION['full_url']?>/index.php?r=index.php";
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
</div>