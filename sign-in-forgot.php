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
								<div class="m-sm-4">

									<form method="POST" class="mt-4">
                                        <div class="mb-5">
                                            <h3 class="text-white w-100 text-center">Forgot Password</h3>
                                        </div>

										<!-- Head -->
										<div class="mb-3">
                                            <label class="form-label text-white fw-bold">Student Number</label>
											<input class="form-control form-control-lg" type="text" name="metric" placeholder="Enter student number"/>
										</div>

										<!-- Password Input -->
										<div class="mb-3">
											<label class="form-label text-white fw-bold">New Password</label>
											<input class="form-control form-control-lg" type="password" name="new_password" placeholder="Enter new password" />
										</div>

                                        <!-- Confirm Password Input -->
										<div class="mb-3">
											<label class="form-label text-white fw-bold">Confirm Password</label>
											<input class="form-control form-control-lg" type="password" name="confirm_password" placeholder="Enter confirm password" />
										</div>

                                        <hr>

										<!-- Submit Button -->
										<div class="text-center">
											<button type="submit" name="forgot_password" class="btn btn-lg btn-warning w-100">Save Password</button>
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
			const currentIsAdmin = (this.innerText == 'Swith to admin login');

			if(currentIsAdmin){
				adminSwitcher.innerText = 'Swith to student login';
				switcherLabel.innerText = 'Admin ID';
				formSubmit.innerText = 'Login as Admin';
				formSubmit.setAttribute('name', 'login_admin');
			}else{
				adminSwitcher.innerText = 'Swith to admin login';
				switcherLabel.innerText = 'Student Number';
				formSubmit.innerText = 'Login as Student';
				formSubmit.setAttribute('name', 'login_student');
				
			}
		});
	</script>
</body>

<?php
    if(isset($_POST['forgot_password'])){
        $metric = $_POST['metric'];
		$password = $_POST['new_password'];

        $checkExist = fetchRow("SELECT * FROM `student` WHERE `matricno` = '$metric'");

        if(!empty($checkExist)){
            runQuery("UPDATE `student` SET `password` = '".$password."' WHERE `student`.`matricno` = '".$metric."'");

            ToastMessage('Success', 'Password has been changed', 'success', 'sign-in');
        }else{
            ToastMessage('Student Number Not Exist', 'Please enter correct student number', 'error', 'sign-in-forgot');
        }
    }
?>
</html>