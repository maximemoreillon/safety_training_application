<html lang="en">
<head>
  <?php include "includes/head.php"; ?>
</head>
<body>

  <div class="wrapper">

    <?php include "includes/header.php"; ?>

    <div class="main">

      <h1>
        Group selection
      </h1>

      <form action="show_results.php" method="get">
        <select name='group_name'>

          <?php

          // Fill the select with the group names

          include "includes/mysql_connect.php";

          $sql = "SHOW TABLES";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_row()) {
              echo '<option value="'.$row[0].'">'.$row[0].'</option>';
            }
          }

          ?>


        </select>
        <input type="submit" value="Select">
      </form>

      <!-- Button to go to group management page -->
      <form action="manage_groups.php" method="post">
        <input type="submit" value="Manage groups">
      </form>

    </div>

    <?php include "includes/footer.php"; ?>

  </div>

</body>
</html>
