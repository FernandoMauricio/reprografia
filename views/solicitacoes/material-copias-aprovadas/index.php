<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\solicitacoes\Situacao;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;


/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasAprovadasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//Pega as mensagens
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
echo '<div class="alert alert-'.$key.'">'.$message.'</div>';
}

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
                                'width'=>'50px',
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
                              'width'=>'5%'
                            ],

                            [
                              'attribute'=>'matc_centrocusto',
                              'width'=>'5%'
                            ],
                            
                            [
                              'attribute'=>'matc_unidade',
                              'value'=> 'unidade.uni_nomeabreviado',
                              'width'=>'20%'
                            ],

                            'matc_curso',

                            [
                                'attribute'=>'situacao_id', 
                                'vAlign'=>'middle',
                                'width'=>'250px',
                                'value'=>function ($model, $key, $index, $widget) { 
                                    return Html::a($model->situacao->sitmat_descricao);
                                },
                                'filterType'=>GridView::FILTER_SELECT2,
                                'filter'=>ArrayHelper::map(Situacao::find()->orderBy('sitmat_status')->asArray()->all(), 'sitmat_descricao', 'sitmat_descricao'), 
                                'filterInputOptions'=>['placeholder'=>'Situação'],
                                'format'=>'raw'
                            ],


                                ['class' => 'yii\grid\ActionColumn',
                                'template' => '{encaminharterceirizada} {producaointerna}',
                                'options' => ['width' => '25%'],
                                'buttons' => [

                                //ENCAMINHADO À TERCEIRIZADA
                                'encaminharterceirizada' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-share"></span> Terceirizada', $url, [
                                                'class' => 'btn btn-warning btn-xs',
                                                'title' => Yii::t('app', 'Encaminhado à Terceirizada'),
                                                'data'  => [
                                                    'confirm' => 'Você tem CERTEZA que deseja ENCAMINHAR À TERCEIRIZADA?',
                                                    'method' => 'post',
                                                     ],
                                                ]);
                                            },

                                //PRODUÇÃO INTERNA
                                'producaointerna' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-book"></span> Produção Interna', $url, [
                                                'class' => 'btn btn-info btn-xs',
                                                'title' => Yii::t('app', 'Reprovar Solicitação'),
                                                'data'  => [
                                                    'confirm' => 'Você tem CERTEZA que deseja ENCAMINHAR PARA PRODUÇÃO INTERNA?',
                                                    'method' => 'post',
                                                     ],
                                                ]);
                                            },
                                 ],
                            ],

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
                ['content'=>'Encaminhamentos', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
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

