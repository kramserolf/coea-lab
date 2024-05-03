<?php include('db_connect.php'); ?>
<title>Borrowers</title>

<div class="container-fluid">
  <div class="row mt-3">
    <div class="col-lg-12">

      <!-- Table Panel -->
      <div class="card">
        <div class="card-header">
          <h4 class="float-left"><b>Borrowers</b></h4>
          <button class="btn btn-sm btn-secondary ml-2 toggle_archive col-sm-1 float-right " type="button">
            <?php
            $archived = isset($_GET['archived']) ? $_GET['archived'] : 0;
            $button_label = $archived ? 'Unarchived' : 'Archived';
            echo $button_label;
            ?>
          </button>
          <button class="btn btn-primary btn-block btn-sm col-sm-1 float-right" type="button" id="new_borrower">
            <i class="fa fa-plus"></i> New</button>
        </div>
        <div class="card-body table-responsive">
          <table id="myTable" class="table table-bordered table-sm table-striped nowrap compact">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="">Name</th>
                <th class="">Image</th>
                <th class="">ID Number</th>
                <th class="">Contact Number</th>
                <th class="">Date Added</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $archived = isset($_GET['archived']) ? $_GET['archived'] : 0;
              $users = $conn->query("SELECT * FROM borrowers WHERE archived = $archived ORDER BY id DESC");
              while ($row = $users->fetch_assoc()):
                ?>
                <tr>

                  <td class="text-center">
                    <?php echo $i++ ?>
                  </td>
                  <td class="">
                    <p><b>
                        <?php echo ucwords($row['name']) ?>
                      </b></p>

                  </td>
                  <td class="tex-center">
                    <div class="img-container">
                      <img src="<?php echo $row['image'] ?>" class="" alt="">
                    </div>
                  </td>


                  <td class=""><b>
                      <?php echo $row['id_number']; ?>
                    </b>
                  </td>
                  <td class="">
                    <?php echo $row['contact_number']; ?>
                  </td>
                  <td class="">
                    <?php echo $row['created_at']; ?>
                  </td>

                  <td class="text-center">
                    <?php if ($row['archived'] == 0): ?>
                      <button class="btn btn-sm btn-primary edit_borrower" type="button"
                        data-id="<?php echo $row['id'] ?>">Edit</button>
                    <?php endif; ?>
                    <?php if ($row['archived'] == 0): ?>
                      <!-- Display archive button only for unarchived  -->
                      <button class="btn btn-sm btn-danger archive_borrower" type="button"
                        data-id="<?php echo $row['id'] ?>"><span>Delete</span></button>
                    <?php else: ?>
                      <!-- Display unarchive button only for archived  -->
                      <button class="btn btn-sm btn-success unarchive_borrower" type="button"
                        data-id="<?php echo $row['id'] ?>">Unarchive</button>
                    <?php endif; ?>
                  </td>

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
  $("#new_borrower").click(function () {
    uni_modal("New Borrower", "manage_borrower.php", "mid-large");
  });

  $(".edit_borrower").click(function () {
    uni_modal(
      "Edit Borrower",
      "manage_borrower.php?id=" + $(this).attr("data-id"),
      "mid-large"
    );
  });

  $(".archive_borrower").click(function () {
    _conf("Are you sure to delete this borrower?", "archive_borrower", [$(this).attr("data-id")], "mid-large");
  });

  $(".unarchive_borrower").click(function () {
    _conf("Are you sure to unarchive this borrower?", "unarchive_borrower", [$(this).attr("data-id")], "mid-large");
  });

  function archive_borrower($id) {
    start_load();
    $.ajax({
      url: "ajax.php?action=archive_borrower",
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
  function unarchive_borrower($id) {
    start_load();
    $.ajax({
      url: "ajax.php?action=unarchive_borrower",
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

  $(".toggle_archive").click(function () {
    var currentUrl = window.location.href;
    var showArchived = currentUrl.includes('archived=1');
    var newArchivedValue = showArchived ? 0 : 1;

    window.location.href = currentUrl.includes('archived=') ?
      currentUrl.replace(/archived=\d/, 'archived=' + newArchivedValue) :
      currentUrl + '&archived=' + newArchivedValue;
  });

</script>