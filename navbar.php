<style>
  nav#sidebar {
    background: #061043;
    height: 100%;
    position: fixed;
    width: 250px;
    z-index: 1;
    overflow-x: hidden;
    transition: 0.5s;
  }

  .sidebar-list {
    padding: 0;
    list-style: none;
  }

  .nav-item {
    padding: 10px;
    text-decoration: none;
    font-size: 16px;
    color: #FFD700;
    display: block;
    transition: 0s;
  }

  .nav-item:hover {
    background-color: #6B0505;
    color: #FFD700;
  }

  .nav-item:not(.active) {
    background-color: #061043;
    color: #fff;
  }

  .nav-item:active {
    background-color: #6B0505;
    color: #fff;
  }

  .icon-field {
    margin-right: 10px;
  }

  .brand-logo {
    display: flex;
    justify-content: center;
  }

  .brand-logo img {
    max-width: 100%;
    height: auto;
  }
</style>

<nav id="sidebar">
  <div class="sidebar-list">
    <a href="index.php?page=home" class="nav-item nav-home active">
      <span class='icon-field'><i class="fa-solid fa-gauge"></i></span> Dashboard
    </a>
    <a href="index.php?page=inventory" class="nav-item nav-inventory">
      <span class='icon-field'><i class="fa-solid fa-screwdriver-wrench"></i></span> Inventory
    </a>
    <a href="index.php?page=transactions" class="nav-item nav-transactions">
      <span class='icon-field'><i class="fa-solid fa-arrow-right-arrow-left"></i></span> Transactions
    </a>
    <a href="index.php?page=borrowers" class="nav-item nav-borrowers">
      <span class='icon-field'><i class="fa-solid fa-users-gear"></i></span> Borrowers
    </a>
  </div>
</nav>


<script>
  $('.nav_collapse').click(function () {
    console.log($(this).attr('href'))
    $($(this).attr('href')).collapse()
  })
  $('.nav-<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>').addClass('active')
  if ('<?php echo isset($_GET['page']) ? $_GET['page'] : '' ?>' !== 'home') {
    $('.nav-home').removeClass('active');
  }

</script>