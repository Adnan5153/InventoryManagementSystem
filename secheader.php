<style>
  body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;  
  }
  .secondary-header {
    background-color: #5B5858;
    color: white;
    padding: 10px 0;
    text-align: left;
    font-size: 18px;
  }
  
  .secondary-header a {
    color: white;
    text-decoration: none;
    padding: 0 15px;
  }
  
  .secondary-header a:hover,
  .secondary-header a.active { /* Style for hover and active link */
    text-decoration: underline;
    color: #CCCCCC;
  }
</style>

<!-- Secondary navigation bar -->
<div class="secondary-header">
    <?php $current_page = basename($_SERVER['PHP_SELF']); ?>
    <a href="home.php" class="<?php echo $current_page == 'home.php' ? 'active' : ''; ?>">Home</a>
    <a href="lab.php" class="<?php echo $current_page == 'lab.php' ? 'active' : ''; ?>">Lab</a>
    <a href="lab-assistant.php" class="<?php echo $current_page == 'lab-assistant.php' ? 'active' : ''; ?>">Lab Assistant</a>
    <a href="lab-inventory.php" class="<?php echo $current_page == 'lab-inventory.php' ? 'active' : ''; ?>">Lab Inventory</a>
    <a href="lab-assignment.php" class="<?php echo $current_page == 'lab-assignment.php' ? 'active' : ''; ?>">Lab Assignment</a>
</div>
