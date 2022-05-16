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
$currperks = $_POST["currperks"];
$maxperks = $_POST["maxperks"];
$playericonindex = $_POST["playericonindex"];
$highestkillcount = $_POST["highestkillcount"];
$sensitivity = $_POST["sensitivity"];
$musicvolume = $_POST["musicvolume"];

// checking if username exists in database
// SELECT username from players WHERE username = 'priyansh';
$namecheckSqlQuery = "SELECT username from project WHERE username = '" . $username . "';";
$nameCheck = mysqli_query($connection, $namecheckSqlQuery) or die("2\tNamecheck Query Failed."); // error code : #1 = Namecheck query failed

if (mysqli_num_rows($nameCheck) != 1) {
    echo "5\tUser does not exist\tlogin.php(Line : 23)";
    exit();
}

// update score in database
$updateQuery = "UPDATE project SET currperks = " . $currperks . ","
    .   "maxperks = " . $maxperks . ", "
    .   "playericonindex = " . $playericonindex . ", "
    .   "highestkillcount = " . $highestkillcount . ", "
    .   "sensitivity = '" . $sensitivity . "', "
    .   "musicvolume = '" . $musicvolume . "'"
    .   " WHERE username = '" . $username . "';";
$result = mysqli_query($connection, $updateQuery);

echo "0";
