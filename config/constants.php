<?php 
//Start Session
// Check if a session is not already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//Create Constants to Store Non Repeating Values
define('SITEURL', 'http://localhost/online-kitchen/'); //Update the home URL of the project if you have changed port number or it's live on server
define('LOCALHOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'online-kitchen');
    
$conn = mysqli_connect(LOCALHOST, DB_USERNAME, DB_PASSWORD) or die(mysqli_error()); //Database Connection
$db_select = mysqli_select_db($conn, DB_NAME) or die(mysqli_error()); //SElecting Database 

?>