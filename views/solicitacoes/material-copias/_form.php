<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\money\MaskMoney;
use yii\widgets\MaskedInput;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\Json;
use kartik\depdrop\DepDrop;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-copias-form">

<?php $form = ActiveForm::begin(['options'=>['id' => 'dynamic-form', 'enctype'=>'multipart/form-data']]); ?>
<?= $form->errorSummary($model); ?>

<div class="row">
   <?php $segmentoList=ArrayHelper::map($segmento, 'seg_codsegmento', 'seg_descricao' );?>
   <?= $model->matc_tipo == 1 ? 
      '<div class="col-md-3">'.
         $form->field($model, 'matc_segmento')->widget(Select2::classname(), [
            'data' =>  $segmentoList,
            'options' => ['id' => 'cat-id','placeholder' => 'Selecione o Segmento...'],
            'pluginOptions' => [
               'allowClear' => true
            ],
         ])
      .'</div>'
      : '';
   ?>

   <?= $model->matc_tipo == 1 ? 
      '<div class="col-md-3">'.
         // Child # 1
         $form->field($model, 'matc_tipoacao')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'options'=>['id' => 'subcat-id'],
            'pluginOptions'=>[
               'depends'=>['cat-id'],
               'placeholder'=>'Selecione o Tipo de Ação...',
               'url'=>Url::to(['/planos/planodeacao/tipos'])
            ]
         ])
      .'</div>'
      : '';
   ?>

   <?= $model->matc_tipo == 1 ? 
      '<div class="col-md-4">'.
         // Child # 2
         $form->field($model, 'matc_curso')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
               'depends'=>['cat-id', 'subcat-id'],
               'placeholder'=>'Selecione o Curso...',
               'url'=>Url::to(['/solicitacoes/material-copias/cursos'])
            ]
         ])
      .'</div>'
      : 
      '<div class="col-md-10">'.
         $form->field($model, 'matc_curso')->textInput()->label('Descrição')
      .'</div>';
   ?>

   <?php $options = ArrayHelper::map($centrocusto, 'cen_centrocustoreduzido', 'cen_centrocustoreduzido');?>
   <?= $model->matc_tipo == 1 ? 
      '<div class="col-md-2">'.
         $form->field($model, 'matc_centrocusto')->widget(DepDrop::classname(), [
            'type'=>DepDrop::TYPE_SELECT2,
            'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
            'pluginOptions'=>[
               'depends'=>['cat-id', 'subcat-id'],
               'placeholder'=>'Selecione o Centro de Custo...',
               'url'=>Url::to(['/solicitacoes/material-copias/centrocusto'])
            ]
         ])
      .'</div>'
      : 
      '<div class="col-md-2">'.
         $form->field($model, 'matc_centrocusto')->widget(Select2::classname(), [
            'data' =>  $options,
            'options' => ['id' => 'cat-id','placeholder' => 'Selecione o Centro de Custo...'],
            'pluginOptions' => [
               'allowClear' => true
            ],
         ])
      .'</div>';
   ?>
</div>

    <?= $this->render('_form-itens', [
        'form' => $form,
        'model' => $model,
        'repositorio' => $repositorio,
        'acabamento'  => $acabamento,
        'modelsItens' => $modelsItens,
    ]) ?>

<!-- <div class="panel panel-primary">
   <div class="panel-heading">
     <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> DADOS FINANCEIROS</h3>
   </div>
   <div class="panel-body">
   <p>** Valores definidos em Reais (R$)</p>
      <div class="row">
         <div class="col-md-3">
            <?= $form->field($model, 'matc_totalValorMono')->widget(MaskedInput::className(),[
               'options' => ['readonly' => true, 'class' => 'form-control'],
                  'clientOptions' => [
                     'alias' => 'numeric',
                     'digits' => 2,
                  ],
               ])
            ?>
         </div>
         <div class="col-md-3">
            <?= $form->field($model, 'matc_totalValorColor')->widget(MaskedInput::className(),[
               'options' => ['readonly' => true, 'class' => 'form-control'],
               'clientOptions' => [
                     'alias' => 'numeric',
                     'digits' => 2,
                  ],
               ])
            ?>
         </div>
         <div class="col-md-3">
            <?= $form->field($model, 'matc_totalGeral')->widget(MaskedInput::className(),[
               'options' => ['readonly' => true, 'class' => 'form-control'],
                  'clientOptions' => [
                     'alias' => 'numeric',
                     'digits' => 2,
                  ],
               ])
            ?>
         </div>
      </div> 
   </div>
</div> -->

<div class="panel panel-primary">
   <div class="panel-heading">
      <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> SERVIÇOS DE ACABAMENTO</h3>
   </div>
   <div class="panel-body">
      <div class="row">
         <div class="col-md-12">
            <?php 
               $options = ArrayHelper::map($acabamento, 'id', 'acab_descricao');
               echo $form->field($model, 'listAcabamento')->checkboxList($options, ['unselect'=>NULL])->label(false);
            ?>
         </div>
      </div> 
    </div>
 </div>

<?= $form->field($model, 'matc_tipo')->hiddenInput(['readonly'=> true])->label(false) ?>

<div class="form-group">
   <?= Html::submitButton($model->isNewRecord ? 'Criar Solicitação' : 'Atualizar Solicitação', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
   <?php ActiveForm::end(); ?>
</div>
