<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\solicitacoes\Situacao;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
$unidade = $session['sess_unidade'];


//Pega as mensagens
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
echo '<div class="alert alert-'.$key.'">'.$message.'</div>';
}

$this->title = 'Solicitações de Cópias';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-copias-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Nova Solicitação', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

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
                            'template' => '{view} {update} {observacoes}',
                            'options' => ['width' => '10%'],
                            'buttons' => [

                            //VIEW BUTTON
                            'view' => function ($url, $model) {
                                return Html::a('<span class="glyphicon glyphicon-eye-open"></span> ', $url, [
                                    'title' => Yii::t('app', 'Visualizar'),        
                                    ]);
                                },

                            //UPDATE BUTTON 3 = Reprovado pela DEP || 8 = Reprovado pelo gerente do setor
                            'update' => function ($url, $model) {
                                if($model->situacao_id == 3) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span> ', $url, [
                                    'title' => Yii::t('app', 'Atualizar'),        
                                    ]);
                                }if($model->situacao_id == 8) {
                                return Html::a('<span class="glyphicon glyphicon-pencil"></span> ', $url, [
                                    'title' => Yii::t('app', 'Atualizar'),        
                                    ]);
                                }else{
                                    '';
                                    }
                                },

                            //JUSTIFICATIVA PARA A REPROVAÇÃO DA DEP
                            'observacoes' => function ($url, $model) {
                                return  $model->situacao_id == 3 ? Html::a('<span class="glyphicon glyphicon-info-sign"></span>', $url, [
                                    'title' => Yii::t('app', 'Observações'),
                                    ]): '';
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
                    } 
                    if($model->situacao_id == 8 ){

                            return['class'=>'danger'];                        
                    } 
                    if($model->situacao_id == 2 ){

                            return['class'=>'success'];                        
                    }
                    if($model->situacao_id == 7 ){

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
                ['content'=>'Detalhes das Solicitações de Cópias', 'options'=>['colspan'=>5, 'class'=>'text-center warning']], 
                ['content'=>'Ações', 'options'=>['colspan'=>1, 'class'=>'text-center warning']], 
            ],
        ]
    ],

        'panel' => [
        'type'=>GridView::TYPE_PRIMARY,
        'heading'=> '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Listagem - '.utf8_encode($unidade).'</h3>',
    ],
]);
    ?>
    <?php Pjax::end(); ?>

</div>

