<?php
require_once("db.php");
$type = (!empty($_GET["type"])) ? $_GET["type"] : "bar";
if (isset($_GET["submit"])) {
    $_SESSION["date"] = $_GET["date"];
    $_SESSION["choose"] = $_GET["choose"];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("header.php"); ?>
    <script src="js/highcharts.js"></script>
</head>

<body>
    <?php require_once("nav.php"); ?>
    <div class="container">
        <h2 class="header">統計圖</h2>
        <hr>
        <form action="" method="get">

            <input type="date" name="date">
            <select name="choose">
                <option value="order">優先順序</option>
                <option value="mode">處理情況</option>
            </select>
            <select name="type">
                <option value="pie">圓餅圖</option>
                <option value="bar">長條圖</option>
            </select>
            <button class="btn btn-info" name="submit" type="submit">確定</button>
        </form>
        <br>

        <div class="card">
            <div id="container"></div>
        </div>

    </div>
    <script>
        var chartdata = {
            chart: {
                renderTo: 'container',
                type: '<?= $type ?>'
            },
            title: {
                text: '工作處理狀況'
            },
            xAxis: {
                categories: null,
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        formatter: function() {
                            return '<b>' + this.point.name + '</b>: ' + this.percentage + ' %';
                        },
                    }
                },
            },
            series: null
        };

        async function chart() {
            await fetch("chart_api.php")
                .then(res => res.json())
                .then(res => {
                    chartdata.series = [{
                        data: res,
                    }]
                })
            var chart = new Highcharts.Chart(chartdata);
        }

        $(function() {
            chart();
        });
    </script>
</body>

</html>