<!DOCTYPE html>
<html lang="en">

<head>
	<?php
        include './include/config.php';
        include './include/auth-checker.php';
        include './include/header.php';

        $profiledata = (object)[];
        $mode = (isset($_GET['id']) ? 'update' : 'create');

        if($mode == 'update'){
            $profiledata = fetchRow("SELECT * FROM `admin` WHERE userid = ".$_GET['id']);
            $profiledata = json_decode(json_encode($profiledata),false);
        }
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
				include './include/navbar-admin.php';
			?>

			<!-- Content -->
			<div class="container p-4 d-flex align-items-center flex-column gap-5" style="min-height: 100vh">

				<!-- Page Title -->
				<div class="row mt-5">
					<div class="col-auto d-none d-sm-block">
						<h3 class="text-white"><strong>Register</strong> Staff</h3>
					</div>
				</div>
				
                <!-- Content -->
				<div class="w-50">
                    <form method="POST" class="card">

						<!-- Card Content -->
                        <div class="card-body">
                            <div class="row mb-4 mt-4">
                                <label class="col-md-3 form-label">Name :</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control" placeholder="Name" name="name" value="<?php echo $profiledata->name ?? ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Phone :</label>
                                <div class="col-md-9">
                                    <input type="tel" class="form-control" placeholder="Phone" name="phone" value="<?php echo $profiledata->phone ?? ''; ?>">
                                </div>
                            </div>

                            <div class="row mb-4">
                                <label class="col-md-3 form-label">Email :</label>
                                <div class="col-md-9">
                                    <input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo $profiledata->email ?? ''; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-md-3 form-label">Password :</label>
                                <div class="col-md-9">
                                    <div class="input-group mb-3">
                                        <input id="password-toggle" data-hide="false" type="password" class="form-control" placeholder="Password" name="password" value="<?php echo $profiledata->password ?? ''; ?>">

                                        <a href="#" class="input-group-text" onclick="showpass()">
                                            <i class="align-middle" data-feather="eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
						</div>

						<!-- Card Footer -->
						<div class="card-footer border">
							<div class="row">
								<div class="col-md-2"></div>
								<div class="col-md-10">
									<button type="submit" name="addadmin" class="btn btn-success">
                                        <?php echo ($mode == 'update' ? 'Save Changes' : 'Add Admin');?>
                                    </button>
								</div>
							</div>
						</div>

                    </form>
				</div>
			</div>

		</div>

        <script>
            function showpass(){
                let areaEl = document.querySelector(`#password-toggle`);
                let showFlag = areaEl.getAttribute('data-hide');

                if(showFlag == 'true'){
                    areaEl.setAttribute('data-hide', 'false');
                    areaEl.setAttribute('type', 'password');
                }

                if(showFlag == 'false'){
                    areaEl.setAttribute('data-hide', 'true');
                    areaEl.setAttribute('type', 'text');
                }
            }
        </script>
	</main>

    <?php
        if(isset($_POST['addadmin'])) {
            $userid = rand(99999, 999999);
            $email = $_POST['email'];
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
        
            $checkExist = fetchRow("SELECT * FROM admin WHERE email = '$email'");
    
            if($mode == 'update'){
                $checkExist = null;
            }
        
            if(!empty($checkExist)){
                ToastMessage('Email already used', 'User Has Already Been Registered!', 'error', 'employees');
            } else{
                if($mode == 'create'){
                    runQuery("INSERT INTO `admin`(`userid`,`password`, `name`, `phone`, `email`) VALUES ('$userid','$password','$name','$phone','$email')");
                }
    
                if($mode == 'update'){
                    runQuery("UPDATE `admin` SET `password`='$password',`phone`='$phone',`name`='$name',`email`='$email' WHERE userid =".$_GET['id']);
                }
    
                ToastMessage('Success', 'Record saved!', 'success', 'employees');
            }
        }
    ?>
</body>
</html>