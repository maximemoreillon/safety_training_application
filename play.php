<html>
<head>

  <?php include "includes/head.php"; ?>

  <!-- CSS -->
  <link rel="stylesheet" href="css/play.css">

  <!-- JS -->
  <script src="js/shoushuudan.js"></script>
</head>
<body>


  <?php
  // Connectto MySQL
  require "includes/mysql_connect.php";

  // Read the request
  $group_name = mysqli_real_escape_string($conn, $_REQUEST['group_name']);

  ?>

  <div class="wrapper">

    <?php include "includes/header.php"; ?>

    <div class="game">

      <div class="aside">
        <div class="group_name">
          <?php echo $group_name; ?>
        </div>

        <div class="scores_wrapper">
          <!-- table of inputs to store the scores -->
          <table id="scores_table">

            <?php

            require "includes/colors.php";

            $sql = "SHOW COLUMNS FROM `$group_name`";
            $result = mysqli_query($conn,$sql);

            $column_index = 0;
            while($row = mysqli_fetch_array($result)) {
              $field = $row['Field'];

                // Ignore columns not about members
                // Column 0: date
                // Column 1: image
              $member_index = $column_index - 3;
              if($member_index >= 0){
                echo "<tr style='color:".$colors[$member_index]."'>";

                // Write name of member for JS to read
                // Invisible by default
                echo "<td style='display: none;'>".$field."</td>";
                echo "<td style='display: none;'>0</td>";

                echo "</tr>";
              }
              $column_index ++;
            }
            ?>
          </table>
        </div>

        <div class="buttons_wrapper">
          <button id="start_button">Start</button>
          <button id="next_button">Next</button>
          <button id="undo_button">Undo</button>
          <button id="submit_button">Submit</button>
          <button id="return_button">Cancel and return</button>
        </div>
      </div> <!-- End of aside -->

      <div id="canvas_wrapper" class="canvas_wrapper">

        <?php
        // Take a random image from the situations folder
        $situation_images_directory = 'images/situations/';
        $situation_images = glob($situation_images_directory.'*.{jpg,jpeg,gif,png}', GLOB_BRACE);
        $random_situation = $situation_images[array_rand($situation_images)];
        ?>

        <!-- Temporary storage of image so it can be picked up by JS -->
        <img id="situation_image" style="display:none" src="<?php echo $random_situation;?>">


        <canvas id="canvas">
          Not supported
        </canvas>
      </div>

    </div> <!-- End of wrapper -->

    <?php include "includes/footer.php"; ?>

  </div>


  <!-- The form to submit data back to the database -->
  <form id='submit_form' action='submit.php' method='post'>
    <input type='hidden' name='group_name' value='<?php echo $group_name; ?>'>
    <input type='hidden' name='image' id='image_input' placeholder="Image">
  </form>

  <form id='return_form' action='show_results.php' method='get'>
    <input type='hidden' name='group_name' value='<?php echo $group_name; ?>'>
  </form>

</body>
</html>
