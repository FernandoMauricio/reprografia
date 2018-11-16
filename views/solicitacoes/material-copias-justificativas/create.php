<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasJustificativas */

$session = Yii::$app->session;
$id_solicitacaoCopias = $session['sess_materialcopias'];

$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias Pendentes', 'url' => ['solicitacoes/material-copias-pendentes/index']];
$this->params['breadcrumbs'][] = ['label' => $id_solicitacaoCopias, 'url' => ['solicitacoes/material-copias/view', 'id' => $id_solicitacaoCopias]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="material-copias-justificativas-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
