<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Air Pollution Marquee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: white;
        }
        .marquee {
            width: 100%;
            white-space: nowrap;
            overflow: hidden;
            box-sizing: border-box;
        }
        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 20s linear infinite;
        }
        @keyframes marquee {
            0% { transform: translate(0, 0); }
            100% { transform: translate(-100%, 0); }
        }
    </style>
</head>
<body>
    <div class="marquee">
        <span id="marquee-content">Fetching data...</span>
    </div>

    <script>
        const waqiToken = 'a6be7c57380a0807b499b7611dadadee9f7f4901';

        const cities = [
            { name: 'Kuala Lumpur', lat: 3.1390, lon: 101.6869 },
            { name: 'Johor', lat: 1.4927, lon: 103.7414 },
            { name: 'Penang', lat: 5.4164, lon: 100.3327 },
            { name: 'Kuching', lat: 1.5533, lon: 110.3592 },
            { name: 'Kota Kinabalu', lat: 5.9804, lon: 116.0735 },
            { name: 'Malacca', lat: 2.1896, lon: 102.2501 },
            { name: 'Alor Setar', lat: 6.1248, lon: 100.3674 },
            { name: 'Kuala Terengganu', lat: 5.3302, lon: 103.1408 },
            { name: 'Kuantan', lat: 3.8167, lon: 103.3317 },
            { name: 'Ipoh', lat: 4.5975, lon: 101.0901 },
            { name: 'Kangar', lat: 6.4331, lon: 100.1986 },
            { name: 'Seremban', lat: 2.7252, lon: 101.9378 },
            { name: 'Shah Alam', lat: 3.0738, lon: 101.5183 },
            { name: 'Putrajaya', lat: 2.9264, lon: 101.6964 }
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
                const marqueeContent = results.join(' - ');
                document.getElementById('marquee-content').innerText = marqueeContent;
            });
        }

        updateMarquee();
    </script>
</body>
</html>
