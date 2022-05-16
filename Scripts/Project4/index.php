<?php
session_start();
$conn = mysqli_connect("localhost", "root", "root", "mazoandom_data");
if (isset($_POST["ENTER"])) {
  $userid = $_POST["t1"];
  $pass = $_POST["t2"];

  $q = mysqli_query($conn, "SELECT * FROM project WHERE username = '" . $userid . "';");

  $existingLoginInfo = mysqli_fetch_assoc($q);

  $salt = $existingLoginInfo['salt'];
  $hash = $existingLoginInfo['hash'];

  $loginHash = crypt($pass, $salt);

  if ($hash == $loginHash) {
    $_SESSION["NAME"] = $row[0];
    header("location:game.html");
  } else {
    echo '<script>alert("USER ID OR PASSWORD NOT FOUND!")</script>';
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
    <h1>USER LOGIN</h1>
    <form action="#" method="POST">
      <div class="txt_field">
        <input type="text" name="t1" required>
        <span></span>
        <label>Username</label>
      </div>
      <div class="txt_field">
        <input type="password" name="t2" required>
        <span></span>
        <label>Password</label>
      </div>
      <input type="submit" value="Login" name="ENTER">
      <div class="signup_link">
        Not a member? <a href="signup.php">SIGNUP</a>
      </div>
    </form>
  </div>

</body>

</html>