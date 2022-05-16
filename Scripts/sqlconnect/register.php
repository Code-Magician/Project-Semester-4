<?php
// Creating a connection to database.
$connection = mysqli_connect('localhost', 'root', 'root', 'mazoandom_data');

// Checking if connection was made
// false if 0 errors else true
if (mysqli_connect_errno()) {
    echo "1\tConnection Failed\tregister.php(Line : 8)"; // error code : #1 = connection failed
    exit();
}

// getting the username and password from the form that we posted
// from unity register menu wwwForm.
$username = $_POST["username"];
$password = $_POST["password"];
$currperks = $_POST["currperks"];
$maxperks = $_POST["maxperks"];
$playericonindex = $_POST["playericonindex"];
$highestkillcount = $_POST["highestkillcount"];
$sensitivity = $_POST["sensitivity"];
$musicvolume = $_POST["musicvolume"];

// checking if username exists in database
// SELECT username from players WHERE username = 'priyansh';
$namecheckSqlQuery = "SELECT username from project WHERE username = '" . $username . "';";
$nameCheck = mysqli_query($connection, $namecheckSqlQuery) or die("2\tNamecheck Query Failed\tregister.php(Line : 20)"); // error code : #1 = Namecheck query failed

if (mysqli_num_rows($nameCheck) > 0) {
    echo "3\tUsername Already Exits. Cannot Register\tregister.php(Line : 23)";
    exit();
}


// // Add users to tables

// // password encription into hash and salt
$salt = "\$5\$rounds=5000\$" . "steamedhams" . $username . "\$";
$hash = crypt($password, $salt);

// inserting to table
$insertUserQuery = "INSERT INTO project (username, hash, salt, currperks, maxperks, playericonindex, highestkillcount, sensitivity, musicvolume) 
VALUES('" . $username . "', '" . $hash . "', '" . $salt . "', " . $currperks . ", " . $maxperks . ", " . $playericonindex . ", " . $highestkillcount . ", '" . $sensitivity . "', '" . $musicvolume . "');";
mysqli_query($connection, $insertUserQuery) or die("4\tUnable to create user in Database\tregister.php(Line : 36)");



echo ("0");
