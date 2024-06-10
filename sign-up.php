<!DOCTYPE html>
<html lang="en">

<head>
	<?php
                include './include/config.php';
include './include/header.php';
		include './include/auth-redirect.php';
    ?>

	<style>
		main{
			background: linear-gradient(rgb(0 0 0 / 45%), rgb(54 54 54 / 45%)), url('images/background.jpg');
			background-repeat: no-repeat;
			background-size: cover;
		}

		.card,
		.glasses{
			background: rgba(255,255,255,0.2) !important;
			-webkit-backdrop-filter: blur(10px) !important;
			backdrop-filter: blur(5px) !important;
		}
	</style>
</head>

<body>
	<main class="d-flex w-100">
		<div class="container-fluid d-flex flex-column p-0">
			<!-- Header -->
			<?php include './include/navbar-main.php'; ?>

			<!-- Content -->
			<div class="row" style="min-height: 100vh;">
				<div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">                        

						<div class="card">
							<div class="card-body">
                                <div class="text-center text-white mt-4">
                                    <h1 class="h2 text-white">Register New Account</h1>
                                    <p class="lead">Create your own account to continue</p>
                                </div>

								<div class="m-sm-4">
									<form method="POST" class="mt-4">
                                        <div class="mb-3">
											<label class="form-label text-white">Student Number</label>
											<input class="form-control form-control-lg" type="text" name="matricno" placeholder="Enter Student Number"/>
										</div>

										<div class="mb-3">
											<label class="form-label text-white">Full Name</label>
											<input class="form-control form-control-lg" type="text" name="name" placeholder="Enter Full Name"/>
										</div>

                                        <div class="mb-3">
											<label class="form-label text-white">Email</label>
											<input class="form-control form-control-lg" type="text" name="email" placeholder="Enter Email"/>
										</div>

                                        <div class="mb-3">
											<label class="form-label text-white">Phone</label>
											<input class="form-control form-control-lg" type="text" name="phone" placeholder="Enter Phone"/>
										</div>

										<div class="mb-3">
											<label class="form-label text-white">Password</label>
											<input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" />
										</div>

										<div class="text-center mt-3">
											<button type="submit" name="addstudent" class="btn btn-lg btn-success w-100">Register Now</button>

											<div class="text-center pt-3">
                                                <a href="./sign-in" class="text-dark mb-0 text-white">Already have an account? Sign In Now</a>
                                            </div>
										</div>
									</form>

								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

		</div>
	</main>
</body>


<?php
    if(isset($_POST['addstudent'])) {
        $matricno = $_POST['matricno'];
        $name = $_POST['name'];
        $phone = $_POST['phone'];
        $password = $_POST['password'];
        $email = ($_POST['email']);
    
        $checkExist = fetchRow("SELECT * FROM student WHERE matricno = '$matricno'");

        if(!empty($checkExist)){
            ToastMessage('Student already used', 'User Has Already Been Registered!', 'error', 'sign-up');
        } else{
            runQuery("INSERT INTO `student`(`matricno`,`password`, `name`, `phonenumber`, `email`) VALUES ('$matricno','$password',
			'$name','$phone','$email')");

            ToastMessage('Success', 'Registration Successfull, Redirecting you to login page..', 'success', 'sign-in');
        }
    }
?>
</html>