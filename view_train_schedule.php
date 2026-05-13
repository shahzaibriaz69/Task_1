<?php
include('config.php');


$train_id = $_GET['id'];


$schedule = getRow($con, "SELECT * FROM train_schedule WHERE id = '$train_id'");



$customers = getAll($con, "SELECT id, name FROM projectstarter_users WHERE role = 'customer'");


if (isset($_POST['add_passenger'])) {
    $u_id = $_POST['user_id'];
    runQuery("INSERT INTO smsCampaigner_train_passengers (train_id, user_id) VALUES ('$train_id', '$u_id')");
    header("Location: view_train_schedule.php?id=$train_id");
    exit();
}
?>

<style>
    /* Theme Colors */
    :root {
        --navy: #001f3f;
        --gold: #FFD700;
        --bg: #f0f2f5;
    }

    body {
        background-color: var(--bg);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Portlet / Card Header */
    .card-header,
    .bg-primary,
    .bg-info {
        background-color: var(--navy) !important;
        color: var(--gold) !important;
        border-bottom: 2px solid var(--gold);
    }

    /* Buttons */
    .btn-primary,
    .btn-success {
        background-color: var(--gold) !important;
        border: none !important;
        color: var(--navy) !important;
        font-weight: bold;
    }

    /* Table Header */
    .table thead {
        background-color: var(--navy);
        color: var(--gold);
    }

    /* Card Styling */
    .card {
        border-radius: 12px;
        border: 1px solid var(--navy);
        overflow: hidden;
    }
</style>

<!DOCTYPE html>
<html>

<head>
    <title>View Schedule</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
</head>

<body class="bg-light">
    <div class="container py-5">

        <div class="card shadow mb-4">
            <div class="card-header">
                <h4>Train Details</h4>
            </div>
            <div class="card-body">
                <p><strong>Route:</strong> <?= $schedule['starting_station']; ?> to <?= $schedule['destination']; ?></p>
                <p><strong>Date:</strong> <?= $schedule['date']; ?></p>
                <p><strong>Time:</strong> <?= $schedule['start_time']; ?> - <?= $schedule['end_time']; ?></p>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Passenger List</h5>
                <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addPassengerModal">Add
                    Passenger</button>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // JOIN query to get passenger details
                        $passengers = getAll($con, "SELECT u.name, u.email, u.phone, u.address 
                                              FROM smsCampaigner_train_passengers p 
                                              JOIN projectStarter_users u ON p.user_id = u.id 
                                              WHERE p.train_id = '$train_id'");
                        foreach ($passengers as $p): ?>
                            <tr>
                                <td><?= $p['name']; ?></td>
                                <td><?= $p['email']; ?></td>
                                <td><?= $p['phone']; ?></td>
                                <td><?= $p['address']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addPassengerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5>AddNew Passenger</h5>
                    </div>
                    <div class="modal-body">
                        <label>Select Customer</label>
                        <select name="user_id" class="form-control" required>
                            <?php foreach ($customers as $c): ?>
                                <option value="<?= $c['id']; ?>"><?= $c['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" name="add_passenger" class="btn btn-primary">Add Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>