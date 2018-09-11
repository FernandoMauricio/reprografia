<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopias */

$this->title = 'Atualizar Solicitação de Cópias: ' . $model->matc_id;
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->matc_id, 'url' => ['view', 'id' => $model->matc_id]];
$this->params['breadcrumbs'][] = 'Atualizar';
?>
<div class="material-copias-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('update/_form', [
        'model' => $model,
        'repositorio' => $repositorio,
        'acabamento'  => $acabamento,
        'modelsItens' => $modelsItens,
    ]) ?>

</div>
