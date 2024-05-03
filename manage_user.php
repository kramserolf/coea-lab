<?php
include('db_connect.php');
session_start();
if (isset($_GET['id'])) {
  $user = $conn->query("SELECT * FROM users where id =" . $_GET['id']);
  foreach ($user->fetch_array() as $k => $v) {
    $meta[$k] = $v;
  }
}
?>
<style>
  .password-container {
    position: relative;
  }

  .EyePass {
    position: absolute;
    right: 10px;
    top: 78%;
    transform: translate(0, -78%);
    cursor: pointer;
  }
</style>
<div class="container-fluid">
  <div id="msg"></div>

  <form action="" id="manage-user">
    <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
    <div class="form-group">
      <label for="name">Name</label>
      <input type="text" name="name" id="name" class="form-control"
        value="<?php echo isset($meta['name']) ? $meta['name'] : '' ?>" required>
    </div>
    <div class="form-group">
      <label for="username">Username</label>
      <input type="text" name="username" id="username" class="form-control"
        value="<?php echo isset($meta['username']) ? $meta['username'] : '' ?>" required autocomplete="off">
    </div>
    <div class="form-group">
      <label for="current_password">Current Password</label>
      <input type="password" name="current_password" id="current_password" class="form-control" required>
    </div>
    <div class="form-group">
      <div class="password-container">
        <label for="password">New Password</label>
        <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
        <span class="EyePass">
          <i class="fa fa-eye" aria-hidden="true" id="eye" onclick="toggle()"></i>
        </span>
      </div>
      <small><i>Leave this blank if you don't want to change the password.</i></small>
    </div>
    <?php if (isset($meta['type']) && $meta['type'] == 3): ?>
      <input type="hidden" name="type" value="3">
    <?php else: ?>
      <?php if (!isset($_GET['mtype'])): ?>
        <div class="form-group">
          <label for="type">User Type</label>
          <select name="type" id="type" class="custom-select">
            <option value="1" <?php echo isset($meta['type']) && $meta['type'] == 1 ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <hr>
    <div class="row">
      <div class="col-md-12 d-flex justify-content-end">
        <button class="btn btn-primary float-right mr-2" type="submit">Submit</button>
        <button class="btn btn-secondary float-right" data-dismiss="modal">Dismiss</button>
      </div>
    </div>
  </form>
</div>
<script>
  $('#manage-user').submit(function (e) {
    e.preventDefault();
    start_load();
    $.ajax({
      url: 'ajax.php?action=save_user',
      method: 'POST',
      data: $(this).serialize(),
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully saved", 'success');
          setTimeout(function () {
            location.reload();
          }, 1500);
        }
        else if (resp == 2) {
          $('#msg').html('<div class="alert alert-danger">Username already exists</div>');
        } else {
          // Display the error message received from the server
          $('#msg').html('<div class="alert alert-danger">' + resp + '</div>');
        }
        end_load();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        // Handle AJAX request errors and display error details
        $('#msg').html('<div class="alert alert-danger">AJAX Error: ' + textStatus + ' - ' + errorThrown +
          '</div>');
        end_load();
      }
    });
  });

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