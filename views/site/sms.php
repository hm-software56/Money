
<div class="row sms-s">
    <?php
    $n = 0;
    $cookie = Yii::$app->request->cookies;
    $cookieValue = $cookie->getValue('check_sms');

    $sms = app\models\Sms::find()->where('date>="' . date('Y-m-d H:i:s', strtotime('-10 days')) . '" and date<="' . date('Y-m-d H:i:s', strtotime('1 days')) . '"')->orderBy('id DESC')->all();
    foreach ($sms as $sms) {
        if (!in_array($cookieValue, explode(',', $sms->user_id))) {
            $n++;

            ?>
            <div class="col-xs-12 line_bottom_sms " >
                <?php \yii\widgets\Pjax::begin(['timeout' => 5000]); ?>
                <b><?= $sms->title ?></b>
                <?= \yii\helpers\Html::a($sms->details, ['site/sms', 'id' => $sms->id], ['class' => 'link-sms']) ?>
                <?php
                if (isset($a) && $_GET['id'] == $sms->id) {
                    echo "<span style='color:red'>" . $a . '</span>';
                }

                ?>
                <?php \yii\widgets\Pjax::end(); ?>
            </div>

            <?php
        }
    }
    if ($n == 0) {

        ?>
        <div class="col-xs-12" style="color: red;" align="center" ><br/><br/><br/><br/>
            <h2>ບໍ່​ມີ​ຂໍ້​ຄວາມ</h2>
        </div>
        <?php
    }

    ?>

</div>
