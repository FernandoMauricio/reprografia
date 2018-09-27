<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use app\models\solicitacoes\Situacao;
use app\models\cadastros\Centrocusto;
use app\models\solicitacoes\Acabamento;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\solicitacoes\MaterialCopiasSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$session = Yii::$app->session;
$unidade = $session['sess_unidade'];
$this->title = 'Solicitações de Cópias';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="material-copias-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Nova Solicitação', ['value'=> Url::to(['gerar-requisicao']), 'class' => 'btn btn-success', 'id'=>'modalButton']) ?>
    </p>

<?php
    Modal::begin([
        'options' => ['tabindex' => false ], // important for Select2 to work properly
        'clientOptions' => ['backdrop' => 'static', 'keyboard' => true],
        'header' => '<h4>Nova Solicitação de Cópias</h4>',
        'id' => 'modal',
        'size' => 'modal-lg',
        ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
?>

<?php Modal::begin([
    'id' => 'activity-modal',
    'header' => '<h4>Recebimento da Requisição</h4>',
    'clientOptions' => ['backdrop' => 'static', 'keyboard' => true],
]);
?>
<div class="well"></div>

<?php Modal::end(); ?>

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
        'attribute'=>'matc_tipo',
        'width'=>'3%',
        'value' => function ($data) { return $data->matc_tipo == 1 ? 'Apostilas' : 'Impressões'; },
        'filterType'=>GridView::FILTER_SELECT2,
        'filter'=> [1=>'Apostilas',2=>'Impressões'],
        'filterWidgetOptions'=>[
            'pluginOptions'=>['allowClear'=>true],
        ],
            'filterInputOptions'=>['placeholder'=>'Tipo de Serviço'],
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
        'attribute'=>'matc_curso', 
        'width'=>'30%',
    ],

    [
        'attribute'=>'matc_totalGeral',
        'width'=>'5%',
        'value' => function ($model) {
            return 'R$ ' . number_format($model->matc_totalGeral,2, ',', '.');
        },
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
    'template' => '{view} {update} {observacoes} {encaminharterceirizada} {receber-requisicao}',
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

    //ENCAMINHADO À TERCEIRIZADA
    'encaminharterceirizada' => function ($url, $model) {
        if($model->situacao_id == 2) {
        return Html::a('<span class="glyphicon glyphicon-share"></span> Terceirizada', $url, [
                    'class' => 'btn btn-warning btn-xs',
                    'title' => Yii::t('app', 'Encaminhar à Terceirizada'),
                    'data'  => [
                        'confirm' => 'Você tem CERTEZA que deseja ENCAMINHAR À TERCEIRIZADA?',
                        'method' => 'post',
                         ],
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

    //RECEBIDO
    'receber-requisicao' => function ($url, $model, $key) {
        return  $model->situacao_id == 6 ? Html::a('<span class="glyphicon glyphicon-ok"></span> Pedido Recebido', $url, [
            //'class' => 'btn btn-success btn-xs',
            'title' => Yii::t('app', 'Recebido Pelo Solicitante'),
            'class' => 'receber-requisicao',
            'data-toggle' => 'modal',
            'data-target' => '#activity-modal',
            'data-id' => $key,
            'data-pjax' => '0',
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
                ['content'=>'Detalhes das Solicitações de Cópias', 'options'=>['colspan'=>7, 'class'=>'text-center warning']], 
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

<?php $this->registerJs(
"$('.receber-requisicao').click(function() {
    $.get(
    'index.php?r=solicitacoes/material-copias/receber-requisicao',
        {
            id: $(this).closest('tr').data('key')
        },

        function (data) {
            $('.modal-body').html(data);
            $('#activity-modal').modal();
        }  
    );
});
"
); ?>

</div>