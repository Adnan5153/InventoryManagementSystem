<style>
  .header {
    background-color: #2480fc;
    color: white;
    padding: 10px 0;
    text-align: left;
    padding-left: 80px;
    font-size: 25px;
    position: relative; /* For absolute positioning of the dropdown */
    margin-bottom: 2%;
  }
  .logout-button {
    position: absolute;
    right: 10px;
    top: 10px;
    background-color: #f44336; /* Red color for the logout button */
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    padding: 10px 20px;
    font-size: 16px;
  }

</style>

<div class="header">
    Inventory Management System - CIU
    <button onclick="window.location.href='logout.php';" class="logout-button">Logout</button>
</div>
