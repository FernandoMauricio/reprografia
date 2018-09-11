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

//Pega as mensagens
foreach (Yii::$app->session->getAllFlashes() as $key => $message) {
echo '<div class="alert alert-'.$key.'">'.$message.'</div>';
}

?>

<div class="material-copias-form">

    <?php $form = ActiveForm::begin(['id' => 'dynamic-form']); ?>

<div class="row">

    <div class="col-md-3">

        <?php
        $segmentoList=ArrayHelper::map(app\models\cadastros\Segmento::find()->all(), 'seg_codsegmento', 'seg_descricao' );
                    echo $form->field($model, 'matc_segmento')->widget(Select2::classname(), [
                            'data' =>  $segmentoList,
                           'options' => ['id' => 'cat-id','placeholder' => 'Selecione o Segmento...'],
                            'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
        ?>
    </div>

    <div class="col-md-3">

          <?php
              // Child # 1
              echo $form->field($model, 'matc_tipoacao')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'options'=>['id' => 'subcat-id'],
                  'pluginOptions'=>[
                      'depends'=>['cat-id'],
                      'placeholder'=>'Selecione o Tipo de Ação...',
                      //'initialize' => true,
                      'url'=>Url::to(['/planos/planodeacao/tipos'])
                  ]
              ]);
          ?>
    </div>

    <div class="col-md-4">

          <?php
              // Child # 2
              echo $form->field($model, 'matc_curso')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'pluginOptions'=>[
                      'depends'=>['cat-id', 'subcat-id'],
                      'placeholder'=>'Selecione o Curso...',
                      //'initialize' => true,
                      'url'=>Url::to(['/solicitacoes/material-copias/cursos'])
                  ]
              ]);
          ?>
    </div>

    <div class="col-md-2">

          <?php
              // Child # 2
              echo $form->field($model, 'matc_centrocusto')->widget(DepDrop::classname(), [
                  'type'=>DepDrop::TYPE_SELECT2,
                  'select2Options'=>['pluginOptions'=>['allowClear'=>true]],
                  'pluginOptions'=>[
                      'depends'=>['cat-id', 'subcat-id'],
                      'placeholder'=>'Selecione o Centro de Custo...',
                      //'initialize' => true,
                      'url'=>Url::to(['/solicitacoes/material-copias/centrocusto'])
                  ]
              ]);
          ?>
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



<?php
// $script = <<<EOD
// $(function() {
//      $('#materialcopias-matc_qtoriginais').keyup(function() {  
//         updateTotal();
//     });

//     $('#materialcopias-matc_qtexemplares').keyup(function() {  
//         updateTotal();
//     });

//     $('#materialcopias-matc_mono').keyup(function() {  
//         updateTotal();
//     });

//     $('#materialcopias-matc_color').keyup(function() {  
//         updateTotal();
//     });

//     var updateTotal = function () {
//       var matc_qtoriginais  = parseInt($('#materialcopias-matc_qtoriginais').val());
//       var matc_qtexemplares = parseInt($('#materialcopias-matc_qtexemplares').val());
//       var matc_mono         = parseInt($('#materialcopias-matc_mono').val());
//       var matc_color        = parseInt($('#materialcopias-matc_color').val());

//       var matc_qteCopias = matc_qtoriginais * matc_qtexemplares;
//       var matc_qteTotal  = (matc_mono + matc_color) * matc_qtexemplares ;

//       var mono = 0.1;
//       var color = 0.6;

//       var matc_totalValorMono = (matc_qtexemplares * matc_mono) * mono;
//       var matc_totalValorColor = (matc_qtexemplares * matc_color) * color;
//       var matc_totalGeral = matc_totalValorMono + matc_totalValorColor;

//     if (isNaN(matc_qteCopias) || matc_qteCopias < 0) {
//         matc_qteCopias = '';
//     }

//     if (isNaN(matc_qteTotal) || matc_qteTotal < 0) {
//         matc_qteTotal = '';
//     }

//     if (isNaN(matc_totalValorMono) || matc_totalValorMono < 0) {
//         matc_totalValorMono = '';
//     }

//     if (isNaN(matc_totalValorColor) || matc_totalValorColor < 0) {
//         matc_totalValorColor = '';
//     }
//       $('#materialcopias-matc_qtecopias').val(matc_qteCopias);
//       $('#materialcopias-matc_qtetotal').val(matc_qteTotal);

//       $('#materialcopias-matc_totalvalormono').val(matc_totalValorMono);
//       $('#materialcopias-matc_totalvalorcolor').val(matc_totalValorColor);
//       $('#materialcopias-matc_totalgeral').val(matc_totalGeral);
//     };
//  });
// EOD;
// $this->registerJs($script, yii\web\View::POS_END);      
?>