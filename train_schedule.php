<?php
// Include your config/db file here (where $con and helper functions are defined)
include('config.php'); 

// Task 2: Drivers fetch karna
$drivers = getAll($con, "SELECT id, name FROM projectStarter_users WHERE role = 'employee'");

// Task 1: Form Submit hone par data save karna
if (isset($_POST['submit_schedule'])) {
    $date = $_POST['date'];
    $s_time = $_POST['start_time'];
    $e_time = $_POST['end_time'];
    $s_station = $_POST['starting_station'];
    $dest = $_POST['destination'];
    $d_id = $_POST['driver_id'];

    $query = "INSERT INTO train_schedule (date, start_time, end_time, starting_station, destination, driver_id) 
              VALUES ('$date', '$s_time', '$e_time', '$s_station', '$dest', '$d_id')";

    runQuery($query);
    header("Location: train_schedule.php?msg=success");
}
?>