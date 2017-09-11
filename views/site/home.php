<?php
$payment = Yii::$app->db->createCommand("select sum(amount) from payment");
$recived = Yii::$app->db->createCommand("select sum(amount) from recieve_money");
$pr = ($payment->queryScalar() * 100) / $recived->queryScalar();

?>
<div class="row">
    <div class="col-xs-12">

        <div class="info-box bg-blue">
            <a style="color:#fff;" href="index.php?r=payment">
                <span class="info-box-icon"><i class="fa fa-money "></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text">ລາຍ​ຈ່າຍ</span>
                <span class="info-box-text">
                    <?php
                    echo number_format($payment->queryScalar(), 0);

                    ?>
                </span>

                <div class="progress">
                    <div class="progress-bar" style="width: <?= $pr ?>%"></div>
                </div>
                <span class="progress-description">
                    <?= round($pr) ?>%
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-xs-12">
        <div class="info-box bg-green">
            <a style="color:#fff;" href="index.php?r=recieve-money">
                <span class="info-box-icon"><i class="fa fa-inbox"></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text">ລາຍ​ຮັບ</span>
                <span class="info-box-text">
                    <?php
                    echo number_format($recived->queryScalar(), 0);

                    ?>
                </span>

                <div class="progress">
                    <div class="progress-bar" style="width: <?= 100 - $pr ?>%"></div>
                </div>
                <span class="progress-description">
                    <?= round(100 - $pr) ?>%
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <?php
    $pay_car_all = Yii::$app->db->createCommand("select sum(amount) from dao_car");
    $pay_car = Yii::$app->db->createCommand("select sum(amount) from dao_car where status='Paid'");

    ?>
    <div class="col-xs-12">
        <div class="info-box bg-yellow">
            <a style="color:#fff;" href="index.php?r=dao-car">
                <span class="info-box-icon"><i class="fa fa-car "></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text">ຈ່າຍ​ຄ່າລົດ</span>
                <span class="info-box-text"><?= number_format($pay_car_all->queryScalar(), 0); ?></span>
                <div class="progress">
                    <div class="progress-bar" style="width: <?= ($pay_car->queryScalar() * 100) / $pay_car_all->queryScalar() ?>%"></div>
                </div>
                <span class="progress-description">
                    <?= round(($pay_car->queryScalar() * 100) / $pay_car_all->queryScalar()) ?>%
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
    <div class="col-xs-12">
        <div class="info-box bg-blue-active">
            <a style="color:#fff;" href="index.php?r=site/index">
                <span class="info-box-icon"><i class="fa fa-bar-chart"></i></span>
            </a>
            <div class="info-box-content">
                <span class="info-box-text">ລາຍ​ງານ</span>
                <span class="info-box-text" style="color:#89f8c1"><?= number_format($recived->queryScalar(), 0) ?></span>

                <div class="progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="progress-description" style="color: red">
                    <?= number_format($payment->queryScalar(), 0) ?>
                </span>
            </div>
            <!-- /.info-box-content -->
        </div>
    </div>
</div>