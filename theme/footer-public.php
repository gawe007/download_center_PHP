        <footer class="pt-7 pt-lg-8 bg-primary text-white" id="siteFooter">
          <div class="container">
            <div class="row gy-4 g-md-3 border-bottom pb-8 pb-lg-9 justify-content-center">
              <div class="col-6 col-md-3">
                <p class="mb-2 lh-lg ls-1">Site Menu</p>
                <ul class="list-unstyled text-1100">
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="index.php?r=files">Files</a></li>
                  <?php
                   if(!isset($global['session_id']) OR empty($global['session_id'])){
                  ?>
                    <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="index.php?r=login">Login</a></li>
                  <?php
                   }else if($global['session_user_level'] == 1){
                    ?>
                    <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#" id="btnLogout">Logout</a></li>
                    <script type="text/javascript">
                      let tokenlogout = '<?= base64_encode($global['auth_token'])?>';
                      if($('#btnLogout').length){
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
                        }
                    </script>
                  <?php
                   }else{
                  ?>
                     <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="index.php?r=admin">Admin Panel</a></li>
                     <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#" id="btnLogout">Logout</a></li>
                     <script type="text/javascript">
                      let tokenlogout = '<?= base64_encode($global['auth_token'])?>';
                  if($('#btnLogout').length){
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
                    }
                  </script>
                  <?php
                  }
                  ?>
                </ul>
              </div>
              <div class="col-6 col-md-3">
                <p class="mb-2 lh-lg">Popular Downloads</p>
                <ul class="list-unstyled text-1100">
                  <?php
                  require_once("bin/entity/file.php");
                  $file = new file();
                  $data = $file->getFilesFavourites();
                  if(count($data) < 1){
                    echo "<li class='mb-1'>Files Empty</li>";
                  }else{
                    foreach($data as $f){
                      echo "<li class='mb-1'><div class='d-flex justify-content-between'><a class='text-light text-wrap' href='".$global['full_url']."/index.php?r=file-info&f=".base64_encode($f['id'])."'>".$f['name']."</a><span class='p-1 text-light overflow-hidden'><small>".$f['downloaded_count']."</small></span></div></li>";
                    }
                  }
                  ?>
                </ul>
              </div>
              <div class="col-6 col-md-3">
                <p class="mb-2 lh-lg"> Legal</p>
                <ul class="list-unstyled text-1100">
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="index.php?r=terms"> Terms & Conditions</a></li>
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="index.php?r=privacy"> Privacy Policy</a></li>
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="index.php?r=license"> License</a></li>
                </ul>
              </div>
              <div class="col-6 col-md-3 d-md-flex flex-column align-items-md-end pe-md-0 text-white">
                <div>
                  <p class="mb-2 lh-lg"> Contact Us</p>
                  <div class="mb-1 mb-lg-2">
                      <b>My contact<b>
                  </div>
                </div>
              </div>
            </div>
            <div class="row gy-2 py-3 justify-content-center justify-content-md-between">
              <div class="col-auto ps-0">
                <p class="text-center text-md-start lh-xl text-1100"> Copyright 2025 gawe007</p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </main><!-- ===============================================--><!--    End of Main Content--><!-- ===============================================-->