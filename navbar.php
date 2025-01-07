<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Navbar with Dropdown</title>
  <style>
    /* Basic Navbar Styles */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #2c3e50;
      padding: 10px 20px;
      color: white;
    }
    .navbar .logo {
      font-size: 20px;
      font-weight: bold;
    }
    .navbar .menu {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .menu-item {
      position: relative;
      cursor: pointer;
    }
    .menu-item a {
      color: white;
      text-decoration: none;
      padding: 5px 10px;
    }
    .menu-item:hover .dropdown {
      display: block;
    }

    /* Dropdown Styles */
    .dropdown {
      display: none;
      position: absolute;
      top: 100%;
      left: 0;
      background-color: white;
      color: #333;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      min-width: 150px;
      z-index: 1000;
    }
    .dropdown a {
      display: block;
      padding: 10px;
      color: #333;
      text-decoration: none;
    }
    .dropdown a:hover {
      background-color: #f1f1f1;
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div class="logo"><?php echo $_SESSION['user_name'];?></div>
      <div class="menu-item">
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>

</body>
</html>
