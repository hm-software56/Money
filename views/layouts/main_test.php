<?php
/* @var $this \yii\web\View */
/* @var $content string */

use app\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body>
        <?php $this->beginBody() ?>
        <?php
        if (!empty(Yii::$app->session['user'])) {
            ?>
            <nav class="navbar navbar-default">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Brand</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav">
                            <?php
                            if (Yii::$app->session['user']->user_type == "User") {
                                ?>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=payment">
                                        <i class="fa fa-th"></i> <span>ຈັດ​ການເງີນ​ທີ່​ຈ່າຍ​ອອກ</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=dao-car">
                                        <i class="fa fa-th"></i> <span>ຈັດ​ການເງີນ​ທີ່​ຈ່າຍ​ຄ່າ​ລົດ​ໃຫຍ່</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=recieve-money">
                                        <i class="fa fa-th"></i> <span>ຈັດ​ການເງີນ​ທີ່​ຮັບ​ເຂົ້າ</span>
                                    </a>
                                </li>

                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-signal"></i> <span>ລາຍ​ງານ​</span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=payment/report">
                                                <i class="fa fa-sellsy"></i>ລາຍ​ງານ​ລາຍ​ຈ່າຍ</a>
                                        </li>
                                        <li>
                                            <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=recieve-money/report">
                                                <i class="fa fa-ra"></i>ລາຍ​ງານ​ລາຍ​ຮັບ</a>
                                        </li>
                                    </ul>
                                </li>
                                <?php
                            }
                            ?>
                            <?php
                            if (Yii::$app->session['user']->user_type == "Admin") {
                                ?>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=payment/report">
                                        <i class="fa fa-sellsy"></i> ລາຍ​ງານ​ລາຍ​ຈ່າຍ</a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=recieve-money/report">
                                        <i class="fa fa-ra"></i> ລາຍ​ງານ​ລາຍ​ຮັບ</a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=site/compare">
                                        <i class="fa fa-th"></i> <span> ສົ​ມ​ທຽບ​ລາຍ​ຮັບ​ລ່າຍ​ຈ່າຍ</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=user">
                                        <i class="fa fa-user"></i> <span> ຈັດ​ການຜູ້​ເຂົ້າ​ລະ​ບົບ</span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                            <li><a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=site/logout" ><span class="fa fa-power-off"></span> ອອກ​ຈາກ​ລະ​ບົບ</a></li>
                        </ul>

                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
            <?php
        }
        ?>
        <div class="container" style="width: 100%">

            <?= $content ?>
        </div>


        <?php $this->endBody() ?>
        <script>
            jQuery(function ($) {
                $('#money').autoNumeric('init', {aSign: ' ກີບ', pSign: 's'});
            });
            jQuery(function ($) {
                $('#money_dao').autoNumeric('init', {aSign: ' ໂດ​ລາ', pSign: 's'});
            });
        </script>
        <?php
        if (!empty(Yii::$app->session['user']) && Yii::$app->controller->action->id == "compare") {

            function weekOfMonth($qDate) {
                $dt = strtotime($qDate);
                $day = date('j', $dt);
                $month = date('m', $dt);
                $year = date('Y', $dt);
                $totalDays = date('t', $dt);
                $weekCnt = 1;
                $retWeek = 0;
                for ($i = 1; $i <= $totalDays; $i++) {
                    $curDay = date("N", mktime(0, 0, 0, $month, $i, $year));
                    if ($curDay == 7) {
                        if ($i == $day) {
                            $retWeek = $weekCnt + 1;
                        }
                        $weekCnt++;
                    } else {
                        if ($i == $day) {
                            $retWeek = $weekCnt;
                        }
                    }
                }
                return $retWeek;
            }

            $w = weekOfMonth(date('Y-m-d')) - 2;
            // exit;
            $r_w = array();
            $p_w = array();
            $label = array();
            $s = 0;
            for ($i = $w; $i >= 0; $i--) {
                $s++;
                $label[] = "ທິດ " . $s;
                $r_w[] = Yii::$app->db->createCommand('SELECT sum(amount)  FROM recieve_money LEFT JOIN user ON recieve_money.user_id=user.id where recieve_money.date BETWEEN "' . date("Y-m-d", strtotime('monday ' . $i . ' week ago')) . '" and "' . date("Y-m-d", strtotime('sunday ' . $i . ' week ago')) . '" and month(recieve_money.date)="' . date('m') . '" and  user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                $p_w[] = Yii::$app->db->createCommand('SELECT sum(amount)  FROM payment LEFT JOIN user ON payment.user_id=user.id where payment.date BETWEEN "' . date("Y-m-d", strtotime('monday ' . $i . ' week ago')) . '" and "' . date("Y-m-d", strtotime('sunday ' . $i . ' week ago')) . '" and month(payment.date)="' . date('m') . '" and user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                if ($i == 0) {
                    $label[] = "ທິດ " . ($s + 1);
                    $r_w[] = Yii::$app->db->createCommand('SELECT sum(amount)  FROM recieve_money LEFT JOIN user ON recieve_money.user_id=user.id where recieve_money.date BETWEEN "' . date("Y-m-d", strtotime('monday this week')) . '" and "' . date("Y-m-d", strtotime('sunday this week')) . '" and month(recieve_money.date)="' . date('m') . '" and  user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                    $p_w[] = Yii::$app->db->createCommand('SELECT sum(amount)  FROM payment LEFT JOIN user ON payment.user_id=user.id where payment.date BETWEEN "' . date("Y-m-d", strtotime('monday this week')) . '" and "' . date("Y-m-d", strtotime('sunday this week')) . '" and month(payment.date)="' . date('m') . '" and user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                }
            }
            $a_w = json_encode($p_w);
            $b_w = json_encode($r_w);

            /// for mount
            $amount_pay = array();
            $amount_recieve = array();
            for ($i = 1; $i <= 12; $i++) {
                $r = Yii::$app->db->createCommand('SELECT sum(amount)  FROM recieve_money LEFT JOIN user ON recieve_money.user_id=user.id where month(recieve_money.date)="' . $i . '" and year(recieve_money.date)="' . date('Y') . '" and  user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                $p = Yii::$app->db->createCommand('SELECT sum(amount)  FROM payment LEFT JOIN user ON payment.user_id=user.id where month(payment.date)="' . $i . '" and year(payment.date)="' . date('Y') . '" and  user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                $amount_recieve[] = $r;
                $amount_pay[] = $p;
            }
            $a = json_encode($amount_pay);
            $b = json_encode($amount_recieve);

            /// for Year
            $min_y = Yii::$app->db->createCommand('SELECT min(year(date))  FROM payment')->queryScalar();
            $max_y = Yii::$app->db->createCommand('SELECT max(year(date))  FROM payment')->queryScalar();

            $amount_y_pay = array();
            $amount_y_recieve = array();
            $label_y = array();
            for ($i = $min_y; $i <= $max_y; $i++) {
                $label_y[] = $i;
                $r_y = Yii::$app->db->createCommand('SELECT sum(amount)  FROM recieve_money LEFT JOIN user ON recieve_money.user_id=user.id where year(recieve_money.date)="' . $i . '" and  user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                $p_y = Yii::$app->db->createCommand('SELECT sum(amount)  FROM payment LEFT JOIN user ON payment.user_id=user.id where year(payment.date)="' . $i . '" and user.user_role_id=' . Yii::$app->session['user']->user_role_id . '')->queryScalar();
                $amount_y_recieve[] = $r_y;
                $amount_y_pay[] = $p_y;
            }
            $a_y = json_encode($amount_y_pay);
            $b_y = json_encode($amount_y_recieve);
            ?>
            <script>
                $(function () {
                    var areaChartData_y = {
                        labels:<?= json_encode($label_y) ?>,
                        datasets: [
                            {
                                label: "ລາຍ​ຮັບ",
                                fillColor: "rgba(60,141,188,0.9)",
                                strokeColor: "rgba(60,141,188,0.8)",
                                pointColor: "#3b8bba",
                                pointStrokeColor: "rgba(60,141,188,1)",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(60,141,188,1)",
                                data: <?= $b_y ?>
                            },
                            {
                                label: "ລາຍ​ຈ່າຍ",
                                fillColor: "rgba(210, 214, 222, 1)",
                                strokeColor: "rgba(210, 214, 222, 1)",
                                pointColor: "rgba(210, 214, 222, 1)",
                                pointStrokeColor: "#c1c7d1",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: <?= $a_y ?>
                            }

                        ]
                    };

                    //-------------
                    //- BAR CHART -
                    //-------------
                    var barChartCanvas = $("#barChart_y").get(0).getContext("2d");
                    var barChart = new Chart(barChartCanvas);
                    var barChartData = areaChartData_y;
                    barChartData.datasets[0].fillColor = "#0c9a13";
                    barChartData.datasets[0].strokeColor = "#0c9a13";
                    barChartData.datasets[0].pointColor = "#0c9a13";

                    barChartData.datasets[1].fillColor = "#fd082b";
                    barChartData.datasets[1].strokeColor = "#fd082b";
                    barChartData.datasets[1].pointColor = "#fd082b";
                    var barChartOptions = {
                        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                        scaleBeginAtZero: true,
                        //Boolean - Whether grid lines are shown across the chart
                        scaleShowGridLines: true,
                        //String - Colour of the grid lines
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        //Number - Width of the grid lines
                        scaleGridLineWidth: 1,
                        //Boolean - Whether to show horizontal lines (except X axis)
                        scaleShowHorizontalLines: true,
                        //Boolean - Whether to show vertical lines (except Y axis)
                        scaleShowVerticalLines: true,
                        //Boolean - If there is a stroke on each bar
                        barShowStroke: true,
                        //Number - Pixel width of the bar stroke
                        barStrokeWidth: 2,
                        //Number - Spacing between each of the X value sets
                        barValueSpacing: 5,
                        //Number - Spacing between data sets within X values
                        barDatasetSpacing: 1,
                        //String - A legend template
                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                        //Boolean - whether to make the chart responsive
                        responsive: true,
                        maintainAspectRatio: true
                    };

                    barChartOptions.datasetFill = false;
                    barChart.Bar(barChartData, barChartOptions);
                });
            </script>
            <script>
                $(function () {
                    var areaChartData_m = {
                        labels: <?= json_encode($label) ?>,
                        datasets: [
                            {
                                label: "ລາຍ​ຮັບ",
                                fillColor: "rgba(60,141,188,0.9)",
                                strokeColor: "rgba(60,141,188,0.8)",
                                pointColor: "#3b8bba",
                                pointStrokeColor: "rgba(60,141,188,1)",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(60,141,188,1)",
                                data: <?= $b_w ?>
                            },
                            {
                                label: "ລາຍ​ຈ່າຍ",
                                fillColor: "rgba(210, 214, 222, 1)",
                                strokeColor: "rgba(210, 214, 222, 1)",
                                pointColor: "rgba(210, 214, 222, 1)",
                                pointStrokeColor: "#c1c7d1",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: <?= $a_w ?>
                            }

                        ]
                    };

                    //-------------
                    //- BAR CHART -
                    //-------------
                    var barChartCanvas = $("#barChart_m").get(0).getContext("2d");
                    var barChart = new Chart(barChartCanvas);
                    var barChartData = areaChartData_m;
                    barChartData.datasets[0].fillColor = "#0c9a13";
                    barChartData.datasets[0].strokeColor = "#0c9a13";
                    barChartData.datasets[0].pointColor = "#0c9a13";

                    barChartData.datasets[1].fillColor = "#fd082b";
                    barChartData.datasets[1].strokeColor = "#fd082b";
                    barChartData.datasets[1].pointColor = "#fd082b";
                    var barChartOptions = {
                        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                        scaleBeginAtZero: true,
                        //Boolean - Whether grid lines are shown across the chart
                        scaleShowGridLines: true,
                        //String - Colour of the grid lines
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        //Number - Width of the grid lines
                        scaleGridLineWidth: 1,
                        //Boolean - Whether to show horizontal lines (except X axis)
                        scaleShowHorizontalLines: true,
                        //Boolean - Whether to show vertical lines (except Y axis)
                        scaleShowVerticalLines: true,
                        //Boolean - If there is a stroke on each bar
                        barShowStroke: true,
                        //Number - Pixel width of the bar stroke
                        barStrokeWidth: 2,
                        //Number - Spacing between each of the X value sets
                        barValueSpacing: 5,
                        //Number - Spacing between data sets within X values
                        barDatasetSpacing: 1,
                        //String - A legend template
                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                        //Boolean - whether to make the chart responsive
                        responsive: true,
                        maintainAspectRatio: true
                    };

                    barChartOptions.datasetFill = false;
                    barChart.Bar(barChartData, barChartOptions);
                });
            </script>
            <script>
                $(function () {
                    var areaChartData = {
                        labels: ["ເດືອນ 1", "ເດືອນ 2", "ເດືອນ 3", "ເດືອນ 4", "ເດືອນ 5", "ເດືອນ 6", "ເດືອນ 7", "ເດືອນ 8", "ເດືອນ 9", "ເດືອນ 10", "ເດືອນ 11", "ເດືອນ 12"],
                        datasets: [
                            {
                                label: "ລາຍ​ຮັບ",
                                fillColor: "rgba(60,141,188,0.9)",
                                strokeColor: "rgba(60,141,188,0.8)",
                                pointColor: "#3b8bba",
                                pointStrokeColor: "rgba(60,141,188,1)",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(60,141,188,1)",
                                data: <?= $b ?>
                            },
                            {
                                label: "ລາຍ​ຈ່າຍ",
                                fillColor: "rgba(210, 214, 222, 1)",
                                strokeColor: "rgba(210, 214, 222, 1)",
                                pointColor: "rgba(210, 214, 222, 1)",
                                pointStrokeColor: "#c1c7d1",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: <?= $a ?>
                            }

                        ]
                    };

                    //-------------
                    //- BAR CHART -
                    //-------------
                    var barChartCanvas = $("#barChart").get(0).getContext("2d");
                    var barChart = new Chart(barChartCanvas);
                    var barChartData = areaChartData;
                    barChartData.datasets[0].fillColor = "#0c9a13";
                    barChartData.datasets[0].strokeColor = "#0c9a13";
                    barChartData.datasets[0].pointColor = "#0c9a13";

                    barChartData.datasets[1].fillColor = "#fd082b";
                    barChartData.datasets[1].strokeColor = "#fd082b";
                    barChartData.datasets[1].pointColor = "#fd082b";
                    var barChartOptions = {
                        //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
                        scaleBeginAtZero: true,
                        //Boolean - Whether grid lines are shown across the chart
                        scaleShowGridLines: true,
                        //String - Colour of the grid lines
                        scaleGridLineColor: "rgba(0,0,0,.05)",
                        //Number - Width of the grid lines
                        scaleGridLineWidth: 1,
                        //Boolean - Whether to show horizontal lines (except X axis)
                        scaleShowHorizontalLines: true,
                        //Boolean - Whether to show vertical lines (except Y axis)
                        scaleShowVerticalLines: true,
                        //Boolean - If there is a stroke on each bar
                        barShowStroke: true,
                        //Number - Pixel width of the bar stroke
                        barStrokeWidth: 2,
                        //Number - Spacing between each of the X value sets
                        barValueSpacing: 5,
                        //Number - Spacing between data sets within X values
                        barDatasetSpacing: 1,
                        //String - A legend template
                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                        //Boolean - whether to make the chart responsive
                        responsive: true,
                        maintainAspectRatio: true
                    };

                    barChartOptions.datasetFill = false;
                    barChart.Bar(barChartData, barChartOptions);
                });

            </script>
            <?php
        }
        ?>
    </body>
</html>
<?php $this->endPage() ?>
