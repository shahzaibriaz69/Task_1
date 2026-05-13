<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'projectStarter'; // Adjust if different

$con = mysqli_connect($host, $user, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Helper functions
function getAll($con, $query)
{
    $result = mysqli_query($con, $query);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function runQuery($query)
{
    global $con;
    return mysqli_query($con, $query);
}
?>