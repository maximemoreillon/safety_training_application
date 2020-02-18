<html lang="en">
<head>
  <?php include "includes/head.php"; ?>
</head>
<body>

  <div class="wrapper">

    <?php include "includes/header.php"; ?>

    <div class="main">
      <h1>
        Groups management
      </h1>

      <form action="member_names_input.php" method="get">
        Create new group with
        <select name='member_count'>
          <?php
          for ($i = 1; $i <= 20; $i++) {
            echo '<option value="'.$i.'">'.$i.'</option>';
          }
          ?>
        </select>
        member(s)
        <input type="submit" value="Create">
      </form>

      <form action="delete_group.php" method="get">
        Delete group:
        <select name='group_name'>
          <?php

          include "includes/mysql_connect.php";

          $sql = "SHOW tables";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_row()) {
                echo '<option value="'.$row[0].'">'.$row[0].'</option>';
            }
          }
          ?>
        </select>
        <input type="submit" value="Delete">
      </form>

      <form action="group_selection.php" method="post">
        <input type="submit" value="Return">
      </form>
    </div>

    <?php include "includes/footer.php"; ?>


  </div>



</body>
</html>
