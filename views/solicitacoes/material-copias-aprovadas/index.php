<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\solicitacoes\Situacao;
use app\models\cadastros\Centrocusto;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasAprovadasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Solicitações de Cópias Aprovadas';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="material-copias-aprovadas-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
        'width'=>'3%',
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
      'attribute'=>'matc_unidade',
      'value'=> 'unidade.uni_nomeabreviado',
      'width'=>'25%'
    ],

    [
        'attribute'=>'matc_curso', 
        'width'=>'25%',
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


    // ['class' => 'yii\grid\ActionColumn',
    // 'template' => '{encaminharterceirizada} {producaointerna}',
    // 'options' => ['width' => '15%'],
    // 'buttons' => [

    // //ENCAMINHADO À TERCEIRIZADA
    // 'encaminharterceirizada' => function ($url, $model) {
    //     return Html::a('<span class="glyphicon glyphicon-share"></span> Terceirizada', $url, [
    //                 'class' => 'btn btn-warning btn-xs',
    //                 'title' => Yii::t('app', 'Encaminhar à Terceirizada'),
    //                 'data'  => [
    //                     'confirm' => 'Você tem CERTEZA que deseja ENCAMINHAR À TERCEIRIZADA?',
    //                     'method' => 'post',
    //                      ],
    //                 ]);
    //             },

    // //PRODUÇÃO INTERNA
    // 'producaointerna' => function ($url, $model) {
    //     return Html::a('<span class="glyphicon glyphicon-book"></span> Produção Interna', $url, [
    //                 'class' => 'btn btn-info btn-xs',
    //                 'title' => Yii::t('app', 'Reprovar Solicitação'),
    //                 'data'  => [
    //                     'confirm' => 'Você tem CERTEZA que deseja ENCAMINHAR PARA PRODUÇÃO INTERNA?',
    //                     'method' => 'post',
    //                      ],
    //                 ]);
    //             },
    // ],
    // ],

    ['class' => 'yii\grid\ActionColumn',
    'template' => '{finalizar}',
    'options' => ['width' => '10%'],
    'buttons' => [

    //ENCAMINHADO À TERCEIRIZADA
    'finalizar' => function ($url, $model) {
        return Html::a('<span class="glyphicon glyphicon-floppy-disk"></span> Finalizar', $url, [
                    'class' => 'btn btn-success btn-xs',
                    'title' => Yii::t('app', 'Finalizar Solicitação'),
                    'data'  => [
                        'confirm' => 'Você tem CERTEZA que deseja FINALIZAR A SOLICITAÇÃO?',
                        'method' => 'post',
                         ],
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
    'rowOptions' =>function($model){
                    if($model->situacao_id == 3 ){

                            return['class'=>'danger'];                        
                    } if($model->situacao_id == 2 ){

                            return['class'=>'success'];                        
                    }
                    if($model->situacao_id == 4 ){

                            return['class'=>'warning'];                        
                    }
                    if($model->situacao_id == 5 ){

                            return['class'=>'info'];                        
                    }

        },
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
                //['content'=>'Encaminhamentos', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
                ['content'=>'Ações', 'options'=>['colspan'=>2, 'class'=>'text-center warning']], 
            ],
        ]
    ],

        'panel' => [
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Listagem de Solicitações Aprovadas pela DEP</h3>',
    ],
]);
    ?>
    <?php Pjax::end(); ?>

</div>

