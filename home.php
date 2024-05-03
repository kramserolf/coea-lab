<?php
include('db_connect.php');

// Fetch total inventory count
$sql_inventory = "SELECT COUNT(*) as total_inventory FROM inventory";
$result_inventory = mysqli_query($conn, $sql_inventory);
$row_inventory = mysqli_fetch_assoc($result_inventory);
$total_inventory = $row_inventory['total_inventory'];

// Fetch total borrowers count
$sql_borrowers = "SELECT COUNT(*) as total_borrowers FROM borrowers";
$result_borrowers = mysqli_query($conn, $sql_borrowers);
$row_borrowers = mysqli_fetch_assoc($result_borrowers);
$total_borrowers = $row_borrowers['total_borrowers'];

// Fetch total borrowed equipment count
$sql_borrowed = "SELECT COUNT(*) as total_borrowed FROM borrowed WHERE returned_date IS NULL";
$result_borrowed = mysqli_query($conn, $sql_borrowed);
$row_borrowed = mysqli_fetch_assoc($result_borrowed);
$total_borrowed = $row_borrowed['total_borrowed'];

// Fetch total returned equipment count
$sql_returned = "SELECT COUNT(*) as total_returned FROM borrowed WHERE returned_date IS NOT NULL";
$result_returned = mysqli_query($conn, $sql_returned);
$row_returned = mysqli_fetch_assoc($result_returned);
$total_returned = $row_returned['total_returned'];
?>

<style>
  .summary_icon {
    font-size: 2rem;
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 13px 65%;
    color: #f8f8f8;
  }

  .card {
    border: none;
    border-radius: 10px;
    overflow: hidden;
    height: 200px;
    position: relative;
  }

  .Cards {
    padding: 20px;
    display: flex;
    flex-direction: column;
    justify-content: center;
  }

  .Cards h1 {
    font-size: 3rem;
    color: #fff;
  }

  .Cards p {
    text-align: left;
    margin: 0;
    letter-spacing: 1px;
    font-size: 1.5rem;
    color: #fff;
  }

  .red {
    background-color: red;
  }

  .yellow {
    background-color: green;
  }

  .green {
    background-color: blue;
  }

  .violet {
    background-color: violet;
  }
</style>
<div class="container-fluid">
  <div class="row mt-3 ">
    <div class="col-md-12">
      <div class="row">
        <br>
        <div class="col-md-3">
          <div class="card bg-primary">
            <div class="summary_icon red">
              <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <div class="card-body Cards">
              <h1 class="count"><b class="count-up">
                  <?php echo $total_inventory; ?>
                </b></h1>
              <p>Total Inventory</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-secondary ">
            <div class="summary_icon yellow">
              <i class="fa-solid fa-users-gear"></i>
            </div>
            <div class="card-body Cards text-white">
              <h1 class="count"><b class="count-up">
                  <?php echo $total_borrowers; ?>
                </b></h1>
              <p>Total Borrowers</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-success">
            <div class="summary_icon green">
              <i class="fa-solid fa-hand-holding-droplet"></i>
            </div>
            <div class="card-body Cards">
              <h1 class="count"><b class="count-up">
                  <?php echo $total_borrowed; ?>
                </b></h1>
              <p>Total Borrowed</p>
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card bg-info">
            <div class="summary_icon violet">
              <i class="fa-solid fa-rotate-left"></i>
            </div>
            <div class="card-body Cards">
              <h1 class="count"><b class="count-up">
                  <?php echo $total_returned; ?>
                </b></h1>
              <p>Total Transactions</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <br>
  <br>
  <p>Scan the QR code to register as a new borrower and follow the given text format as the example:</p>
  <img src="assets/img/frame1.png" alt="QR Code Generator" width="470" height="470">
  <img src="assets/img/Sample1.png" alt="QR Code Generator" width="700" style="float: right">
</div>
