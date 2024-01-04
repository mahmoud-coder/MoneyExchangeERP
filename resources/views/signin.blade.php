<!DOCTYPE html>
<html
  lang="en"
  class="light-style customizer-hide"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="/assets/"
  data-template="vertical-menu-template-no-customizer"
>
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
    />

    <title>UAB Moneybeat | Signin </title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{!! asset('assets/img/favicon/favicon.png') !!}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet"
    />

    <!-- Icons -->
    <link rel="stylesheet" href="{!! asset('/assets/vendor/fonts/fontawesome.css') !!}" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/fonts/tabler-icons.css') !!}" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/fonts/flag-icons.css') !!}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{!! asset('/assets/vendor/css/core.css') !!}" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/css/theme-default.css') !!}" />
    <link rel="stylesheet" href="{!! asset('/assets/css/demo.css') !!}" />
    
    <!-- Vendors CSS'-->
    <link rel="stylesheet" href="{!! asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') !!}" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/libs/node-waves/node-waves.css') !!}" />
    <link rel="stylesheet" href="{!! asset('/assets/vendor/libs/typeahead-js/typeahead.css') !!}" />
    <!-- Vendor -->
    <link rel="stylesheet" href="{!! asset('/assets/vendor/libs/formvalidation/dist/css/formValidation.min.css') !!}" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{!! asset('/assets/vendor/css/pages/page-auth.css') !!}" />
    <!-- Helpers -->
    <script src="{!! asset('/assets/vendor/js/helpers.js') !!}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{!! asset('/assets/js/config.js') !!}"></script>
  </head>

  <body>
    <!-- Content -->

    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <!-- Login -->
          <div class="card">
            <div class="card-body">
              <!-- Logo -->
              <div class="app-brand justify-content-center mb-4 mt-2">
                <a href="/" class="text-center">
                    <img src="{!! asset('assets/img/logo.png') !!}" alt="Money Beat Logo"  class="w-75 h-auto"></img>
                </a>
              </div>
              <!-- /Logo -->
              <h4 class="mb-1 pt-2">Welcome to UAB Monybeat! 👋</h4>
              <p class="mb-4">Please sign-in to your account and start the adventure</p>

              <form id="formAuthentication" class="mb-3" action="{!! route('auth') !!}" method="POST">
                @csrf
                <div class="mb-3">
                  <label for="email" class="form-label">Email or Username</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email_username"
                    placeholder="Enter your email or username"
                    autofocus
                  />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Password</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="password"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password"
                    />
                    <span class="input-group-text cursor-pointer" onclick="toggleShowPassword()"><i id="show-password-icon" class="ti ti-eye-off"></i></span>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="remember-me" />
                    <label class="form-check-label" for="remember-me"> Remember Me </label>
                  </div>
                </div>
                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Sign in</button>
                </div>
              </form>
            </div>
          </div>
          <!-- /Register -->
        </div>
      </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{!! asset('/assets/vendor/libs/jquery/jquery.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/libs/popper/popper.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/js/bootstrap.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/libs/node-waves/node-waves.js') !!}"></script>

    <script src="{!! asset('/assets/vendor/libs/hammer/hammer.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/libs/typeahead-js/typeahead.js') !!}"></script>

    <script src="{!! asset('/assets/vendor/js/menu.js') !!}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{!! asset('/assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js') !!}"></script>
    <script src="{!! asset('/assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js') !!}"></script>

    <!-- Main JS -->
    <script src="{!! asset('/assets/js/main.js') !!}"></script>

    <!-- Page JS -->
    <script src="{!! asset('/assets/js/pages-auth.js') !!}"></script>
    <script>
      function toggleShowPassword(){
        let $password = $("#password")
        if($password.attr('type') == 'password'){
          $password.attr('type', 'text')
        }else{
          $password.attr('type', 'password')
        }
        $('#show-password-icon').toggleClass('ti-eye-off').toggleClass('ti-eye')
      }
    </script>
  </body>
</html>
