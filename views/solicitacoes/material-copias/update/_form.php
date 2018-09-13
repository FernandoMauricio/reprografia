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
use app\models\cadastros\Segmento;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopias */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-copias-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

<div class="row">

    <div class="col-md-3">

    <?= $form->field($model, 'segmentoLabel')->textInput(['value'=> $model->segmento->seg_descricao,'readonly'=>true]) ?>

    </div>

    <div class="col-md-3">

    <?= $form->field($model, 'tipoLabel')->textInput(['value'=> $model->tipo->tip_descricao,'readonly'=>true]) ?>
          
    </div>

    <div class="col-md-4">

    <?= $form->field($model, 'matc_curso')->textInput(['value'=> $model->matc_curso,'readonly'=>true]) ?>

    </div>

    <div class="col-md-2">

    <?= $form->field($model, 'matc_centrocusto')->textInput(['value'=> $model->matc_centrocusto,'readonly'=>true]) ?>

    </div>

 </div>

    <?= $this->render('_form-itens', [
        'form' => $form,
        'repositorio' => $repositorio,
        'acabamento'  => $acabamento,
        'modelsItens' => $modelsItens,
    ]) ?>
    

 </div>

    <div class="panel panel-primary">
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
                                     'digitsOptional' => false,
                                     'radixPoint' => '.',
                                     'groupSeparator' => ',',
                                     'autoGroup' => true,
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
                                     'digitsOptional' => false,
                                     'radixPoint' => '.',
                                     'groupSeparator' => ',',
                                     'autoGroup' => true,
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
                                     'digitsOptional' => false,
                                     'radixPoint' => '.',
                                     'groupSeparator' => ',',
                                     'autoGroup' => true,
                                ],
                  ])
                ?>
                </div>
            </div> 
        </div>
     </div>

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

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Criar Solicitação' : 'Atualizar Solicitação', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>