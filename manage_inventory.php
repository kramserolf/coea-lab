<?php include 'db_connect.php' ?>
<?php
if (isset($_GET['id'])) {
  $qry = $conn->query("SELECT * FROM inventory where id=" . $_GET['id'])->fetch_array();
  foreach ($qry as $k => $v) {
    $$k = $v;
  }
}
?>
<div class="container-fluid">
  <form action="" id="manage-inventory">
    <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>" class="form-control">

    <div class="row form-group">
      <div class="col-md-12">
        <label class="control-label">Item Name</label>
        <input type="text" name="item_name" class="form-control" required
          value="<?php echo isset($item_name) ? $item_name : '' ?>">
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-12">
        <label class="control-label">Item Image</label>
        <!-- Input for selecting file -->
        <input type="file" name="item_image" class="form-control" required onchange="previewImage(event)">
        <!-- Image preview -->
        <img id="image-preview" src="<?php echo isset($item_image) ? $item_image : '' ?>"
          style="max-width: 100px; max-height: 100px;">
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-12">
        <label class="control-label">Description</label>
        <textarea name="description" required
          class="form-control"><?php echo isset($description) ? $description : '' ?></textarea>
      </div>
    </div>

    <div class="row form-group">
      <div class="col-md-12">
        <label class="control-label">Equipment ID</label>
        <input type="number" name="equipment_id" class="form-control" required
          value="<?php echo isset($equipment_id) ? $equipment_id : '' ?>">
      </div>
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

  function previewImage(event) {
    var input = event.target;
    var reader = new FileReader();
    reader.onload = function () {
      var imagePreview = document.getElementById('image-preview');
      imagePreview.src = reader.result;
    };
    reader.readAsDataURL(input.files[0]);
  }

  $('#manage-inventory').submit(function (e) {
    e.preventDefault();
    start_load();
    $.ajax({
      url: 'ajax.php?action=save_inventory',
      method: 'POST',
      data: new FormData(this),
      contentType: false,
      processData: false,
      cache: false,
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully saved.", 'success');
          setTimeout(function () {
            location.reload();
          }, 1000);
        }
      }
    });
  });
</script>