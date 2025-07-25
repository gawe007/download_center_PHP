<?php
    require_once("bin/entity/user.php");
    $user = new user(null);
    $user->setId($global['session_user_id']);
    $user->load();
?>
<div class="container-fluid">
    <?php include("offcanvas-adminmenu.php"); ?>
    <section class="row">
        <div class="col-12 col-md-10 p-3" style="min-width: 0; flex: 1 1 auto;">
            <?php include("header-admin.php")?>
            <div class="d-block">
                <p class="display-3">My Profile</p>
                <hr noshade/>
                <div class="p-2">
                    <form action="#">
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">E-Mail</label>
                            <input type="text" id="userEmail" class="form-control" value="<?= $user->getEmail()?>">
                        </div>
                        <div class="mb-3">
                            <label for="userDisplayName" class="form-label">Display name</label>
                            <input type="text" id="userDisplayName" class="form-control" value="<?= $user->getName()?>">
                        </div>
                    </form>
                    <blockquote class="blockquote">
                        <p class="text-wrap">For updating/changing any user info, contact your system admin.</p>
                    </blockquote>
                </div>
                <script type="text/javascript">
                const token = '<?= htmlspecialchars(base64_encode($global['auth_token']))?>';
                </script>
                </div>
            </div>
        </div>
    </section>
</div>