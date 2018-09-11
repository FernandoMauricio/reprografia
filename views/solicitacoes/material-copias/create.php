<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopias */

$this->title = 'Nova Solicitação de Cópia';
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-copias-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'repositorio' => $repositorio,
        'acabamento'  => $acabamento,
        'modelsItens' => $modelsItens,
    ]) ?>

</div>
