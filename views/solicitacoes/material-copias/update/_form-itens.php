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

      <div class="col-sm-2"><?= $form->field($modelItens, "[{$i}]item_qtoriginais")->textInput(['readonly'=> true]) ?> </div>

      <div class="col-sm-2">
      <?= $form->field($modelItens, "[{$i}]item_qtexemplares")->textInput([
         'onchange'=>"
            $(function() {
               $('#materialcopiasitens-0-item_qtoriginais').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-0-item_qtexemplares').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-0-item_mono').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-0-item_color').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-1-item_qtoriginais').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-1-item_qtexemplares').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-1-item_mono').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-1-item_color').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-2-item_qtoriginais').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-2-item_qtexemplares').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-2-item_mono').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-2-item_color').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-3-item_qtoriginais').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-3-item_qtexemplares').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-3-item_mono').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopiasitens-3-item_color').keyup(function() {  
                   updateTotal();
               });


               $('#materialcopias-matc_totalvalormono').keyup(function() {  
                   updateTotal();
               });

               $('#materialcopias-matc_totalvalorcolor').keyup(function() {  
                   updateTotal();
               });

               var updateTotal = function () {

                 var item_qtoriginais  = parseInt($('#materialcopiasitens-0-item_qtoriginais').val());
                 var item_qtexemplares = parseInt($('#materialcopiasitens-0-item_qtexemplares').val());
                 var item_mono         = parseInt($('#materialcopiasitens-0-item_mono').val());
                 var item_color        = parseInt($('#materialcopiasitens-0-item_color').val());

                 var item_qtoriginais1  = parseInt($('#materialcopiasitens-1-item_qtoriginais').val());
                 var item_qtexemplares1 = parseInt($('#materialcopiasitens-1-item_qtexemplares').val());
                 var item_mono1         = parseInt($('#materialcopiasitens-1-item_mono').val());
                 var item_color1        = parseInt($('#materialcopiasitens-1-item_color').val());

                 var item_qtoriginais2  = parseInt($('#materialcopiasitens-2-item_qtoriginais').val());
                 var item_qtexemplares2 = parseInt($('#materialcopiasitens-2-item_qtexemplares').val());
                 var item_mono2         = parseInt($('#materialcopiasitens-2-item_mono').val());
                 var item_color2        = parseInt($('#materialcopiasitens-2-item_color').val());

                 var item_qtoriginais3  = parseInt($('#materialcopiasitens-3-item_qtoriginais').val());
                 var item_qtexemplares3 = parseInt($('#materialcopiasitens-3-item_qtexemplares').val());
                 var item_mono3         = parseInt($('#materialcopiasitens-3-item_mono').val());
                 var item_color3        = parseInt($('#materialcopiasitens-3-item_color').val());

                 var item_qteCopias = item_qtoriginais * item_qtexemplares;
                 var item_qteTotal  = (item_mono + item_color) * item_qtexemplares;

                 var item_qteCopias1 = item_qtoriginais1 * item_qtexemplares1;
                 var item_qteTotal1  = (item_mono1 + item_color1) * item_qtexemplares1;

                 var item_qteCopias2 = item_qtoriginais2 * item_qtexemplares2;
                 var item_qteTotal2  = (item_mono2 + item_color2) * item_qtexemplares2;

                 var item_qteCopias3 = item_qtoriginais3 * item_qtexemplares3;
                 var item_qteTotal3  = (item_mono3 + item_color3) * item_qtexemplares3;

               if (isNaN(item_qteCopias) || item_qteCopias < 0) {
                   item_qteCopias = '';
               }

               if (isNaN(item_qteTotal) || item_qteTotal < 0) {
                   item_qteTotal = '';
               }

               if (isNaN(item_qteCopias1) || item_qteCopias1 < 0) {
                   item_qteCopias1 = '';
               }

               if (isNaN(item_qteTotal1) || item_qteTotal1 < 0) {
                   item_qteTotal1 = '';
               }

               if (isNaN(item_qteCopias2) || item_qteCopias2 < 0) {
                   item_qteCopias2 = '';
               }

               if (isNaN(item_qteTotal2) || item_qteTotal2 < 0) {
                   item_qteTotal2 = '';
               }

               if (isNaN(item_qteCopias3) || item_qteCopias3 < 0) {
                   item_qteCopias3 = '';
               }

               if (isNaN(item_qteTotal3) || item_qteTotal3 < 0) {
                   item_qteTotal3 = '';
               }

                 $('#materialcopiasitens-0-item_qtecopias').val(item_qteCopias);
                 $('#materialcopiasitens-0-item_qtetotal').val(item_qteTotal);

                 $('#materialcopiasitens-1-item_qtecopias').val(item_qteCopias1);
                 $('#materialcopiasitens-1-item_qtetotal').val(item_qteTotal1);

                 $('#materialcopiasitens-2-item_qtecopias').val(item_qteCopias2);
                 $('#materialcopiasitens-2-item_qtetotal').val(item_qteTotal2);

                 $('#materialcopiasitens-3-item_qtecopias').val(item_qteCopias3);
                 $('#materialcopiasitens-3-item_qtetotal').val(item_qteTotal3);


                 //totais
                      var mono = 0.12;
                      var color = 0.6;

                      //mono
                      var item_totalValorMono  = (item_qtexemplares  * item_mono) * mono;
                      var item_totalValorMono1 = (item_qtexemplares1 * item_mono1) * mono;
                      var item_totalValorMono2 = (item_qtexemplares2 * item_mono2) * mono;
                      var item_totalValorMono3 = (item_qtexemplares3 * item_mono3) * mono;

                      //color
                      var item_totalValorColor  = (item_qtexemplares  * item_color) * color;
                      var item_totalValorColor1 = (item_qtexemplares1 * item_color1) * color;
                      var item_totalValorColor2 = (item_qtexemplares2 * item_color2) * color;
                      var item_totalValorColor3 = (item_qtexemplares3 * item_color3) * color;

                      //Geral
                      var item_totalGeral  = item_totalValorMono + item_totalValorColor;
                      var item_totalGeral1 = item_totalGeral + item_totalValorMono1 + item_totalValorColor1;
                      var item_totalGeral2 = item_totalGeral + item_totalValorMono1 + item_totalValorColor1 + item_totalValorMono2 + item_totalValorColor2;
                      var item_totalGeral3 = item_totalGeral + item_totalGeral1 + item_totalValorMono2 + item_totalValorColor2 + item_totalValorMono3 + item_totalValorColor3;

                    
                      //valores output mono
                     if(item_totalValorMono >= 0){
                      $('#materialcopias-matc_totalvalormono').val(item_totalValorMono)
                     }

                     if(item_totalValorMono1 >= 0){
                      $('#materialcopias-matc_totalvalormono').val(item_totalValorMono + item_totalValorMono1)
                     }

                     if(item_totalValorMono2 >= 0){
                      $('#materialcopias-matc_totalvalormono').val(item_totalValorMono + item_totalValorMono1 + item_totalValorMono2)
                     }

                     if(item_totalValorMono3 >= 0){
                      $('#materialcopias-matc_totalvalormono').val(item_totalValorMono + item_totalValorMono1 + item_totalValorMono2 + item_totalValorMono3)
                     }


                     //valores output color
                     if(item_totalValorColor >= 0){
                      $('#materialcopias-matc_totalvalorcolor').val(item_totalValorColor)
                     }

                     if(item_totalValorColor1 >= 0){
                      $('#materialcopias-matc_totalvalorcolor').val(item_totalValorColor + item_totalValorColor1)
                     }

                     if(item_totalValorColor2 >= 0){
                      $('#materialcopias-matc_totalvalorcolor').val(item_totalValorColor + item_totalValorColor1 + item_totalValorColor2)
                     }

                     if(item_totalValorColor3 >= 0){
                      $('#materialcopias-matc_totalvalorcolor').val(item_totalValorColor + item_totalValorColor1 + item_totalValorColor2 + item_totalValorColor3)
                     }


                     //valores output Geral
                     if(item_totalGeral >= 0){
                      $('#materialcopias-matc_totalgeral').val(item_totalGeral)
                     }

                     if(item_totalValorColor1 >= 0){
                      $('#materialcopias-matc_totalgeral').val(item_totalGeral1)
                     }

                     if(item_totalValorColor2 >= 0){
                      $('#materialcopias-matc_totalgeral').val(item_totalGeral2)
                     }

                     if(item_totalValorColor3 >= 0){
                      $('#materialcopias-matc_totalgeral').val(item_totalGeral3)
                     }
                  };
              });
            "
            ]);
         ?> 
         </div>

         <div class="col-sm-2"><?= $form->field($modelItens, "[{$i}]item_qteCopias")->textInput(['readonly'=> true]) ?></div>
      </div>

      <div class="row">
         <div class="col-sm-4"><?= $form->field($modelItens, "[{$i}]item_mono")->textInput() ?></div>
             
         <div class="col-sm-4"><?= $form->field($modelItens, "[{$i}]item_color")->textInput() ?></div>
                
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
<?php DynamicFormWidget::end(); ?>
