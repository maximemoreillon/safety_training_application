<html lang="en">
<head>
  <?php include "includes/head.php"; ?>
</head>
<body>

  <div class="wrapper">

    <?php include "includes/header.php"; ?>

    <div class="main">
      <h1>
        Group creation
      </h1>
      <form action="add_group.php" method="get">

        <input type="text" name="group_name" placeholder="Group name"><br>

        <?php
        $member_count = $_REQUEST['member_count'];
        for ($i = 1; $i <= $member_count; $i++) {
          echo "<input type='text' name='member_names[]' placeholder='Member name ".$i."'><br>";
        }
        ?>

        <br>
        <input type="submit" value="Create">
      </form>

      <form action="manage_groups.php" method="get">
        <input type="submit" value="Return">
      </form>
    </div>





    <?php include "includes/footer.php"; ?>

  </div>
</body>
</html>
