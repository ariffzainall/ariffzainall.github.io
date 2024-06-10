<!DOCTYPE html>
<html lang="en">

<head>
    <?php 
        include './include/config.php';
        include './include/header.php';
        include './include/auth-redirect.php';
    ?>

    <style>
        main {
            background: linear-gradient(rgb(0 0 0 / 45%), rgb(54 54 54 / 45%)), url('images/background.jpg');
            background-repeat: no-repeat;
            background-size: cover;
            min-height: 100vh;
        }

        .card,
        .glasses {
            background: rgba(255, 255, 255, 0.2) !important;
            -webkit-backdrop-filter: blur(2px) !important;
            backdrop-filter: blur(2px) !important;
            max-width: 100%;
        }

        .marquee-container {
            overflow: hidden;
            white-space: nowrap;
            box-sizing: border-box;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.3);
            color: white;
            padding: 10px 0;
        }

        .marquee {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }

        .text-center {
            text-align: center;
        }

        .content-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 50px); /* Adjust based on the height of your marquee */
            padding-top: 50px; /* Adjust this value to move the container down */
        }
    </style>
</head>

<body>
    <main class="d-flex w-100">
        <div class="container-fluid d-flex flex-column p-0">
            <!-- Header -->
            <?php include './include/navbar-main.php'; ?>

            <!-- AQI Marquee -->
            <div class="marquee-container">
                <div class="marquee" id="marquee-content">Fetching data...</div>
            </div>

            <!-- Content -->
            <div class="content-wrapper">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto">
                    <div class="card">
                        <div class="h-100 w-100 d-flex flex-column gap-3 justify-content-center align-items-center card-body">
                            <h1 class="text-white">Air Pollutant Monitoring System</h1>
                            <h2 class="text-white">Ensuring Clean and Healthy Air Quality</h2>
                            <h3 class="text-white">Welcome to our Monitoring System</h3>
                            <p class="text-white m-0 text-center">Monitor and analyze air quality in real-time. Stay informed about the pollutants affecting your environment.</p>
                            <p class="text-white m-0">Explore our featured areas to view detailed air quality reports.</p>
                            <a href="./sign-in" class="btn btn-warning btn-lg my-3">Get Started!</a>
                            <h4 class="text-white m-0">Key Features</h4>
                            <ol class="text-white m-0">
                                <li>Real-time monitoring of air quality</li>
                                <li>Interactive maps with pollutant concentrations</li>
                                <li>Customizable alerts for high pollution levels</li>
                                <li>Historical data analysis and trend tracking</li>
                            </ol>
                            <h4 class="text-white m-0 mt-4">Common Air Pollutants</h4>
                            <p class="text-white my-0">Our system monitors various pollutants, including:</p>
                            <ol class="text-white m-0">
                                <li>Particulate Matter (PM2.5 and PM10)</li>
                                <li>Ozone (O3)</li>
                                <li>Nitrogen Dioxide (NO2)</li>
                                <li>Sulfur Dioxide (SO2)</li>
                                <li>Carbon Monoxide (CO)</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const waqiToken = 'a6be7c57380a0807b499b7611dadadee9f7f4901';

        const cities = [
            { name: '🏙️ Kuala Lumpur', lat: 3.1390, lon: 101.6869 },
            { name: '🏙️ Johor', lat: 1.4927, lon: 103.7414 },
            { name: '🏙️ Georgetown', lat: 5.4164, lon: 100.3327 },
            { name: '🏙️ Kuching', lat: 1.5533, lon: 110.3592 },
            { name: '🏙️ Kota Kinabalu', lat: 5.9804, lon: 116.0735 },
            { name: '🏙️ Malacca City', lat: 2.1896, lon: 102.2501 },
            { name: '🏙️ Alor Setar', lat: 6.1248, lon: 100.3674 },
            { name: '🏙️ Kuala Terengganu', lat: 5.3302, lon: 103.1408 },
            { name: '🏙️ Kuantan', lat: 3.8167, lon: 103.3317 },
            { name: '🏙️ Ipoh', lat: 4.5975, lon: 101.0901 },
            { name: '🏙️ Kangar', lat: 6.4331, lon: 100.1986 },
            { name: '🏙️ Seremban', lat: 2.7252, lon: 101.9378 },
            { name: '🏙️ Shah Alam', lat: 3.0738, lon: 101.5183 },
            { name: '🏙️ Putrajaya', lat: 2.9264, lon: 101.6964 }
        ];

        function getAirPollution(city) {
            const airPollutionUrl = `https://api.waqi.info/feed/geo:${city.lat};${city.lon}/?token=${waqiToken}`;
            console.log(`Fetching data from: ${airPollutionUrl}`); // Debugging line to check the URL

            return fetch(airPollutionUrl)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('API response for', city.name, ':', data); // Debugging line
                    if (data.status === 'ok' && data.data && data.data.aqi !== undefined) {
                        const aqi = data.data.aqi;
                        return `${city.name} AQI: ${aqi}`;
                    } else {
                        throw new Error(`Invalid data for ${city.name}`);
                    }
                })
                .catch(error => {
                    console.error('Error fetching air pollution data for', city.name, ':', error);
                    return `${city.name} AQI: N/A`;
                });
        }

        function updateMarquee() {
            const promises = cities.map(city => getAirPollution(city));
            
            Promise.all(promises).then(results => {
                const marqueeContent = "<<⚠️ Current Cities AQI reading: |     " + results.join(' | ')  + " | ⚠️>>";
                document.getElementById('marquee-content').innerText = marqueeContent;
            });
        }

        updateMarquee();
    </script>
</body>
</html>
