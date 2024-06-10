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
				<div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
					<div class="d-table-cell align-middle">

						<div class="card">
							<div class="card-body">
								<div class="m-sm-4 pt-3">

									<!-- Logo -->
									<div class="text-center pb-3">
										<a href="./home.php">
											<img src="images/air_guardian.png?1" width="250" style="box-shadow: 1px 1px 16px 12px #fff;" />
										</a>
									</div>

									<form method="POST" class="mt-4">
										<!-- Head -->
										<div class="mb-3">
											<div class="d-flex w-100 align-items-center">
												<label id="switcher-label" class="form-label text-white fw-bold" style="flex: 1;">Enter ID</label>
												<a type="button" href="javascript:void(0)" id="switch-admin" class="text-white text-decoration-underline">Switch to admin login</a>
											</div>
											<input class="form-control form-control-lg" type="text" name="metric" placeholder="Enter ID"/>
										</div>

										<!-- Password Input -->
										<div class="mb-3">
											<label class="form-label text-white fw-bold">Password</label>
											<input class="form-control form-control-lg" type="password" name="password" placeholder="Enter password" />
										</div>

										<!-- Submit Button -->
										<div class="text-center mt-3">
											<button id="submit-button" type="submit" name="login_student" class="btn btn-lg btn-success w-100">Login as User</button>

											<div class="text-center pt-3">
                                                <a href="./sign-up" class="text-dark mb-0 text-white">Register your new account</a><br>
                                                <a href="./sign-in-forgot" class="text-dark mb-0 text-white">Forgot Password</a>
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

	<script>
		const formSubmit = document.querySelector('#submit-button');
		const adminSwitcher = document.querySelector('#switch-admin');
		const switcherLabel = document.querySelector('#switcher-label');

		adminSwitcher.addEventListener("click", function(){
			const currentIsAdmin = (this.innerText == 'Switch to admin login');

			if(currentIsAdmin){
				adminSwitcher.innerText = 'Switch to student login';
				switcherLabel.innerText = 'Admin ID';
				formSubmit.innerText = 'Login as Admin';
				formSubmit.setAttribute('name', 'login_admin');
			}else{
				adminSwitcher.innerText = 'Switch to admin login';
				switcherLabel.innerText = 'Student ID';
				formSubmit.innerText = 'Login as Student';
				formSubmit.setAttribute('name', 'login_student');
				
			}
		});
	</script>
</body>

<?php
	function doLogin($role){
		$successMessage = '';
		$metric = $_POST['metric'];
		$password = $_POST['password'];

		if($role == 'student'){
			$successMessage = 'Successfull login as student';
			$checkCredential = fetchRow("SELECT * FROM `student` WHERE `password` = '$password' AND `matricno` = '$metric'");
		}

		if($role == 'admin'){
			$successMessage = 'Successfull login as admin';
			$checkCredential = fetchRow("SELECT * FROM `admin` WHERE `password` = '$password' AND `userid` = '$metric'");
		}

		if(!empty($checkCredential)){
			unset($_SESSION['student']);
			unset($_SESSION['admin']);

			if($role == 'student'){
				$_SESSION['student'] = (object) $checkCredential;
			}

			if($role == 'admin'){
				$_SESSION['admin'] = (object) $checkCredential;
			}
			
			ToastMessage('Success', $successMessage, 'success', 'pollution');
		}else{
			ToastMessage('Invalid Credential', 'Invalid login credential, please try again', 'error', 'sign-in');
		}
	}

    if(isset($_POST['login_student'])){
        doLogin('student');
    }

	if(isset($_POST['login_admin'])){
        doLogin('admin');
    }
?>
</html>