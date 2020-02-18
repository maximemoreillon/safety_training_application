<html lang="en">
<head>
  <?php include "includes/head.php"; ?>
</head>
<body>

  <div class="wrapper">

    <?php include "includes/header.php"; ?>

    <div class="main">

      <h1>
        <?php echo $_REQUEST['group_name']; ?>
      </h1>


      <?php

      // Connect to the DB and get the names of the members

      require "includes/mysql_connect.php";

      // Get group name from request
      $group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);

      // Get column names
      $column_names = array();
      $sql = "SHOW COLUMNS FROM `$group_name`";
      $result = mysqli_query($conn,$sql);
      while($row = mysqli_fetch_array($result)) {
        array_push($column_names,$row['Field']);
      }

      ?>


      <table class="results_table">
        <tr>
          <!-- the headers of the columns -->
          <th>Date</th>
          <th>View results</th>
          <th>Delete</th>

          <?php

          // Get colors from external file
          require "includes/colors.php";

          // Headers of the table for members
          for ($column_index = 3; $column_index < count($column_names); $column_index++) {
            $member_index = $column_index - 3;
            echo "<th style='color:".$colors[$member_index].";' >".$column_names[$column_index]."</th>";
          }

          ?>

        </tr>

        <?php



        // Member points and images
        // Get columns we're interested into (date, image and member names)
        $sql = "SELECT id, date, image";
        for ($column_index = 3; $column_index < count($column_names); $column_index++) {
          $sql .= ",`". $column_names[$column_index] ."`";
        }
        $sql .= "FROM `". $group_name ."`" ;
        $result = mysqli_query($conn,$sql);

        $row_index = 0;
        while($row = mysqli_fetch_array($result)) {

          // ignore first line that is empty anyway
          if($row_index>0) {
            echo "<tr>";
            echo "<td class='date'>".$row["date"]."</td>"; // Date

            // Button to view individual result
            echo "<td>";
            echo '<form action="view_result.php" method="get">';
            echo '<input type="hidden" name="id" value="'.$row["id"].'">';
            echo '<input type="hidden" name="group_name" value="'.$_REQUEST['group_name'].'">';
            echo '<input type="hidden" name="image" value="'.$row["image"].'">';
            echo '<input type="submit" value="View">';
            echo "</form>";
            echo "</td>";

            echo "<td>";
            echo '<form action="delete_result.php" method="get">';
            echo '<input type="hidden" name="id" value="'.$row["id"].'">';
            echo '<input type="hidden" name="group_name" value="'.$_REQUEST['group_name'].'">';
            echo '<input type="submit" value="Delete">';
            echo "</form>";
            echo "</td>";


            // Score of the member
            for ($column_index = 3; $column_index < count($column_names); $column_index++) {
              echo "<td>".$row[$column_names[$column_index]]."</td>";
            }
            echo "</tr>";
          }
          $row_index ++;
        }

        ?>

        <!-- the last line is for the sum -->
        <tr>
          <td></td>
          <td></td>
          <td><b>Sum:</b></td>

          <?php

          // COmpute the sum of each member

          for ($i = 3; $i < count($column_names); $i++) {
            $sql = "SELECT SUM(`$column_names[$i]`) AS value_sum FROM `$group_name`";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            echo "<td>".$row["value_sum"]."</td>";
          }

          ?>
        </tr>
      </table>

      <?php mysqli_close($conn); ?>

      <!-- Buttons -->
      <form action="play.php" method="get" enctype="multipart/form-data">
        <?php echo "<input type='hidden' name='group_name' value='".$_REQUEST['group_name']."'>"; ?>
        <input type="submit" value="Start">
      </form>

      <form action="group_selection.php" method="get">
        <input type="submit" value="Return to group selection">
      </form>

    </div> <!-- end of main -->

    <?php include "includes/footer.php"; ?>
  </div> <!-- end of wrapper -->



</body>
</html>
