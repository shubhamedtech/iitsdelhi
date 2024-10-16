<?php
session_start();
if (isset($_SESSION["Password"]) || isset($_SESSION["Unique_ID"])) {
  header("Location: /dashboards/admin-dashboard.php");
}
include('includes/config.php');
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-wide  customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="../../assets/" data-template="vertical-menu-template" data-style="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Login | <?= $app_title ?></title>
<link rel="icon" type="image/x-icon" href="favicon.ico" />

  <meta name="description" content="Materialize – is the most developer friendly &amp; highly customizable Admin Dashboard Template." />
  <meta name="keywords" content="dashboard, material, material design, bootstrap 5 dashboard, bootstrap 5 design, bootstrap 5">

  <link rel="canonical" href="https://1.envato.market/materialize_admin">


  <script>
    window.onload = function() {
      // fix for windows 8
      if (navigator.appVersion.indexOf("Windows NT 6.2") != -1)
        document.head.innerHTML += '<link rel="stylesheet" type="text/css" href="pages/css/windows.chrome.fix.css" />'
    }
   
  </script>
  <!--<link rel="icon" type="image/x-icon" href="https://demos.pixinvent.com/materialize-html-admin-template/assets/img/favicon/favicon.ico" />
  <link rel="preconnect" href="https://fonts.googleapis.com/">
  <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;ampdisplay=swap" rel="stylesheet">-->
  <link rel="stylesheet" href="./assets/vendor/fonts/remixicon/remixicon.css" />
  <link rel="stylesheet" href="./assets/vendor/fonts/flag-icons.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/node-waves/node-waves.css" />
  <link rel="stylesheets" href="./assets/vendor/libs//toastr/toastr.css"/>
  <link rel="stylesheet" href="./assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
  <link rel="stylesheet" href="./assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
  <link rel="stylesheet" href="./assets/css/demo.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
  <link rel="stylesheet" href="./assets/vendor/libs/typeahead-js/typeahead.css" />
   <link rel="stylesheet" href="./assets/vendor/libs/%40form-validation/form-validation.css" />
 <link rel="stylesheet" href="./assets/vendor/css/pages/page-auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.css" />
<script src="./assets/vendor/js/helpers.js"></script>
  <!-- <script src="./assets/vendor/js/template-customizer.js"></script> -->
  <script src="./assets/js/config.js"></script>
<style>
  .toast {
    background-color: #030303 !important;
  }
  .toast-success {
    background-color: #51a351 !important;
  }
  .toast-error {
    background-color: #bd362f !important;
  }
  .toast-info {
    background-color: #2f96b4 !important;
  }
  .toast-warning {
    background-color: #f89406 !important;
  }
</style>
</head>

<body>

  <div class="authentication-wrapper authentication-cover">
   <div class="authentication-inner row m-0">
      <div class="d-none d-lg-flex col-lg-7 col-xl-8 align-items-center justify-content-center p-12 pb-2">
         <lottie-player src="./assets/login_person.json" background="transparent" speed="1" style="width: 600px; height: 600px;" loop autoplay></lottie-player>
      </div>
    <div class="d-flex col-12 col-lg-5 col-xl-4 align-items-center authentication-bg position-relative py-sm-12 px-12 py-6">
        <div class="w-px-400 mx-auto pt-5 pt-lg-0">
          <h4 class="mb-1">WELCOME</h4>
          <p class="mb-5">Please sign-in to your account and start the adventure</p>

          <form id="form-login" class="mb-5" role="form" autocomplete="off" action="/app/login/login.php" method="post">
            <div class="form-floating form-floating-outline mb-5">
              <input type="text" class="form-control" style="text-transform: uppercase" id="username" name="username" placeholder="Enter your username" autofocus>
              <label for="email">Username</label>
            </div>
            <div class="mb-5">
              <div class="form-password-toggle">
                <div class="input-group input-group-merge">
                  <div class="form-floating form-floating-outline">
                    <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                    <label for="password">Password</label>
                  </div>
                  <span class="input-group-text cursor-pointer"><i class="ri-eye-off-line"></i></span>
                </div>
              </div>
            </div>
            <div class="mb-5 d-flex justify-content-between mt-5">
              <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" checked value="1" id="checkbox1">
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>
              <a href="#" class="float-end mb-1 mt-2">
                <span>Forgot Password?</span>
              </a>
            </div>
            <button type="submit" class="btn btn-primary d-grid w-100">
              Sign in
            </button>
          </form>

          <p class="text-center">
            <span>New on our platform?</span>
            <a href="#">
              <span>Create an account</span>
            </a>
          </p>

          <div class="divider my-5">
            <div class="divider-text">or</div>
          </div>

          <div class="d-flex justify-content-center gap-2">
            <a href="#" class="btn btn-icon rounded-circle btn-text-facebook">
              <i class="tf-icons ri-facebook-fill"></i>
            </a>

            <a href="#" class="btn btn-icon rounded-circle btn-text-twitter">
              <i class="tf-icons ri-twitter-fill"></i>
            </a>

            <a href="#" class="btn btn-icon rounded-circle btn-text-github">
              <i class="tf-icons ri-github-fill"></i>
            </a>

            <a href="#" class="btn btn-icon rounded-circle btn-text-google-plus">
              <i class="tf-icons ri-google-fill"></i>
            </a>
          </div>
        </div>
      </div>
      </div>
  </div>

  <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
  <script src="./assets/vendor/libs/jquery/jquery.js"></script>
  <script src="./assets/vendor/libs/popper/popper.js"></script>
  <script src="./assets/vendor/js/bootstrap.js"></script>
  <script src="./assets/vendor/libs/node-waves/node-waves.js"></script>
  <script src="./assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
  <script src="./assets/vendor/libs/hammer/hammer.js"></script>
  <script src="./assets/vendor/libs/i18n/i18n.js"></script>
  <script src="./assets/vendor/libs/typeahead-js/typeahead.js"></script>
  <script src="./assets/vendor/js/menu.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
   <script src="./assets/vendor/libs/toastr/toastr.js"></script>
  <script src="./assets/vendor/libs/jquery-validation/js/jquery.validate.min.js"></script>
 <script src="./assets/vendor/libs/%40form-validation/popular.js"></script>
  <script src="./assets/vendor/libs/%40form-validation/bootstrap5.js"></script>
  <script src="./assets/vendor/libs/%40form-validation/auto-focus.js"></script>
<script src="../assets/js/main.js"></script>
<script src="../assets/js/pages-auth.js"></script>
  <script>
    toastr.options = {
      "closeButton": false,
      "debug": false,
      "newestOnTop": false,
      "progressBar": true,
      "positionClass": "toast-top-right",
      "preventDuplicates": false,
      "onclick": null,
      "showDuration": "300",
      "hideDuration": "1000",
      "timeOut": "3000",
      "extendedTimeOut": "1000",
      "showEasing": "swing",
      "hideEasing": "linear",
      "showMethod": "fadeIn",
      "hideMethod": "fadeOut"
    }
  </script>

  <script>
    $(function() {
      $('#form-login').validate();
      $("#form-login").on("submit", function(e) {
        if ($('#form-login').valid()) {
          $(':input[type="submit"]').prop('disabled', true);
          var formData = new FormData(this);
          $.ajax({
            url: this.action,
            type: 'post',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(data) {
             
              if (data.status == 200) {
                console.log('fvfv');
                toastr.success(data.message);
                window.setTimeout(function() {

                  window.location.href = data.url;
                }, 2000);
              } else {
                $(':input[type="submit"]').prop('disabled', false);
                toastr.error(data.message);
              }
            }
          });
          e.preventDefault();
        }
      });
    })
  </script>
</body>

</html>
