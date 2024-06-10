<!DOCTYPE html>
<html lang="en">

<head>
    <?php
        include './include/header.php';
        include './include/config.php';
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
            -webkit-backdrop-filter: blur(2px) !important;
            backdrop-filter: blur(2px) !important;
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
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100 p-3">
                    <div class="card">
                        <div class="h-100 w-100 d-flex flex-column gap-3 justify-content-center align-items-center card-body">
                            
                            <br><br>
                            <h1 class="text-white text-center">About Us</h1>

                            <h2 class="text-white text-center">Learn more about the team and mission behind the Air Pollutant Monitoring System</h2>

                            <h3 class="text-white text-center">Our Mission</h3>
                            <p class="text-white m-0 text-center">We are dedicated to providing accurate and real-time information about air quality to help individuals and communities make informed decisions for healthier living environments.</p>

                            <h3 class="text-white text-center">Contact Us</h3>
                            <p class="text-white m-0 text-center">If you have any questions or feedback, feel free to contact us at <a href="#" class="text-warning">info@airguardian.com</a>.</p>
                            <!-- Adding Instagram and Twitter icons -->
                            <div class="text-center">
                                <a href="https://www.instagram.com" target="_blank"><img src="images/instagram.png" alt="Instagram" width="30" height="30"></a>
                                <a href="https://twitter.com" target="_blank"><img src="images/X.png" alt="Twitter" width="30" height="30"></a>
								<a href="https://gmail.com" target="_blank"><img src="images/gmail.png" alt="Twitter" width="30" height="30"></a>
                            </div>
                            <br><br>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>
</body>
</html>
