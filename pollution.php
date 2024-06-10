<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include './include/header.php';
    include './include/config.php';
    include './include/auth-checker.php';
    ?>

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
    </style>
    <!-- JavaScript for Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>

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
                <div class="row mb-2 mt-5 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3 class="text-white"><strong>Air Monitor</strong> Dashboard</h3>
                    </div>
                </div>

                <div class="row">
                    <!-- Left Content -->
                    <div class="col-xl-6 col-xxl-5 d-flex">
                        <div class="w-100">
                            <div class="row">

                                <div class="col-sm-12">
                                    <h5 class="mb-3 bg-white rounded p-3">Current Date: <strong>
                                            <?php echo date('d M Y h:i A', time()); ?>
                                        </strong></h5>
                                </div>

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">AQI Reading</h5>
                                                </div>
                                            </div>
                                            <h1 id="aqiPercentage" class="mt-1 mb-3">Loading...</h1>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Sensor Readings</h5>
                                                </div>
                                            </div>
                                            <table class="mt-1 mb-3 table">
                                                <tr>
                                                    <td>MQ5 (N)</td>
                                                    <td id="mq5Value">Loading...</td>
                                                </tr>
                                                <tr>
                                                    <td>MQ6 (Methane)</td>
                                                    <td id="mq6Value">Loading...</td>
                                                </tr>
                                                <tr>
                                                    <td>MQ7 (CO)</td>
                                                    <td id="mq7Value">Loading...</td>
                                                </tr>
                                                <tr>
                                                    <td>MQ135 (CO2)</td>
                                                    <td id="mq135Value">Loading...</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-sm-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Air Type</h5>
                                                </div>
                                            </div>
                                            <h1 id="airType" class="mt-1 mb-3 fw-bold">Null</h1>
                                        </div>
                                    </div>
                                    <script>
                                        // Fetch air reading percentage from gas_fetch.php
                                        fetch('gas_fetch.php')
                                            .then(response => response.json())
                                            .then(data => {
                                                // Get air reading percentage
                                                const airReading = data.airReading;

                                                // Get air type element
                                                const airTypeElement = document.getElementById('airType');

                                                // Get action element
                                                const actionElement = document.getElementById('actionToBeTaken');

                                                // Remove previous classes
                                                airTypeElement.classList.remove('text-success', 'text-warning', 'text-danger');

                                                // Update air type and action based on air reading percentage
                                                if (airReading <= 50) {
                                                    airTypeElement.textContent = 'Good';
                                                    airTypeElement.classList.add('text-success');
                                                    actionElement.textContent = 'Go outside';
                                                } else if (airReading > 50 && airReading <= 100) {
                                                    airTypeElement.textContent = 'Intermediate';
                                                    airTypeElement.classList.add('text-warning');
                                                    actionElement.textContent = 'Wear mask';
                                                } else {
                                                    airTypeElement.textContent = 'Danger';
                                                    airTypeElement.classList.add('text-danger');
                                                    actionElement.textContent = 'Stay Inside!';
                                                }
                                            })
                                            .catch(error => console.error('Error fetching air reading:', error));
                                    </script>


                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col mt-0">
                                                    <h5 class="card-title">Action To Be Taken</h5>
                                                </div>
                                            </div>
                                            <div class="mt-1">
                                                <p id="actionToBeTaken" class="form-control py-3">Loading...</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Right Content -->
                    <div class="col-xl-6 col-xxl-7">
                        <div class="card flex-fill w-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Monitoring Chart</h5>
                            </div>
                            <div class="card-body pt-2 pb-3" style="
    overflow-x: scroll;
    max-width: -webkit-fill-available;
    height: 300px;
    position: relative;">
    <style>canvas#sensorChart {
    position: absolute;
    width: 100% !important;
}</style>
                                <canvas id="sensorChart" width="900" height="450"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>


                    <script>
                        var map = L.map('map').setView([0, 0], 13);

                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            maxZoom: 19,
                        }).addTo(map);

                        function onLocationFound(e) {
                            var radius = e.accuracy / 5;

                            L.marker(e.latlng).addTo(map)
                                .bindPopup("You are within " + radius + " meters from this point").openPopup();

                            L.circle(e.latlng, radius).addTo(map);
                        }

                        function onLocationError(e) {
                            alert(e.message);
                        }

                        var geoOptions = {
                            enableHighAccuracy: true,
                            maximumAge: 30000,
                            timeout: 27000
                        };

                        map.on('locationfound', onLocationFound);
                        map.on('locationerror', onLocationError);

                        map.locate(geoOptions);

                        const dataURL = 'get_sensor_data.php';
                        let chartInstance;

                        fetch(dataURL)
                            .then(response => response.json())
                            .then(data => {
                                const mq5Data = data.map(entry => entry.mq5_value);
                                const mq6Data = data.map(entry => entry.mq6_value);
                                const mq7Data = data.map(entry => entry.mq7_value);
                                const mq135Data = data.map(entry => entry.mq135_value);
                                const labels = data.map(entry => entry.timestamp);

                                const ctx = document.getElementById('sensorChart').getContext('2d');
                                const drawingOptions = {
                                    type: 'line',
                                    responsive: true,
                                    data: {
                                        labels: labels,
                                        datasets: [
                                            {
                                                label: 'N',
                                                data: mq5Data,
                                                borderColor: 'red',
                                                fill: false
                                            },
                                            {
                                                label: 'Methane',
                                                data: mq6Data,
                                                borderColor: 'blue',
                                                fill: false
                                            },
                                            {
                                                label: 'CO',
                                                data: mq7Data,
                                                borderColor: 'green',
                                                fill: false
                                            },
                                            {
                                                label: 'CO2',
                                                data: mq135Data,
                                                borderColor: 'orange',
                                                fill: false
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        title: {
                                            display: true,
                                            text: 'Sensor Data Visualization'
                                        },
                                        scales: {
                                            xAxes: [{
                                                type: 'time',
                                                time: {
                                                    displayFormats: {
                                                        second: 'HH:mm:ss'
                                                    }
                                                },
                                                scaleLabel: {
                                                    display: true,
                                                    labelString: 'Time'
                                                }
                                            }],
                                            yAxes: [{
                                                scaleLabel: {
                                                    display: true,
                                                    labelString: 'Sensor Value'
                                                }
                                            }]
                                        }
                                    }
                                };

                                chartInstance = new Chart(ctx, drawingOptions);

                                window['jubo'] = () => {
                                    chartInstance.destroy();
                                    chartInstance = new Chart(ctx, drawingOptions);
                                };
                            })
                            .catch(error => console.error('Error fetching data:', error));


     /*                   function fetchAirReadingData() {
                            var xhr = new XMLHttpRequest();
                            xhr.onreadystatechange = function () {
                                if (this.readyState === 4 && this.status === 200) {
                                    var airReadingData = JSON.parse(this.responseText);
                                    updateAirReadingChart(airReadingData);
                                }
                            };
                            xhr.open('GET', 'addData.php', true);
                            xhr.send();
                        }

                        setInterval(fetchAirReadingData, 60000);

                        fetchAirReadingData();

*/
                        function updateAirQuality() {
                            fetch('gas_fetch.php')
                                .then(response => response.json())
                                .then(data => {
                                    document.getElementById('aqiPercentage').textContent = data.aqi + '%';

                                    // Update sensor values
                                    document.getElementById('mq5Value').textContent = data.mq5;
                                    document.getElementById('mq6Value').textContent = data.mq6;
                                    document.getElementById('mq7Value').textContent = data.mq7;
                                    document.getElementById('mq135Value').textContent = data.mq135;

                                    // Update air type based on AQI reading
                                    const airTypeElement = document.getElementById('airType');
                                    airTypeElement.classList.remove('text-success', 'text-warning', 'text-danger');
                                    if (data.aqi <= 50) {
                                        airTypeElement.textContent = 'Good';
                                        airTypeElement.classList.add('text-success');
                                    } else if (data.aqi > 50 && data.aqi <= 99) {
                                        airTypeElement.textContent = 'Intermediate';
                                        airTypeElement.classList.add('text-warning');
                                    } else {
                                        airTypeElement.textContent = 'Danger';
                                        airTypeElement.classList.add('text-danger');
                                    }

                                    // Update action based on AQI reading
                                    const actionElement = document.getElementById('actionToBeTaken');
                                    if (data.aqi <= 50) {
                                        actionElement.textContent = 'Go outside';
                                    } else if (data.aqi > 50 && data.aqi <= 99) {
                                        actionElement.textContent = 'Wear mask';
                                    } else {
                                        actionElement.textContent = 'Stay Inside!';
                                    }
                                })
                                .catch(error => console.error('Error fetching air quality data:', error));
                        }

                        updateAirQuality();
                        setInterval(updateAirQuality, 10000);
                    </script>

                    <?php if (isset($_SESSION['admin'])) { ?>
                        <div class="col-xl-12 col-xxl-12 mt-3">
                            <div class="w-100 d-flex gap-3 justify-content-center bg-white p-3 rounded">
                                <a href="control-panel" class="btn btn-success me-2">Scan</a>
                                <a href="report-dashboard.php" class="btn btn-primary">Report</a>
                            </div>
                        </div>
                    <?php } ?>

                    <!-- #END Row -->
                </div>
            </div>

        </div>
    </main>
</body>

</html>
