<?php
    require_once("bin/config/config.php");
    require_once("bin/Parsedown.php");
    include("header-public.php");
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-sm-12 com-md-10 p-2 pt-5">
            
            <div class="card">
                <div class="card-body">
                    <div class="card-title">
                        <span class="d-block display-4 text-primary">About</span>
                        <hr/>
                    </div>
                    <div class="p-1 text-monospace">
                        
                           <p> App Name : <?= APP_NAME?>.</p>
                            <p>Configured For : <?= APP_CONFIGURED_FOR?>.</p>
                            <p>Author : <?= APP_AUTHOR ?>.</p>
                            <p>github : <a href="<?= APP_AUTHOR_CONTACT?>" target="_blank"><?= APP_AUTHOR_CONTACT?></a>.</p>
                        
                        <?php
                            $Parsedown  = new Parsedown();
                            echo $Parsedown->text(file_get_contents("README.MD"));
                        ?>
                    </div>
                </div>         
            </div>
        </div>
    </div>
</div>
<?php 
include("footer-public.php");
?>