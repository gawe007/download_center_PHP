<!-- ===============================================--><!--    Main Content--><!-- ===============================================-->
<?php 
include("header-public.php");
?>
<div class="row mb-3">
    <div class="clr-fix" style="height: 200px;"></div>
    <p class="display-3 text-center text-primary">Download Center</p>
    <p class="display-5 text-center">Fk. Teknik Universitas Pancasila</p>
    <div class="row justify-content-center align-items-center mb-2">
        <div class="col-sm-12 col-md-6">
            <div class="input-group">
                <input type="text" name="searchQ" class="form-control p-2" placeholder="Type Something" maxlength="100" required>
                <button type="submit" class="btn btn-primary text-white"><i class="bi bi-search"></i></button>
            </div>
            <div class="d-flex justify-content-end">
                <a href="<?= $global['full_url']?>/index.php?r=adv-search">Advanced Search</a>
            </div>
        </div>
    </div>
</div>
<?php
include("footer-public.php");
?>