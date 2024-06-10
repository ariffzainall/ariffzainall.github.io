<!DOCTYPE html>
<html lang="en">

<head>
	<?php
        include './include/header.php';
        include './include/config.php';
        include './include/auth-checker.php';
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

		.switch3{
			position: relative;
			display: inline-block;
			width: 80px;
			height: 37px;
			border-radius: 37px;
			background-color: #f3f4f4;
			cursor: pointer;
			transition: all .3s;
			overflow: hidden;
			box-shadow: 0px 0px 2px rgba(0,0,0, .3);
		}

		.switch3 input{
			display: none;
		}

		.switch3 input:checked + div{
			left: calc(80px - 32px);
			box-shadow: 0px 0px 0px white;
		}

		.switch3 div{
			position: absolute;
			width: 27px;
			height: 27px;
			border-radius: 27px;
			background-color: white;
			top: 5px;
			left: 5px;
			box-shadow: 0px 0px 1px rgb(150,150,150);
			transition: all .3s;
		}

		.switch3 div:before, .switch3 div:after{
			position: absolute;
			content: 'ON';
			width: calc(80px - 40px);
			height: 37px;
			line-height: 37px;
			font-family: 'Varela Round';
			font-size: 14px;
			font-weight: bold;
			top: -5px;
		}

		.switch3 div:before{
			content: 'OFF';
			color: rgb(120,120,120);
			left: 100%;
		}

		.switch3 div:after{
			content: 'ON';
			right: 100%;
			color: white;
		}

		.switch3-checked{
			background-color : #4caf50;
			box-shadow: none;
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
						<h3 class="text-white"><strong>Control</strong> Panel</h3>
					</div>
				</div>
				
                <!-- Content -->
				<div class="row">
					<div class="col-12 col-lg-12 col-xxl-12">
						<div class="card">

							<!-- Card Content -->
							<div class="card-body d-flex align-items-center gap-3 justify-content-center">
								<h4 class="m-0 fw-bold">Sensor Status : </h4>

								<!-- <label class="switch3"><input type="checkbox"/><div></div></label> -->
								<label class="switch3 switch3-checked"><input type="checkbox" checked /><div></div></label>
							</div>

						</div>
                    </div>

					<div class="col-12 col-lg-12 col-xxl-12">
						<div class="card">

							<!-- Card Content -->
							<div class="card-body" style="overflow: scroll;max-height: 400px">
                            <table class="table table-striped table-sm" id="logTable">
                                    <thead>
                                        <tr>
                                            <th>Logs</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Log entries will be dynamically added here -->
										
                                    </tbody>
                                </table>
							</div>

						</div>
                    </div>
				</div>

			</div>

		</div>
	</main>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script>
		 $(document).ready(function() {
            fetchDeviceStatus();

            setInterval(fetchDeviceStatus, 500);
        $('.switch3 input').on('change', function(){
			const dad = $(this).parent();

			if($(this).is(':checked')){
				dad.addClass('switch3-checked');
                console.log (1);
                 updateDeviceStatus(1);
			}else{
				dad.removeClass('switch3-checked');
                console.log(0);
                updateDeviceStatus(0);

			}
		});
    });
        function fetchDeviceStatus() {
            $.ajax({
                url: 'fetch_device_status.php',
                type: 'GET',
                success: function(response) {
                    if (response == 1) {
                        $('#deviceSwitch').prop('checked', true).parent().addClass('switch3-checked');
                    } else {
                        $('#deviceSwitch').prop('checked', false).parent().removeClass('switch3-checked');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); 
                }
            });

            $.ajax({
                url: 'fetch_logs.php',
                type: 'GET',
                success: function(response) {
                    $('#logTable tbody').html(response);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); 
                }
            });
        }

        function updateDeviceStatus(status) {
            $.ajax({
                url: 'update_device_status.php',
                type: 'POST',
                data: { status: status },
                success: function(response) {
                    console.log(response); 
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText); 
                }
            });
        }
    </script>
    </body>
</html>