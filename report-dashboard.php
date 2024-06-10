<!DOCTYPE html>
<html lang="en">

<head>
    <?php
        include './include/header.php';
        include './include/config.php';
        include './include/auth-checker.php';
    ?>

    <link href="css/datatables.css" rel="stylesheet">

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
            <div class="container-fluid p-4 d-flex align-items-center flex-column gap-5" style="min-height: 100vh">
                <!-- Page Title -->
                <div class="row mb-2 mt-5 mb-xl-3">
                    <div class="col-auto d-none d-sm-block">
                        <h3 class="text-white"><strong>Report</strong> Dashboard</h3>
                    </div>
                </div>
                
                <div class="row w-100 px-6">
                    <!-- Left Content -->
                    <div class="col-xl-5 col-xxl-5">
                        <div class="card flex-fill w-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Daily Reading Chart</h5>
                            </div>
                            <div class="card-body pt-2 pb-3" style="overflow-x: scroll; max-width: -webkit-fill-available; height: 400px; position: relative;">
                                <style>canvas#chartjs-bar { position: absolute; width: 100% !important; }</style>
                                <canvas id="chartjs-bar" width="624" height="600" style="display: block; height: 300px; width: 312px;" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content -->
                    <div class="col-xl-7 col-xxl-7">
                        <div class="card flex-fill w-100">
                            <div class="card-header">
                                <h5 class="card-title mb-0">Monitoring Chart</h5>
                            </div>
                            <div class="card-body pt-2 pb-3">
                                <table class="table table-striped table-sm" id="table-dataset">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th class="text-center">AQI Reading</th>
                                            <th class="text-center">Air Type</th>
                                            <th class="text-center">Action To Be Taken</th>
                                            <th class="text-center">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            // Fetching data from the reports table
                                            $dataset = fetchRows("SELECT * FROM `reports`");

                                            $processedData = [];

                                            if(!empty($dataset)){
                                                foreach ($dataset as $key => $data) {
                                                    $processedData[] = [
                                                        'ID' => $data['ID'],
                                                        'AQI Reading' => $data['AQI Reading'],
                                                        'Air Type' => $data['Air Type'],
                                                        'Action To Be Taken' => $data['Action To Be Taken'],
                                                        'Date' => $data['Date']
                                                    ];

                                                    echo '
                                                    <tr>
                                                        <td>' . $data['ID'] . '</td>
                                                        <td class="text-center">' . $data['AQI Reading'] . '</td>
                                                        <td class="text-center">' . $data['Air Type'] . '</td>
                                                        <td class="text-center">' . $data['Action To Be Taken'] . '</td>
                                                        <td class="text-center">' . $data['Date'] . '</td>
                                                    </tr>
                                                    ';
                                                }
                                            } else {
                                                echo '<tr><td colspan="5">No record found</td></tr>';
                                            }
                                        ?>
                                    </tbody>
                                </table>

                                <button class="btn btn-success" id="download">
                                    <i class="align-middle" data-feather="download"></i> Download 
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- #END Row -->
                </div>
            </div>
        </div>
    </main>

    <script src="./js/jquery.min.js"></script>
    <script src="./js/datatables.js"></script>

    <?php if(!empty($dataset)): ?>
    <script>
        const reportJson = <?php echo json_encode($processedData); ?>;

        const jsonToCSV = function (json) {
            const items = json;
            const replacer = (key, value) => value === null ? '' : value;
            const header = Object.keys(items[0]);

            return [header.join(','), ...items.map(row => header.map(fieldName => JSON.stringify(row[fieldName], replacer)).join(','))].join('\r\n');
        };

        const downloadCSV = function (csv, filename) {
            const csvFile = new Blob([csv], { type: 'text/csv' });
            const downloadLink = document.createElement('a');

            downloadLink.download = filename;
            downloadLink.href = window.URL.createObjectURL(csvFile);
            downloadLink.style.display = 'none';
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);
        };

        document.getElementById('download').addEventListener('click', function() {
            downloadCSV(jsonToCSV(reportJson), 'airguardian-weeklyreport.csv');
        });

        document.addEventListener("DOMContentLoaded", function() {
            new DataTable("#table-dataset", {
                paging: true,
                sort: true,
                scrollCollapse: false,
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            new Chart(document.getElementById("chartjs-bar"), {
                type: "bar",
                data: {
                    labels: reportJson.map(item => item['Date']),
                    datasets: [{
                        label: "AQI Readings",
                        backgroundColor: '#1cbb8c',
                        borderColor: '#1cbb8c',
                        hoverBackgroundColor: '#1cbb8c',
                        hoverBorderColor: '#1cbb8c',
                        data: reportJson.map(item => item['AQI Reading']),
                        barPercentage: 1,
                        categoryPercentage: .5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            stacked: false,
                            ticks: {
                                stepSize: 5
                            }
                        }]
                    }
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
