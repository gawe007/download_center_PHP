 <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileSidebar" aria-labelledby="labelSidePanel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="labelSidePanel">Admin Panel</h5>
        <h6 class="text-center text-light"><a href="<?= $global['full_url']?>">Download Center</a></h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <a href="#" id="mobileDashboardButton" class="nav-link">Dashboard</a>
        <a href="#" id="mobileMyProfileButton" class="nav-link">My Profile</a>
        <a href="#" id="btnLogout" class="nav-link">Logout</a>
        <hr/>
        <a href="#" id="mobileUserButton" class="nav-link">User</a>
        <a href="#" id="mobileFilesButton" class="nav-link">Files</a>
      </div>
      <script type="text/javascript">
        $('#mobileDashboardButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=admin';
        });
        $('#mobileMyProfileButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=admin-info';
        });
        $('#mobileUserButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=user-admin';
        });
        $('#mobileFilesButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=files-admin';
        });
        $('#btnLogout').on('click', function(){
          const tokenlogout = '<?= base64_encode($global['auth_token'])?>';
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
</div>