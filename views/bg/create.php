<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Bg */

$this->title = 'Create Bg';
$this->params['breadcrumbs'][] = ['label' => 'Bgs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bg-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
