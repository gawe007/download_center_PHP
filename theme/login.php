<section class="vh-100">
  <div class="container-fluid vh-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-md-9 col-lg-6 col-xl-5">
        <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-login-form/draw2.webp"
          class="img-fluid" alt="Sample image">
      </div>
      <div class="col-md-8 col-lg-6 col-xl-4 offset-xl-1">
        <form id="formLogin" method="POST">
                <input class="form-control mb-5 p-2 border border-primary border-1" id="email" type="email" name="email" placeholder="E-mail" maxlength="30" required/>
                <input class="form-control mb-5 p-2 border border-primary border-1" id="password" type="password" name="password" placeholder="password" maxlength="20" required/>
                <div class="d-flex align-items-end">
                    <button type="submit" class="btn btn-lg btn-primary text-white" id="btnlogin">Login</button>
                </div>
        </form>
        <script type="text/javascript">
            $('#formLogin').on('submit', function(e){
                e.preventDefault();
                $('#btnLogin').html("<span class='spinner-border' role='status' aria-hidden='true'></span>");
                $.ajax({
                    type: "POST",
                    url: '<?= $global['full_url']?>/bin/api_public.php',
                    contentType: "application/json",
                    credentials: 'include',
                    data: JSON.stringify({action: "login", data: { email: $('#email').val(), password: $('#password').val()}}),
                    success : function (response){
                            window.location = "<?= $global['full_url']?>/index.php?r=admin";
                    },
                    error : function(xhr, status, error){
                       Swal.fire({
                              icon: "error",
                              title: "Error",
                              text: "Login failed",
                            });
                    }
                });
            });
        </script>
      </div>
    </div>
  </div>
</section>