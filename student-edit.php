<?php
include './include/config.php';
include './include/auth-checker.php';
include './include/header.php';

if (isset($_GET['id'])) {
    $studentId = $_GET['id'];
    $student = fetchRow("SELECT * FROM student WHERE matricno = '$studentId'");
}

if (isset($_POST['update'])) {
    $studentId = $_POST['matricno'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phoneNumber = $_POST['phonenumber'];

    $updateQuery = "UPDATE student SET name = '$name', email = '$email', phonenumber = '$phoneNumber' WHERE matricno = '$studentId'";
    if (runQuery($updateQuery)) {
        header("Location: student-list.php?update=success");
        exit();
    }
}

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

        .card {
            width: 100%;
            max-width: 500px;
            margin: auto;
            margin-top: 100px;
            background: rgba(255, 255, 255, 0.8) !important;
            -webkit-backdrop-filter: blur(5px) !important;
            backdrop-filter: blur(5px) !important;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 20px;
        }

        .btn {
            width: 100px;
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
                        <h3 class="text-white"><strong>Edit</strong> Student</h3>
                    </div>
                </div>

                <!-- Content -->
                <div class="row">
                    <div class="col-12 col-lg-12 col-xxl-12">
                        <div class="card glasses">
                            <div class="card-body">
                                <form method="post" action="">
                                    <input type="hidden" name="matricno" value="<?php echo $student['matricno']; ?>">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo $student['name']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $student['email']; ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phonenumber" class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phonenumber" name="phonenumber" value="<?php echo $student['phonenumber']; ?>" required>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="submit" name="update" class="btn btn-primary">Save</button>
                                        <a href="student-list.php" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    if (isset($_GET['update'])) {
        ToastMessage('Success', 'Student details updated!', 'success', 'student-list.php');
    }
    ?>
</body>
</html>
