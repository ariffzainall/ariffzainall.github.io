<?php
include './include/config.php';
include './include/auth-checker.php';
include './include/header.php';

$staffArray = fetchRows("SELECT * FROM student");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        main {
            background: linear-gradient(rgb(0 0 0 / 45%), rgb(54 54 54 / 45%)), url('images/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
        }

        .glasses {
            background: rgba(255, 255, 255, 0.2) !important;
            -webkit-backdrop-filter: blur(2px) !important;
            backdrop-filter: blur(2px) !important;
        }

        .scrollable-card {
            max-height: 400px; /* Adjust the height as needed */
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <main class="d-flex w-100">
        <div class="container-fluid d-flex flex-column p-0">
            <!-- Header -->
            <?php
            if (isset($_SESSION['student'])) {
                include './include/navbar-student.php';
            }

            if (isset($_SESSION['admin'])) {
                include './include/navbar-admin.php';
            }
            ?>

            <!-- Content -->
            <div class="container p-4 d-flex align-items-center flex-column gap-5" style="min-height: 100vh">

                <!-- Page Title -->
                <div class="row mt-5">
                    <div class="col-auto d-none d-sm-block">
                        <h3 class="text-white"><strong>Registered</strong> Student</h3>
                    </div>
                </div>

                <!-- Content -->
                <div class="row">
                    <div class="col-12 col-lg-12 col-xxl-12">
                        <div class="card scrollable-card">

                            <!-- Card Content -->
                            <div class="card-body">
                                <table class="table table-bordered my-0">
                                    <thead>
                                        <tr>
                                            <th>Student Number</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Phone Number</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (!empty($staffArray)) {
                                            foreach ($staffArray as $key => $value) {

                                                $actionButtons = '
                                                    <a href="student-edit.php?id=' . $value['matricno'] . '" class="btn btn-primary">Edit</a>
                                                    <a href="student-delete?id=' . $value['matricno'] . '" class="btn btn-danger">Delete</a>';

                                                echo '
                                                    <tr>
                                                        <td>' . $value['matricno'] . '</td>
                                                        <td>' . $value['name'] . '</td>
                                                        <td>' . $value['email'] . '</td>
                                                        <td>' . $value['phonenumber'] . '</td>
                                                        <td>' . $actionButtons . '</td>
                                                    </tr>
                                                ';
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <?php
    if (isset($_GET['delete'])) {
        ToastMessage('Success', 'Student deleted!', 'success', 'index.php');
    }
    ?>
</body>
</html>
