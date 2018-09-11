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

$this->title = 'Justificativas da Reprovação ';
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias', 'url' => ['solicitacoes/material-copias/index']];
$this->params['breadcrumbs'][] = ['label' => $id_solicitacaoCopias, 'url' => ['solicitacoes/material-copias/view', 'id' => $id_solicitacaoCopias]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="material-copias-justificativas-index">

    <h1><?= Html::encode($this->title) . "<small>Solicitação de Cópia: ".$id_solicitacaoCopias. "</small>" ?></h1>

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