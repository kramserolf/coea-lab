<?php include('db_connect.php'); ?>

<!-- HTML form for borrowing equipment -->
<div class="container-fluid">
  <form id="borrow-form">
    <input type="hidden" name="equipment_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
    <div class="form-group">
      <label for="id_number">Borrower ID Number</label>
      <input type="text" class="form-control" id="id_number" name="id_number" placeholder="Enter ID Number">
    </div>
    <div class="form-group">
      <label for="equipment_id">Equipment ID</label>
      <input type="text" class="form-control" id="equipment_id" name="equipment_id" readonly
        value="<?php echo isset($_GET['id']) ? $_GET['id'] : 'No ID'; ?>">
    </div>
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
  $(document).ready(function () {
    $('#borrow-form').submit(function (e) {
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        type: 'POST',
        url: 'ajax.php?action=borrow_equipment',
        data: formData,
        success: function (response) {
          if (response == 1) {
            alert("Equipment borrowed successfully.");
            location.reload();
          } else {
            alert("Error: " + response);
          }
        }
      });
    });
  });
</script>