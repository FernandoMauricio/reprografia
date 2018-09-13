<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasJustificativasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
$id_solicitacaoCopias = $session['sess_materialcopias'];

$this->title = 'Justificativas para Correção  ';
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias Pendentes', 'url' => ['solicitacoes/material-copias-pendentes/index']];
$this->params['breadcrumbs'][] = ['label' => $id_solicitacaoCopias, 'url' => ['solicitacoes/material-copias/view', 'id' => $id_solicitacaoCopias]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="material-copias-justificativas-index">

    <h1><?= Html::encode($this->title) . "<small>Solicitação de Cópia: ".$id_solicitacaoCopias. "</small>" ?></h1>

    <p>
        <?= Html::button('Inserir Justificativa', ['value'=> Url::to('index.php?r=solicitacoes/material-copias-justificativas/create&id='.$id_solicitacaoCopias.''), 'class' => 'btn btn-success', 'id'=>'modalButton']) ?>
    </p>

    <?php
        Modal::begin([
            'header' => '<h4>Justificativa</h4>',
            'id' => 'modal',
            'size' => 'modal-lg',
            ]);

        echo "<div id='modalContent'></div>";

        Modal::end();

   ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'descricao',
            'usuario',
            [
                'attribute' => 'data',
                'format' => ['date', 'php:d/m/Y'],
            ],
        ],
    ]); ?>

</div>