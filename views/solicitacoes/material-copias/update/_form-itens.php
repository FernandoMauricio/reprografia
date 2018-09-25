<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Json;
use yii\helpers\Url;

use wbraganca\dynamicform\DynamicFormWidget;

?>

<?php
$js = '
jQuery(".dynamicform_copia").on("afterInsert", function(e, item) {
    jQuery(".dynamicform_copia .panel-title-copia").each(function(i) {
        jQuery(this).html("Item: " + (i + 1))
    });
});

jQuery(".dynamicform_copia").on("afterDelete", function(e) {
    jQuery(".dynamicform_copia .panel-title-copia").each(function(i) {
        jQuery(this).html("Item: " + (i + 1))
    });
});

';
$this->registerJs($js);
?>

<?php DynamicFormWidget::begin([
   'widgetContainer' => 'dynamicform_copia', // required: only alphanumeric characters plus "_" [A-Za-z0-9_]
   'widgetBody' => '.container-items-copia', // required: css class selector
   'widgetItem' => '.item-copia', // required: css class
   'limit' => 4, // the maximum times, an element can be cloned (default 999)
   'min' => 1, // 0 or 1 (default 1)
   'insertButton' => '.add-item-copia', // css class
   'deleteButton' => '.remove-item-copia', // css class
   'model' => $modelsItens[0],
   'formId' => 'dynamic-form',
   'formFields' => [
       'id',
       'item_descricao',
       'item_qtoriginais',
       'item_qtexemplares',
       'item_qteCopias',
       'item_mono',
       'item_color',
       'item_qteTotal',
       'item_observacao',
   ],
]); ?>


<div class="panel panel-default">
   <div class="panel-heading">
      <i class="glyphicon glyphicon-list-alt"></i> Listagem de Cópias
      <button type="button" class="pull-right add-item-copia btn btn-success btn-xs"><i class="glyphicon glyphicon-plus"></i> Adicionar Item</button>
   <div class="clearfix"></div>
   </div>
   <div class="panel-body container-items-copia"><!-- widgetContainer -->
   <?php foreach ($modelsItens as $i => $modelItens): ?>
   <div class="item-copia panel panel-default"><!-- widgetBody -->
      <div class="panel-heading">
          <span class="panel-title-copia">Item: <?= ($i + 1) ?></span>
          <button type="button" class="pull-right remove-item-copia btn btn-danger btn-xs"><i class="glyphicon glyphicon-minus"></i></button>
          <div class="clearfix"></div>
      </div>
      <div class="panel-body">
      <?php
          // necessary for update action.
          if (!$modelItens->isNewRecord) {
              echo Html::activeHiddenInput($modelItens, "[{$i}]id");
          }
      ?>
   <div class="row">
      <div class="col-sm-6">
         <?php
            $data_repositorio = ArrayHelper::map($repositorio, 'rep_titulo', 'rep_titulo');
            echo $form->field($modelItens, "[{$i}]item_descricao")->widget(Select2::classname(), [
               'data' =>  $data_repositorio,
               'options' => ['placeholder' => 'Selecione o Material...',
               'onchange'=>'
                       var select = this;
                       $.getJSON( "'.Url::toRoute('/solicitacoes/material-copias/get-repositorio').'", { repId: $(this).val() } )
                       .done(function( data ) {

                              var $divPanelBody =  $(select).parent().parent().parent();

                              var $inputTitulo = $divPanelBody.find("input:eq(0)");

                              $inputTitulo.val(data.rep_qtdoriginais) ;
                              
                           });
                       '
               ]]);
         ?> 
      </div>

      <div class="col-sm-2"><?= $form->field($modelItens, "[{$i}]item_qtoriginais")->textInput(['onkeyup' => 'totais($(this))', 'readonly'=> $model->matc_tipo == 1 ? true : false]) ?></div>

      <div class="col-sm-2"><?= $form->field($modelItens, "[{$i}]item_qtexemplares")->textInput(['onkeyup' => 'totais($(this))']);?></div>
        
      <div class="col-sm-2"><?= $form->field($modelItens, "[{$i}]item_qteCopias")->textInput(['readonly'=> true]) ?></div>
   </div>

      <div class="row">
         <div class="col-sm-4"><?= $form->field($modelItens, "[{$i}]item_mono")->textInput(['onkeyup' => 'totais($(this))']) ?></div>
             
         <div class="col-sm-4"><?= $form->field($modelItens, "[{$i}]item_color")->textInput(['onkeyup' => 'totais($(this))']) ?></div>
                
         <div class="col-sm-4"><?= $form->field($modelItens, "[{$i}]item_qteTotal")->textInput(['readonly'=>true]) ?></div>
      </div>


      <div class="row">
         <?= $model->matc_tipo == 1 ? 
            '<div class="col-md-12">'.$form->field($modelItens, "[{$i}]item_arquivo")->hiddenInput(['readonly'=> true])->label(false).'</div>'
            : 
            '<div class="col-md-12">'.
               $form->field($modelItens, "[{$i}]file")->widget(FileInput::classname(), [
                   'pluginOptions' => [
                       'language' => 'pt-BR',
                       'showRemove'=> false,
                       'showUpload'=> false,
                       'dropZoneEnabled' => false,
                   ],
               ])
            .'</div>';
         ?>
      </div>

      <?= $form->field($modelItens, "[{$i}]item_codrepositorio")->hiddenInput(['readonly'=> true])->label(false) ?>

      </div>
      </div>
      <?php endforeach; ?>
   </div>
</div>
<script type="text/javascript">
   function totais(item){
      var mono = 0.12; //Valor padrão mono
      var color = 0.95; //Valor padrão color
      var total = 0;
      var i = item.attr("id").replace(/[^0-9.]/g, "");   
      var item_qtexemplares = parseFloat($('#materialcopiasitens-' + i + '-item_qtexemplares').val());
      var item_qtoriginais = parseFloat($('#materialcopiasitens-' + i + '-item_qtoriginais').val());
      var item_mono = parseFloat($('#materialcopiasitens-' + i + '-item_mono').val());
      var item_color = parseFloat($('#materialcopiasitens-' + i + '-item_color').val());

      // horas = horas == "" ? 0 : Number(horas.split(",").join(""));
      // var valorhora = $('#modelOptionValue-' + i + '-valorhora').val();
      // valorhora = valorhora == "" ? 0 : Number(valorhora.split(",").join(""));

      $('#materialcopiasitens-' + i + '-item_qtecopias').val(item_qtoriginais * item_qtexemplares);
      $('#materialcopiasitens-' + i + '-item_qtetotal').val((item_mono + item_color) * item_qtexemplares);
      $('#materialcopiasitens-' + i + '-totalmono').val((item_qtexemplares  * item_mono) * mono);
   }
</script>

<?php DynamicFormWidget::end(); ?>
