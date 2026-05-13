<?php
include('config.php');

$drivers = getAll($con, "SELECT id, name FROM projectstarter_users WHERE role = 'employee'");

$edit_mode = false;
$edit_data = [];

if (isset($_GET['edit_id'])) {
    $edit_mode = true;
    $e_id = $_GET['edit_id'];
    $edit_data = getRow($con, "SELECT * FROM train_schedule WHERE id = '$e_id'");
}


if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    runQuery("DELETE FROM smsCampaigner_train_passengers WHERE train_id = '$id'");
    runQuery("DELETE FROM train_schedule WHERE id = '$id'");
    header("Location: train_schedule.php");
    exit();
}


if (isset($_POST['submit_schedule'])) {
    $date = $_POST['date'];
    $s_time = $_POST['start_time'];
    $e_time = $_POST['end_time'];
    $s_station = $_POST['starting_station'];
    $dest = $_POST['destination'];
    $d_id = $_POST['driver_id'];

    if ($edit_mode) {

        $query = "UPDATE train_schedule SET 
                  date='$date', start_time='$s_time', end_time='$e_time', 
                  starting_station='$s_station', destination='$dest', driver_id='$d_id' 
                  WHERE id = '$e_id'";
    } else {

        $query = "INSERT INTO train_schedule (date, start_time, end_time, starting_station, destination, driver_id) 
                  VALUES ('$date', '$s_time', '$e_time', '$s_station', '$dest', '$d_id')";
    }

    runQuery($query);
    header("Location: train_schedule.php?msg=success");
    exit();
}
?>

<style>
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

    .card-header {
        background-color: #001f3f !important;
        color: #FFD700 !important;
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
            <div class="card-header">
                <h3>Create Train Schedule</h3>
            </div>
            <div class="card-body">
              <div class="card-header" style="background-color: #001f3f; color: #FFD700; font-weight: bold; padding: 15px;">
    <?= $edit_mode ? 'Update Train Schedule' : 'Create New Train Schedule'; ?>
</div>

<div class="card-body" style="background: white; padding: 25px;">
    <form method="POST">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label style="font-weight: 600;">Date</label>
                <input type="date" name="date" class="form-control" 
                       value="<?= $edit_mode ? $edit_data['date'] : ''; ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label style="font-weight: 600;">Start Time</label>
                <input type="time" name="start_time" class="form-control" 
                       value="<?= $edit_mode ? $edit_data['start_time'] : ''; ?>" required>
            </div>

            <div class="col-md-4 mb-3">
                <label style="font-weight: 600;">End Time</label>
                <input type="time" name="end_time" class="form-control" 
                       value="<?= $edit_mode ? $edit_data['end_time'] : ''; ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label style="font-weight: 600;">Starting Station</label>
                <input type="text" name="starting_station" class="form-control" placeholder="e.g. Karachi"
                       value="<?= $edit_mode ? $edit_data['starting_station'] : ''; ?>" required>
            </div>

            <div class="col-md-6 mb-3">
                <label style="font-weight: 600;">Destination</label>
                <input type="text" name="destination" class="form-control" placeholder="e.g. Islamabad"
                       value="<?= $edit_mode ? $edit_data['destination'] : ''; ?>" required>
            </div>

            <div class="col-md-12 mb-3">
                <label style="font-weight: 600;">Select Driver (Task 2)</label>
                <select name="driver_id" class="form-control" required>
    <option value="">-- Select Employee --</option>
    <?php if(!empty($drivers)): ?>
        <?php foreach ($drivers as $driver): ?>
            <option value="<?= $driver['id']; ?>" 
                <?= ($edit_mode && $edit_data['driver_id'] == $driver['id']) ? 'selected' : ''; ?>>
                <?= $driver['name']; ?>
            </option>
        <?php endforeach; ?>
    <?php else: ?>
        <option value="">No Employees Found</option>
    <?php endif; ?>
</select>
            </div>
        </div>

        <div class="mt-3">
            <button type="submit" name="submit_schedule" class="btn" 
                    style="background-color: #FFD700; color: #001f3f; font-weight: bold; border: none; padding: 10px 25px;">
                <?= $edit_mode ? 'Update Schedule' : 'Save Schedule'; ?>
            </button>

            <?php if($edit_mode): ?>
                <a href="train_schedule.php" class="btn btn-secondary" style="margin-left: 10px;">Cancel</a>
            <?php endif; ?>
        </div>
    </form>
</div>
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

                                    <a href="train_schedule.php?edit_id=<?= $row['id']; ?>" class="btn btn-warning btn-sm"
                                        style="background: #ffc107; color: #000;">Edit</a>

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