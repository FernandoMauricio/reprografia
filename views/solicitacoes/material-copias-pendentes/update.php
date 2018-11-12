<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasPendentes */

$this->title = 'Update Material Copias Pendentes: ' . $model->matc_id;
$this->params['breadcrumbs'][] = ['label' => 'Material Copias Pendentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->matc_id, 'url' => ['view', 'id' => $model->matc_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="material-copias-pendentes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
