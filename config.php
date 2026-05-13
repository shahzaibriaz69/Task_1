<?php

$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'projectStarter';

$con = mysqli_connect($host, $user, $password, $dbname);

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}


function getAll($con, $query)
{
    $result = mysqli_query($con, $query);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    } else {
        return [];
    }
}

function getRow($con, $query)
{
    $result = mysqli_query($con, $query);
    if ($result) {
        return mysqli_fetch_assoc($result);
    } else {
        return [];
    }
}

function deleteRow($con, $table, $id)
{
    $query = "DELETE FROM $table WHERE id = '$id'";
    return mysqli_query($con, $query);
}

function runQuery($query)
{
    global $con;
    return mysqli_query($con, $query);
}


?>