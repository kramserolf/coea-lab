<?php
session_start();
include('db_connect.php');
ob_start();
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>Smart COEA Laboratory Login</title>
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
  <?php include('./header.php'); ?>
  <?php
  if (isset($_SESSION['login_id']))
    header('location:index.php?page=home');

  ?>

  <style>
    body {
      background: #f2f2f2 !important;
      color: #333;
    }

    .login {
      min-height: 100vh;
    }

    .bg-image {
      background-image: url(assets/img/csu-bg.png);
      background-size: cover;
      background-position: center;
      filter: brightness(100%);
    }

    .btn-login {
      font-weight: 600;
      font-size: 0.9rem;
      letter-spacing: 0.05rem;
      padding: 0.75rem 1rem;
    }

    .btn-login:hover {
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .password-container {
      position: relative;
    }

    .EyePass {
      position: absolute;
      right: 10px;
      top: 80%;
      transform: translate(0, -80%);
      cursor: pointer;
    }
  </style>
</head>

<body>
  <div class="container-fluid ps-md-0">
    <div class="row g-0">
      <div class="d-none d-md-flex col-md-4 col-lg-8 bg-image" data-aos="fade-right" data-aos-offset="300"
        data-aos-easing="ease-in-out" data-aos-duration="2000"></div>
      <div class="col-md-8 col-lg-4">
        <div class="login d-flex align-items-center py-5">
          <div class="container">
            <div class="row">
              <div class="col-md-9 col-lg-10 mx-auto text-center ">
                <!-- Brand -->
                <div class="text-center">
                  <img src="./assets/img/csu-logo.png" alt="Logo" width="400" height="100">
                  <h4 class="mt-3 mb-4">Smart COEA Laboratory: Equipment Monitoring And Inventory System</h4>
                </div>

                <!-- Sign In Form -->
                <form id="login-form" class="text-left">
                  <label class="control-label">Username:</label>
                  <div class="mb-3">
                    <input type="text" class="form-control pt-4 pb-4" id="username" name="username"
                      placeholder="Enter Username">
                  </div>
                  <div class="mb-3">
                    <div class="password-container">
                      <label class="control-label">Password:</label>
                      <input type="password" class="form-control pt-4 pb-4" id="password" name="password"
                        placeholder="Enter Password">
                      <span class="EyePass">
                        <i class="fa fa-eye" aria-hidden="true" id="eye" onclick="toggle()"></i>
                      </span>
                    </div>

                  </div>
                  <div class="d-grid">
                    <button class="btn btn-lg btn-danger btn-login text-uppercase fw-bold mb-5 w-100"
                      type="submit">Login</button>
                  </div>
                </form>
                <small class="text-center text-muted mx-auto ">&copy; <strong><span> Brix Cyrus Geron, Rovi Ulep, Cris Jhon Garan, Ingrid Mariz Andres</span></strong>
                  2024. All Rights Reserved</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

</body>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init();
  $('#login-form').submit(function (e) {
    e.preventDefault()
    $('#login-form button[type="button"]').attr('disabled', true).html('Logging in...');
    if ($(this).find('.alert-danger').length > 0)
      $(this).find('.alert-danger').remove();
    $.ajax({
      url: 'ajax.php?action=login',
      method: 'POST',
      data: $(this).serialize(),
      error: err => {
        console.log(err)
        $('#login-form button[type="button"]').removeAttr('disabled').html('Login');

      },
      success: function (resp) {
        if (resp == 1) {
          location.href = 'index.php?page=home';
        } else {
          $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
          $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
        }
      }
    })
  })

  // Password toggle eye icon
  var state = false;

  function toggle() {
    // Use the name attribute to select the password input
    var passwordInput = document.getElementsByName("password")[0];

    if (state) {
      passwordInput.setAttribute("type", "password");
      document.getElementById("eye").style.color = '#7a797e';
      state = false;
    } else {
      passwordInput.setAttribute("type", "text");
      document.getElementById("eye").style.color = '#5887ef';
      state = true;
    }
  }

</script>


</html>