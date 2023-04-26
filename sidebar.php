<ul class="header-nav">
  <li><a href="logout.php">Logout</a></li>
  <li><a href="home.php">Change Password</a></li>
  <li><a href="home.php">Profile</a></li>
  <li><a href="home.php">Home</a></li>
  <li>
    <span class="text-uppercase text-warning">
      <?php
      $sql = "SELECT * FROM users WHERE user_id='".$_SESSION['userdata']['user_id']."'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo ucfirst($row['name']);
      } ?>
    </span>
  </li>
</ul>