<?php include('db_connect.php'); ?>
<title>Borrowed Equipment</title>

<div class="container-fluid">
  <div class="row mt-3">
    <div class="col-lg-12">
      <!-- Table Panel -->
      <div class="card">
        <div class="card-header">
          <h4 class="float-left"><b>Borrowed Equipment</b></h4>
        </div>
        <div class="card-body table-responsive">
          <table id="myTable" class="table table-bordered table-sm table-striped nowrap compact">
            <thead>
              <tr>
                <th class="text-center">#</th>
                <th class="">Equipment ID</th>
                <th class="">ID Number</th>
                <th class="">Borrowed Date</th>
                <th class="">Returned Date</th>
                <th class="text-center">Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              $equipment_borrowed = $conn->query("SELECT * FROM borrowed ORDER BY id DESC");
              while ($row = $equipment_borrowed->fetch_assoc()):
                ?>
                <tr>
                  <td class="text-center">
                    <?php echo $i++ ?>
                  </td>
                  <td><b>
                      <?php echo ucwords($row['equipment_id']) ?>
                    </b>
                  </td>
                  <td><b>
                      <?php echo ucwords($row['id_number']) ?>
                    </b>
                  </td>
                  <td>
                    <?php echo $row['borrowed_date'] ?>
                  </td>
                  <td class="">
                    <?php echo $row['returned_date'] ? $row['returned_date'] : 'Not returned' ?>
                  </td>

                  <td class="text-center">
                    <button class="btn btn-sm btn-success return_equipment mr-2" type="button"
                      data-id="<?php echo $row['equipment_id'] ?>">Return</button>
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
  $(".return_equipment").click(function () {
    uni_modal("Return Equipment", "manage_return.php?id=" + $(this).attr("data-id"), "mid-large");
  });
</script>