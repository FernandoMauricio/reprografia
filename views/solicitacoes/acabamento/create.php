<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\Acabamento */

$this->title = 'Novo Tipo de Acabamento';
$this->params['breadcrumbs'][] = ['label' => 'Tipos de Acabamento', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="acabamento-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
