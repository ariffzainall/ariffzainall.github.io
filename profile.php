<!DOCTYPE html>
<html lang="en">

<head>
	<?php
        include './include/config.php';
        include './include/auth-checker.php';
        include './include/header.php';
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
				$id = NULL;
				$profileSQL = NULL;
				$submitname = '';

				if(isset($_SESSION['student'])){
					include './include/navbar-student.php';
					
					$id = $_SESSION['student']->matricno;
					$submitname = 'student_profile';
					$profileSQL = "SELECT * FROM `student` WHERE `matricno` = '".$id."'";
				}

				if(isset($_SESSION['admin'])){
					include './include/navbar-admin.php';
					
					$id = $_SESSION['admin']->userid;
					$submitname = 'admin_profile';
					$profileSQL = "SELECT * FROM `admin` WHERE `userid` = '".$id."'";
				}

				$profile = fetchRow($profileSQL);

				if($profile){
					$profile = json_decode(json_encode($profile), false);
				}
			?>

			<!-- Content -->
			<div class="container p-4 d-flex align-items-center flex-column gap-5" style="min-height: 100vh">

				<!-- Page Title -->
				<div class="row mt-5">
					<div class="col-auto d-none d-sm-block">
						<h3 class="text-white"><strong>Manage</strong> Profile</h3>
					</div>
				</div>
				
                <!-- Content -->
				<div class="w-50">
                    <form method="POST" class="card" enctype="multipart/form-data">

						<!-- Card Content -->
                        <div class="card-body">
							
							<?php if(!empty($profile->profile_picture)){ ?>
								<div class="row mb-4">
									<span class="bg-light d-flex w-100 justify-content-center py-3">
										<img src="./images/photos/<?php echo ($profile->profile_picture ?? ''); ?>" class="img-thumbnail rounded-circle" width="128" height="128">
									</span>
								</div>
							<?php } ?>

							<div class="row mb-4">
								<label class="col-md-3 form-label"> Profile Picture</label>

								<div class="col-md-9">
									<input type="file" class="form-control" name="profile_picture">
									<input type="hidden" name="profile_picture_bk" value="<?php echo ($profile->profile_picture ?? ''); ?>"/>
								</div>
							</div>
							
							<div class="row mb-4 mt-4">
								<label class="col-md-3 form-label"> Name</label>
								<div class="col-md-9">
									<input type="text" class="form-control" placeholder="Name" name="name" value="<?php echo ($profile->name ?? ''); ?>">
								</div>
							</div>

							<div class="row mb-4">
								<label class="col-md-3 form-label">Phone</label>
								<div class="col-md-9">
									<input type="tel" class="form-control" placeholder="Phone" name="phone" value="<?php echo ($profile->phonenumber ?? $profile->phone ?? ''); ?>">
								</div>
							</div>

							<div class="row mb-4">
								<label class="col-md-3 form-label">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control" placeholder="Email" name="email" value="<?php echo ($profile->email ?? ''); ?>">
								</div>
							</div>

							<div class="row mb-4">
								<label class="col-md-3 form-label">Password</label>
								<div class="col-md-9">
									<input type="text" class="form-control" placeholder="Password" name="password" value="<?php echo ($profile->password ?? ''); ?>">
								</div>
							</div>
						</div>

						<!-- Card Footer -->
						<div class="card-footer border">
							<div class="row">
								<div class="col-md-3"></div>
								<div class="col-md-9">
									<button type="submit" name="<?php echo $submitname; ?>" class="btn btn-primary">Update Profile</button>
								</div>
							</div>
						</div>

                    </form>
				</div>
			</div>

		</div>
	</main>

<?php
	function saveProfile($role, $profileid){
        $password = ($_POST['password']);
        $name     = ($_POST['name']);
        $phone    = ($_POST['phone']);
        $email    = ($_POST['email']);
		$imagename = null;

		if(isset($_FILES["profile_picture"]) && !empty($_FILES["profile_picture"]["size"])){
            $target_dir = "./images/photos/";
            $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

            $check = getimagesize($_FILES["profile_picture"]["tmp_name"]);

            if(!$check) {
                echo "File is not an image."; exit;
            }

            if(!move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)){
				echo "Sorry, there was an error uploading your file.";
            }

            $imagename = ($_FILES["profile_picture"]["name"]);
        }else{
			$imagename = ($_POST["profile_picture_bk"]);
		}

		if($role == 'student'){
			runQuery("UPDATE `student` SET `profile_picture`='$imagename' WHERE `matricno`='$profileid'");
			runQuery("UPDATE `student` SET `password`='$password',`name`='$name',`phonenumber`='$phone',`email`='$email' WHERE `matricno`='$profileid'");
		}

		if($role == 'admin'){
			runQuery("UPDATE `admin` SET `profile_picture`='$imagename' WHERE `userid`='$profileid'");
			runQuery("UPDATE `admin` SET `password`='$password',`name`='$name',`phone`='$phone',`email`='$email' WHERE `userid`='$profileid'");
		}

        ToastMessage('Successfully', 'Profile Updated', 'success', 'profile');
	}

    if(isset($_POST['student_profile'])){
        saveProfile('student', $id);
    }

	if(isset($_POST['admin_profile'])){
        saveProfile('admin', $id);
    }
?>
</body>
</html>