<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasPendentes */

$this->title = 'Create Material Copias Pendentes';
$this->params['breadcrumbs'][] = ['label' => 'Material Copias Pendentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-copias-pendentes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
