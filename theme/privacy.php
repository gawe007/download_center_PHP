<?php
include("header-public.php");
?>
<div class="container-fluid">
    <div class="row">
        <div class="d-sm-none d-md-block col-md-2">&nbsp;</div>
        <div class="col-sm-12 col-md-8 pt-5">
            <div class="card shadow-sm mb-4">
            <div class="card-header bg-dark text-white fw-semibold">
                🔐 Privacy Policy
            </div>
            <div class="card-body">
                    <?php
                    require_once("bin/Parsedown.php");
                    $parse = new Parsedown();
                    echo $parse->text(file_get_contents("PRIVACY.md"));
                     ?>
            </div>
            </div>
        </div>
        <div class="d-sm-none d-md-block col-md-2">&nbsp;</div>
    </div>
</div>
<?php
include("footer-public.php");
?>