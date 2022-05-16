<?php
// Creating a connection to database.
$connection = mysqli_connect('localhost', 'root', 'root', 'mazoandom_data');

// Checking if connection was made
// false if 0 errors else true
if (mysqli_connect_errno()) {
    echo "1\tConnection Failed"; // error code : #1 = connection failed
    exit();
}

// getting the username and password from the form that we posted
// from unity register menu wwwForm.
$username = $_POST["username"];
$password = $_POST["password"];

// checking if username exists in database
// SELECT username from players WHERE username = 'priyansh';
$namecheckSqlQuery = "SELECT username from project WHERE username = '" . $username . "';";
$nameCheck = mysqli_query($connection, $namecheckSqlQuery) or die("2\tNamecheck Query Failed."); // error code : #1 = Namecheck query failed

if (mysqli_num_rows($nameCheck) != 1) {
    echo "5\tUser does not exist\tlogin.php(Line : 23)";
    exit();
}

// checking if the password of username matches the entered password
$q = mysqli_query($connection, "SELECT * FROM project WHERE username = '" . $username . "';");

$existingLoginInfo = mysqli_fetch_assoc($q);

$salt = $existingLoginInfo['salt'];
$hash = $existingLoginInfo['hash'];

$loginHash = crypt($password, $salt);

if ($hash != $loginHash) {
    echo  "7\tPassword Incorrect\tlogin.php(Line : 37)";
    exit();
}

$currperks = $existingLoginInfo["currperks"];
$maxperks = $existingLoginInfo["maxperks"];
$playericonindex = $existingLoginInfo["playericonindex"];
$highestkillcount = $existingLoginInfo["highestkillcount"];
$sensitivity = $existingLoginInfo["sensitivity"];
$musicvolume = $existingLoginInfo["musicvolume"];

echo "0\t" . $currperks . "\t" . $maxperks . "\t" . $playericonindex . "\t" . $highestkillcount . "\t" . $sensitivity . "\t" . $musicvolume;
