<html>
<head>

  <?php include "includes/head.php"; ?>
  <link rel="stylesheet" href="css/play.css">

</head>
<body>


  <?php
  // Connectto MySQL
  require "includes/mysql_connect.php";

  // Read the request
  $group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);
  $image = mysqli_real_escape_string($conn, $_REQUEST['image']);
  $id = mysqli_real_escape_string($conn, $_REQUEST['id']);

  ?>

  <div class="wrapper">

    <?php include "includes/header.php"; ?>

    <div class="game">

      <div class="aside">

        <div class="scores_wrapper">


        <?php

        require "includes/colors.php";

        // Get column names
        $members_name = array();
        $sql = "SHOW COLUMNS FROM `$group_name`";
        $result = mysqli_query($conn,$sql);
        $column_index = 0;
        while($row = mysqli_fetch_array($result)) {
          if($column_index>2){
            array_push($members_name,$row['Field']);
          }
          $column_index ++;
        }

        ?>

        <?php



        $sql = "SELECT ";
        for ($member_index = 0; $member_index < count($members_name); $member_index++) {
          if($member_index>0) {
            $sql .= ",";
          }
          $sql .= "`".$members_name[$member_index] ."` ";
        }
        $sql .= "FROM `". $group_name ."` WHERE `id` = ".$id.";";

        $result = mysqli_query($conn,$sql);
        $members_points = mysqli_fetch_assoc($result);


        ?>

        <!-- table of inputs to store the scores -->
        <table id="scores_table">

          <?php

          require "includes/colors.php";

          // NOT CLEAN AT ALL
          for($member_index=0; $member_index<count($members_name);$member_index++){
            echo "<tr style='color:".$colors[$member_index]."'>";
            echo "<td>".$members_name[$member_index]."</td>";
            echo "<td>".$members_points[$members_name[$member_index]]."</td>";
            echo "</tr>";
          }

          ?>

        </table>
        </div>

        <div class="buttons_wrapper">

          <form action='show_results.php' method='get'>
            <?php echo "<input type='hidden' name='group_name' value='".$_REQUEST['group_name']."'>"; ?>
            <input type='submit' value='Return to results table'>
          </form>

        </div>
      </div>

      <div class="canvas_wrapper">
        <img id="image"  src="<?php echo "images/results/".$image;?>">
      </div>

    </div>

    <?php include "includes/footer.php"; ?>

  </div>

</body>
</html>
