<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasJustificativasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Justificativas da Reprovação ';
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias', 'url' => ['solicitacoes/material-copias/index']];
$this->params['breadcrumbs'][] = ['label' => $model->matc_id, 'url' => ['solicitacoes/material-copias/view', 'id' => $model->matc_id]];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="material-copias-justificativas-index">

    <h1><?= Html::encode($this->title) . "<small>Solicitação de Cópia: ".$model->matc_id. "</small>" ?></h1>

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