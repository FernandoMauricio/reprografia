<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;
use app\models\solicitacoes\Situacao;
use app\models\cadastros\Centrocusto;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasAutGerenciaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Solicitações Pendentes';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="material-copias-aut-gerencia-index">
    <h1><?= Html::encode($this->title) . '<small> Autorização Gerencial</small>' ?></h1>

<?php

$gridColumns = [

    [
        'class'=>'kartik\grid\ExpandRowColumn',
        'width'=>'2%',
        'value'=>function ($model, $key, $index, $column) {
            return GridView::ROW_COLLAPSED;
        },
        'detail'=>function ($model, $key, $index, $column) {
            return Yii::$app->controller->renderPartial('/solicitacoes/material-copias/view_expand', ['model'=>$model]);
        },
        'headerOptions'=>['class'=>'kartik-sheet-style'],
        'expandOneOnly'=>true,
    ],

    [
      'attribute'=>'matc_id',
      'width'=>'3%'
    ],

    [
        'attribute'=>'matc_centrocusto', 
        'width'=>'5%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->matc_centrocusto;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Centrocusto::find()->where(['cen_codsituacao' => 1])->orderBy('cen_codano')->asArray()->all(), 'cen_centrocustoreduzido', 'cen_centrocustoreduzido'),
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>'Centro de Custo...'],
    ],

    [
        'attribute' => 'matc_dataPrevisao',
        'format' => ['date', 'php:d/m/Y'],
        'width' => '8%',
        'hAlign' => 'center',
        'filter'=> DatePicker::widget([
        'model' => $searchModel, 
        'attribute' => 'matc_dataPrevisao',
        'pluginOptions' => [
             'autoclose'=>true,
             'format' => 'yyyy-mm-dd',
            ]
        ])
    ],

    [
      'attribute'=>'matc_unidade',
      'value'=> 'unidade.uni_nomeabreviado',
      'width'=>'20%'
    ],

    [
        'attribute'=>'matc_curso', 
        'width'=>'30%',
    ],


    [
        'attribute'=>'situacao_id', 
        'width'=>'15%',
        'value'=>function ($model, $key, $index, $widget) { 
            return $model->situacao->sitmat_descricao;
        },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=>ArrayHelper::map(Situacao::find()->orderBy('sitmat_status')->asArray()->all(), 'sitmat_id', 'sitmat_descricao'),
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
        'filterInputOptions'=>['placeholder'=>'Situação...'],
    ],


    ['class' => 'yii\grid\ActionColumn',
    'template' => '{aprovar} {reprovar}',
    'options' => ['width' => '15%'],
    'buttons' => [

    //APROVAR REQUISIÇÃO
    'aprovar' => function ($url, $model) {
        return Html::a('<span class="glyphicon glyphicon-ok"></span> Aprovar', $url, [
                    'class' => 'btn btn-success btn-xs',
                    'title' => Yii::t('app', 'Aprovar Solicitação'),
                    'data'  => [
                        'confirm' => 'Você tem CERTEZA que deseja APROVAR a solicitação?',
                        'method' => 'post',
                         ],
                    ]);
                },

    //REPROVAR REQUISIÇÃO
    'reprovar' => function ($url, $model) {
        return Html::a('<span class="glyphicon glyphicon-remove"></span> Reprovar', $url, [
                    'class' => 'btn btn-danger btn-xs',
                    'title' => Yii::t('app', 'Reprovar Solicitação'),
                    ]);
                },

    ],
    ],

]; 
?>

    <?php Pjax::begin(['id'=>'w0-pjax']); ?>

    <?php 

    echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'columns'=>$gridColumns,
    'containerOptions'=>['style'=>'overflow: auto'], // only set when $responsive = false
    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
    'pjax'=>true, // pjax is set to always true for this demo
    'condensed' => true,
    'hover' => true,
    'beforeHeader'=>[
        [
            'columns'=>[
                ['content'=>'Detalhes das Solicitações de Cópias', 'options'=>['colspan'=>6, 'class'=>'text-center warning']], 
                ['content'=>'Ações', 'options'=>['colspan'=>3, 'class'=>'text-center warning']], 
            ],
        ]
    ],

        'panel' => [
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Listagem de Solicitações Pendentes</h3>',
    ],
]);
    ?>
    <?php Pjax::end(); ?>

</div>

