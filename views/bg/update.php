<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bg */

$this->title = 'Update Bg: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Bgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="bg-update">
    <div class="line_bottom">ຕັ້ງ​ຄ່າ​ສີ​ຂອງ app</div>
    <br/>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])

    ?>

</div>
