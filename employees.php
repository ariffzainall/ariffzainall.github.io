<!DOCTYPE html>
<html lang="en">

<head>
	<?php
        include './include/config.php';
        include './include/auth-checker.php';
        include './include/header.php';

		$staffArray = fetchRows("SELECT * FROM admin");
    ?>

	<style>
		main{
			background: linear-gradient(rgb(0 0 0 / 45%), rgb(54 54 54 / 45%)), url('images/background.jpg');
			background-repeat: no-repeat;
			background-size: cover;
		}

		.glasses{
			background: rgba(255,255,255,0.2) !important;
			-webkit-backdrop-filter: blur(2px) !important;
			backdrop-filter: blur(2px) !important;
		}
	</style>
</head>

<body>
	<main class="d-flex w-100">
		<div class="container-fluid d-flex flex-column p-0">
			<!-- Header -->
			<?php
				if(isset($_SESSION['student'])){
					include './include/navbar-student.php';
				}

				if(isset($_SESSION['admin'])){
					include './include/navbar-admin.php';
				}
			?>

			<!-- Content -->
			<div class="container p-4 d-flex align-items-center flex-column gap-5" style="min-height: 100vh">

				<!-- Page Title -->
				<div class="row mt-5">
					<div class="col-auto d-none d-sm-block">
						<h3 class="text-white"><strong>Staff</strong> Management</h3>
					</div>
				</div>
				
                <!-- Content -->
				<div class="row">
					<div class="col-12 col-lg-12 col-xxl-12">
						<div class="card">

							<!-- Card Content -->
							<div class="card-body">
								<a href="./employees-create" class="btn btn-primary mb-4">Register Staff</a>
								<a href="#" class="btn btn-primary mb-4" onClick="window.print()">Print</a>

								<table class="table table-bordered my-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Email</th>
											<th>Phone Number</th>
											<th>Password</th>
											<th>Action</th>
										</tr>
									</thead>
									<tbody>
										<?php
											if(!empty($staffArray)){
												foreach($staffArray as $key => $value){

													$actionButton = '
														<a href="employees-delete?id='.$value['userid'].'" class="btn btn-danger">Delete</a>';

													if($value['userid'] == $_SESSION['admin']->userid){
														$actionButton = 'Your Account';
													}

													echo '
														<tr>
															<td>'.$value['name'].'</td>
															<td>'.$value['email'].'</td>
															<td>'.$value['phone'].'</td>
															<td class="password-hidden">'.$value['password'].'</td>
															<td>'.$actionButton.'</td>
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

	<script>
		const passwordEl = document.querySelectorAll('.password-hidden');

		[...passwordEl].forEach(function(e, i){
			const original = e.innerText;
			const randomID = `passwordselector${ i }`;

			e.innerHTML = `
				<div class="d-flex gap-3 align-items-center">
					<span class="flex-1" id="${ randomID }" data-hide="true">********</span>
					<a href="#" onclick="showpass('${ original }', '${ randomID }')">
						<i class="align-middle" data-feather="eye"></i>
					</a>
				</div>
			`;
		});

		function showpass(pass, selector){
			const areaEl = document.querySelector(`#${ selector }`);
			const showFlag = areaEl.getAttribute('data-hide');

			if(showFlag == 'true'){
				areaEl.innerHTML = pass;
				areaEl.setAttribute('data-hide', 'false');
			}

			if(showFlag == 'false'){
				areaEl.innerHTML = '********';
				areaEl.setAttribute('data-hide', 'true');
			}
		}
    </script>

	<?php
		if(isset($_GET['delete'])){
			ToastMessage('Success', 'Admin deleted!', 'success', 'employees');
		}
	?>
</body>
</html>