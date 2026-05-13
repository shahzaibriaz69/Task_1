<?php

include('config.php');

// --- DELETE LOGIC START ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];


    runQuery("DELETE FROM smsCampaigner_train_passengers WHERE train_id = '$id'");


    runQuery("DELETE FROM train_schedule WHERE id = '$id'");


    header("Location: train_schedule.php?msg=deleted");
    exit();
}



$drivers = getAll($con, "SELECT id, name FROM projectStarter_users WHERE role = 'employee'");

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

<style>
    /* Pure page ka background */
    body {
        background-color: #f0f2f5;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Main Cards ka style */
    .card {
        border: none;
        border-radius: 15px;
        overflow: hidden;
    }

    /* Header ka color (Navy Blue) */
    .bg-primary,
    .bg-dark,
    .card-header {
        background-color: #001f3f !important;

        color: #FFD700 !important;

        border-bottom: 2px solid #FFD700;
    }


    .btn-success {
        background-color: #FFD700 !important;
        border: none;
        color: #001f3f !important;
        font-weight: bold;
        transition: 0.3s;
    }

    .btn-success:hover {
        background-color: #e6c200 !important;
        transform: translateY(-2px);
    }

    .btn-view {
        background-color: #001f3f !important;
        border: none;
        color: #FFD700 !important;
        font-weight: bold;
        transition: 0.3s;
    }


    .form-control:focus {
        border-color: #FFD700;
        box-shadow: 0 0 5px rgba(255, 215, 0, 0.5);
    }

    .table thead {
        background-color: #001f3f;
        color: #FFD700;
    }
</style>

<!DOCTYPE html>
<html>

<head>
    <title>Train Schedule CRUD</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header text-white">
                <h3>Create Train Schedule</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>Start Time</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label>End Time</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Starting Station</label>
                            <input type="text" name="starting_station" class="form-control" placeholder="e.g. Karachi"
                                required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Destination</label>
                            <input type="text" name="destination" class="form-control" placeholder="e.g. Islamabad"
                                required>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label>Select Driver (Task 2)</label>
                            <select name="driver_id" class="form-control" required>
                                <option value="">-- Select Employee --</option>
                                <?php foreach ($drivers as $driver): ?>
                                    <option value="<?= $driver['id']; ?>">
                                        <?= $driver['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="submit_schedule" class="btn btn-success">Save Schedule</button>
                </form>
            </div>
        </div>

        <div class="card my-5 shadow">
            <div class="card-header">
                <h3>All Train Schedules</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Route</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $all_schedules = getAll($con, "SELECT * FROM train_schedule");
                        foreach ($all_schedules as $row): ?>
                            <tr>
                                <td>
                                    <?= $row['date']; ?>
                                </td>
                                <td>
                                    <?= $row['starting_station'] . " to " . $row['destination']; ?>
                                </td>
                                <td>
                                    <a href="view_train_schedule.php?id=<?= $row['id']; ?>"
                                        class="btn btn-view btn-sm">View</a>

                                    <a href="train_schedule.php?delete_id=<?= $row['id']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</body>

</html>