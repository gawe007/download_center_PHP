 <div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="mobileSidebar" aria-labelledby="labelSidePanel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="labelSidePanel">Admin Panel</h5>
        <h6 class="text-center text-light">Download Center</h6>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>
      <div class="offcanvas-body">
        <a href="#" id="mobileDashboardButton" class="nav-link">Dashboard</a>
        <hr/>
        <a href="#" id="mobileUserButton" class="nav-link">User</a>
        <a href="#" id="mobileDataButton" class="nav-link">Data</a>
      </div>
      <script type="text/javascript">
        $('#mobileDashboardButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=admin';
        });
        $('#mobileUserButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=user-admin';
        });
        $('#mobileDataButton').on('click', function(){
            window.location = '<?= $global['full_url']?>/index.php?r=data-admin';
        });
      </script>
</div>