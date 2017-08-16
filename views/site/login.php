
<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\alert\Alert;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
if (Yii::$app->session->hasFlash('su')) {
    echo Alert::widget([
        'type' => Alert::TYPE_DANGER,
        'title' => Yii::$app->session->getFlash('action'),
        'icon' => 'glyphicon glyphicon-alert',
        'body' => Yii::$app->session->getFlash('su'),
        'showSeparator' => false,
        'delay' => 5000
    ]);
}
if (Yii::$app->session->hasFlash('reg')) {
    echo Alert::widget([
        'type' => Alert::TYPE_SUCCESS,
        'title' => Yii::$app->session->getFlash('action'),
        'icon' => 'glyphicon glyphicon-ok',
        'body' => Yii::$app->session->getFlash('reg'),
        'showSeparator' => false,
        'delay' => 12000
    ]);
}
?>

<?php
if (!isset($_GET['reg'])) {
    $form = ActiveForm::begin([
                'id' => 'login-form',
                'options' => ['class' => 'form-horizontal', 'autocomplete' => "off"],
                'fieldConfig' => [
                    'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
                    'labelOptions' => ['class' => 'col-lg-1 control-label'],
                ],
    ]);
    ?>

    <div class="login-box " style="margin-top: 0px;" >
        <div class="login-box-body">
            <div class="form-group has-feedback">
                <div class="line_bottom">ປ້ອນຊື່ເຂົ້າ​ລະ​ບົບ ແລະ ລະ​ຫັດ​ຜ່ານ</div>
              <!--  ຖ້າ​ທ່ານ​ຍັງ​ບໍໍ່​ມີ​ລະ​ຫັດ​ເຂົ້າ​ລະ​ບົບກົດ​ທີ່ນີ້ <a href="<?= Yii::$app->urlManager->baseUrl ?>/index.php?r=site/login&reg=true"><span class="fa fa-registered btn " ></span></a>-->
            </div>
            <div class="form-group has-feedback">
                <input type="text" name="LoginForm[username]" class="form-control" placeholder="ຊື່​ເຂົ້າ​ລະ​ບົບ" value="<?= $model->username ?>" required >
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input type="password" name="LoginForm[password]" class="form-control" placeholder="ລະ​ຫັດ​ເຂົ້າ​ລະ​ບົບ" value="<?= $model->password ?>" required >
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <div class="col-xs-12" style="padding-right: 0px; padding-left: 0px;" align="right">
                    <button type="submit" class="btn btn-primary  col-xs-12"><span class="fa fa-lock" ></span> ເຂົ້າ​ລະ​ບົບ</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    ActiveForm::end();
} else {
    $model = $login;
    ?>
    <div class="user-form">

        <?php $form = ActiveForm::begin(['action' => ['site/reg'], 'id' => 'forum_post', 'method' => 'post',]); ?>
        <?= $form->field($model, 'first_name')->textInput(['maxlength' => true])->label('ຊື່') ?>

        <?= $form->field($model, 'last_name')->textInput(['maxlength' => true])->label('ນາມ​ສະ​ກຸນ') ?>
        <?php
        echo $form->field($model, 'username')->textInput(['maxlength' => true])->label('ຊື່​ເຂົ້າ​ລະ​ບົບ');
        ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->label('ລະ​ຫັດ​ເຂົ້າ​ລະ​ບົບ') ?>
        <?= $form->field($model, 'status')->hiddenInput(['value' => "1"])->label(false) ?>
        <?= $form->field($model, 'user_type')->hiddenInput(['value' => "User"])->label(false) ?>
        <?= $form->field($model, 'date')->hiddenInput(['value' => date('Y-m-d')])->label(false) ?>
        <?= $form->field($model, 'user_role_id')->hiddenInput(['value' => 2])->label(false) ?>
        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? '<span class="fa fa-save"></span> ລົງ​ທະ​ບຽນ' : '<span class="fa fa-save"></span> ບັນ​ທືກ', ['class' => $model->isNewRecord ? 'btn btn-success btn-sm' : 'btn btn-primary btn-sm']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
    <?php
}
?>