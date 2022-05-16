<?php
$conn = mysqli_connect("localhost", "root", "root", "mazoandom_data");
if (isset($_POST["ENTER"])) {
  $name = $_POST["t1"];
  $userid = $_POST["t4"];
  $pass = $_POST["t5"];

  $salt = "\$5\$round=5000\$" . "steamedhams" . $name . "\$";
  $hash = crypt($pass, $salt);

  $conn = mysqli_connect("localhost", "root", "root", "mazoandom_data");
  $sql = "insert into project values('$userid','$name','$hash','$salt',0,100,0,0,0,0)";
  if (mysqli_query($conn, $sql))
    echo '<script>alert("DATA ENTERED SUCCESSFULLY") </script>';
  else {
    echo '<script>alert("DATA NOT ENTERED SUCCESSFULLY") </script>';
    echo mysqli_error($conn);
  }
}
?>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>MAZE GENERATOR | SIGNUP </title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="center">
    <h1>USER SIGNUP</h1>
    <form action="#" method="POST">
      <div class="txt_field">
        <input type="text" name="t1" required>
        <span></span>
        <label>USERNAME</label>
      </div>
      <div class="txt_field">
        <input type="text" name="t4" required>
        <span></span>
        <label>USER ID</label>
      </div>
      <div class="txt_field">
        <input type="password" name="t5" required>
        <span></span>
        <label>PASSWORD</label>
      </div>
      <input type="submit" value="SIGN UP" name="ENTER">
      <div class="signup_link">
        Already a member? <a href="index.php">LOG IN</a>
      </div>
    </form>
  </div>

</body>

</html>