<?php include('db_connect.php'); ?>
<title>Inventory</title>

<div class="container-fluid">
  <div class="row mt-3">
    <div class="col-lg-12">

      <!-- Table Panel -->
      <div class="card">
        <div class="card-header">
          <h4 class="float-left"><b>Inventory</b></h4>
          <button class="btn btn-sm btn-secondary ml-2 toggle_archive col-sm-1 float-right">
            <?php
            $archived = isset($_GET['archived']) ? $_GET['archived'] : 0;
            $button_label = $archived ? 'Unarchived' : 'Archived';
            echo $button_label;
            ?>
          </button>
          <button class="btn btn-sm btn-warning ml-2 toggle_availability col-sm-1 float-right">
            <?php
            $status = isset($_GET['status']) ? $_GET['status'] : 1;
            $button_label = $status ? 'Unavailable' : 'Available';
            echo $button_label;
            ?>
          </button>

          <button class="btn btn-primary btn-block btn-sm col-sm-1 float-right" type="button" id="new_inventory">
            <i class="fa fa-plus"></i> New</button>
        </div>
        <div class="card-body table-responsive">
          <table id="myTable" class="table table-bordered table-sm table-striped nowrap compact">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="">Item Name</th>
                <th class="">Item Image</th>
                <th class="">Description</th>
                <th class="">Equipment ID</th>
                <th class="">Date Added</th>
                <?php if (!isset($_GET['status']) || $_GET['status'] != 0): ?>
                  <th class="text-center">Action</th>
                <?php endif; ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $archived = isset($_GET['archived']) ? $_GET['archived'] : 0;
              $status = isset($_GET['status']) ? $_GET['status'] : 1;
              $inventory_inventorys = $conn->query("SELECT * FROM inventory WHERE archived = $archived AND status = $status AND id NOT IN (SELECT equipment_id FROM borrowed WHERE returned_date IS NULL) ORDER BY id DESC");
              while ($row = $inventory_inventorys->fetch_assoc()):
                ?>
                <tr>
                  <td class="text-center">
                    <?php echo $i++ ?>
                  </td>
                  <td class="">
                    <p><b>
                        <?php echo ucwords($row['item_name']) ?>
                      </b></p>
                  </td>
                  <td class="tex-center">
                    <div class="img-container">
                      <img src="<?php echo $row['item_image'] ?>" class="" alt="">
                    </div>
                  </td>
                  <td class="">
                    <p>
                      <?php echo ucwords($row['description']) ?>
                    </p>
                  </td>
                  <td class="">
                    <p><b>
                        <?php echo ucwords($row['equipment_id']) ?>
                      </b></p>
                  </td>
                  <td class="">
                    <?php echo $row['created_at'] ?>
                  </td>
                  <?php if (!isset($_GET['status']) || $_GET['status'] != 0): ?>
                    <td class="text-center">
                      <?php if ($row['archived'] == 0): ?>
                        <button class="btn btn-sm btn-primary edit_inventory mr-2" type="button"
                          data-id="<?php echo $row['id'] ?>">Edit</button>
                        <button class="btn btn-sm btn-info borrow_equipment mr-2" type="button"
                          data-id="<?php echo $row['equipment_id'] ?>">Borrow</button>
                        <button class="btn btn-sm btn-danger archive_inventory" type="button"
                          data-id="<?php echo $row['id'] ?>"><span>Delete</span></button>
                      <?php else: ?>
                        <button class="btn btn-sm btn-success unarchive_inventory" type="button"
                          data-id="<?php echo $row['id'] ?>">Unarchive</button>
                      <?php endif; ?>
                    </td>
                  <?php endif; ?>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Table Panel -->
    </div>
  </div>
</div>
<script>
  $("#new_inventory").click(function () {
    uni_modal("New Item", "manage_inventory.php", "mid-large");
  });

  $(".edit_inventory").click(function () {
    uni_modal(
      "Edit Item",
      "manage_inventory.php?id=" + $(this).attr("data-id"),
      "mid-large"
    );
  });
  $(".borrow_equipment").click(function () {
    uni_modal(
      "Borrow Equipment",
      "manage_borrow.php?id=" + $(this).attr("data-id"),
      "mid-large"
    );
  });

  $(".archive_inventory").click(function () {
    _conf("Are you sure to delete this inventory?", "archive_inventory", [$(this).attr("data-id")], "mid-large");
  });

  function archive_inventory($id) {
    start_load();
    $.ajax({
      url: "ajax.php?action=archive_inventory",
      method: "POST",
      data: { id: $id },
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully deleted", "success");
          setTimeout(function () {
            location.reload();
          }, 1500);
        }
      },
    });
  }

  $(".unarchive_inventory").click(function () {
    _conf("Are you sure to unarchive this inventory?", "unarchive_inventory", [$(this).attr("data-id")], "mid-large");
  });

  function unarchive_inventory($id) {
    start_load();
    $.ajax({
      url: "ajax.php?action=unarchive_inventory",
      method: "POST",
      data: { id: $id },
      success: function (resp) {
        if (resp == 1) {
          alert_toast("Data successfully unarchived", "success");
          setTimeout(function () {
            location.reload();
          }, 1500);
        }
      },
    });
  }

  $(".toggle_archive").click(function () {
    var currentUrl = window.location.href;
    var showArchived = currentUrl.includes('archived=1');
    var newArchivedValue = showArchived ? 0 : 1;

    window.location.href = currentUrl.includes('archived=') ?
      currentUrl.replace(/archived=\d/, 'archived=' + newArchivedValue) :
      currentUrl + '&archived=' + newArchivedValue;
  });

  $(".toggle_availability").click(function () {
    var currentUrl = window.location.href;
    var showNotAvailable = currentUrl.includes('status=0');
    var newNotAvailableValue = showNotAvailable ? 1 : 0;

    window.location.href = currentUrl.includes('status=') ?
      currentUrl.replace(/status=\d/, 'status=' + newNotAvailableValue) :
      currentUrl + '&status=' + newNotAvailableValue;
  });

</script>