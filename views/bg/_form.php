<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bg */
/* @var $form yii\widgets\ActiveForm */

?>

<div class="bg-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php
    $bg_menu = array('skin-black' => 'Black/ສີດຳ', 'skin-blue' => 'Blue/ສີຟ້າ', 'skin-green' => 'Green/ສີຂຽວ', 'skin-purple' => 'Purple/ສີມ່ວງ', 'skin-red' => 'Red/ສ​ີ​ແດງ', 'skin-yellow' => 'Yellow​/ສີ​ເຫຼີອງ');
    $bg_footer = array('bg-black' => 'Black/ສີດຳ', 'bg-blue' => 'Blue/ສີຟ້າ', 'bg-green' => 'Green/ສີຂຽວ', 'bg-purple' => 'Purple/ສີມ່ວງ', 'bg-red' => 'Red/ສ​ີ​ແດງ', 'bg-yellow' => 'Yellow​/ສີ​ເຫຼີອງ');

    ?>
    <?= $form->field($model, 'bg_menu')->dropDownList($bg_menu) ?>
    <?= $form->field($model, 'bg_footer')->dropDownList($bg_footer) ?>
    <?= $form->field($model, 'bg_button')->dropDownList($bg_footer) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '<span class="fa fa-save"></span> ບັນ​ທືກ' : '<span class="fa fa-save"></span> ບັນ​ທືກ', ['class' => $model->isNewRecord ? 'btn ' . Yii::$app->session['bg_buttoon'] . ' btn-sm' : 'btn ' . Yii::$app->session['bg_buttoon'] . ' btn-sm']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
