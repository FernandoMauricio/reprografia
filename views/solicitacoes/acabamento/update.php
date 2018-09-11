<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\Acabamento */

$this->title = 'Atualizar Tipo de Acabamento: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Acabamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="acabamento-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
