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
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#!">Features</a></li>
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#!"> Pricing</a></li>
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#!"> News</a></li>
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#!"> Help desk</a></li>
                  <li class="mb-1"><a class="ls-1 lh-xl link-footer" href="#!"> Support</a></li>
                </ul>
              </div>
              <div class="col-6 col-md-3">
                <p class="mb-2 lh-lg"> Legal</p>
                <ul class="list-unstyled text-1100">
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#!">Licence</a></li>
                  <li class="mb-1"> <a class="ls-1 lh-xl link-footer" href="#!"> Terms & Conditions</a></li>
                </ul>
              </div>
              <div class="col-6 col-md-3 d-md-flex flex-column align-items-md-end pe-md-0 text-white">
                <div>
                  <p class="mb-2 lh-lg"> Contact Us</p>
                  <div class="mb-1 mb-lg-2">
                      <b>IT Maintenance<b>
                      <p class="text-wrap">Lt.4 - Fak. Teknik Universitas Pancasila</p>
                  </div>
                </div>
              </div>
            </div>
            <div class="row gy-2 py-3 justify-content-center justify-content-md-between">
              <div class="col-auto ps-0">
                <p class="text-center text-md-start lh-xl text-1100"> © 2025 Copyright, All Right Reserved.</p>
              </div>
              <div class="col-auto pe-0"><a class="icons fs-8 me-3 me-md-0 ms-md-3 cursor-pointer" href="#!"><span class="uil uil-twitter"> </span></a><a class="icons fs-8 me-3 me-md-0 ms-md-3 cursor-pointer" href="#!"><span class="uil uil-instagram"></span></a><a class="icons fs-8 me-3 me-md-0 ms-md-3 cursor-pointer" href="#!"><span class="uil uil-linkedin"> </span></a></div>
            </div>
          </div>
        </footer>
      </div>
    </main><!-- ===============================================--><!--    End of Main Content--><!-- ===============================================-->