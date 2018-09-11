<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasPendentes */

$this->title = $model->matc_id;
$this->params['breadcrumbs'][] = ['label' => 'Material Copias Pendentes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-copias-pendentes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->matc_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->matc_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'matc_id',
            'matc_descricao',
            'matc_qtoriginais',
            'matc_qtexemplares',
            'matc_mono',
            'matc_color',
            'matc_curso',
            'matc_centrocusto',
            'matc_unidade',
            'matc_solicitante',
            'matc_data',
            'situacao_id',
            'matc_qteCopias',
            'matc_qteTotal',
            'matc_totalValorMono',
            'matc_totalValorColor',
            'matc_autorizacao',
            'matc_dataAut',
        ],
    ]) ?>

</div>
