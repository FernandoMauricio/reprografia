<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use app\models\solicitacoes\Acabamento;
use app\models\solicitacoes\MaterialCopiasItens;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopias */
$this->title = 'Solicitação de Cópia: ' . $model->matc_id;
$this->params['breadcrumbs'][] = ['label' => 'Solicitações de Cópias', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="material-copias-view">
   <h2><?= Html::encode($this->title) ?> <small><span class="label-material-copias-view"><?= $model->matc_tipo == 1 ? 'Requisição de Apostilas' : 'Requisição de Impressão'; ?></span></small></h2>

   <p><?= Html::a('<span class="glyphicon glyphicon-arrow-left"></span> Retornar', ['index'], ['class' => 'btn btn-default']) ?></p>

   <p>
      <span class="pull-left"><b>Data da Solicitação: </b><small><span class="label label-primary" style="font-size: 100%;font-weight:normal"><?= date('d/m/Y', strtotime($model->matc_data)); ?></span></small></span>
      <span class="pull-right"><b>Situação: </b><small><span class="label label-warning" style="font-size: 100%;font-weight:normal"><?= $model->situacao->sitmat_descricao; ?></span></small></span>
   </p><br /><br />

<div class="panel panel-primary">
   <div class="panel-heading">
      <h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> DETALHES DA SOLICITAÇÃO DE CÓPIA</h3>
   </div>
   <div class="panel-body">
      <div class="row">
      <?php
         $attributes = [
         //-------------- SESSÃO 1 INFORMAÇÕES DA SOLICITAÇÃO
         [
             'group'=>true,
             'label'=>'SEÇÃO 1: Informações da Solicitação',
             'rowOptions'=>['class'=>'info']
         ],

         [
             'columns' => [
                 [
                   'attribute'=>'matc_solicitante', 
                   'displayOnly'=>true,
                   'value'=> $model->colaborador->usuario->usu_nomeusuario,
                   'valueColOptions'=>['style'=>'width:30%'],
                   'labelColOptions'=>['style'=>'width:12%'],
                 ],

                 [
                   'attribute'=>'matc_unidade', 
                   'displayOnly'=>true,
                   'value'=> $model->unidade->uni_nomeabreviado,
                   'labelColOptions'=>['style'=>'width:12%'],
                 ],
             ],
         ],

         [
             'columns' => [
                 [
                   'attribute'=>'matc_segmento', 
                   'displayOnly'=>true,
                   'value'=> !empty($model->segmento->seg_descricao) ? $model->segmento->seg_descricao : '',
                   'visible' => (!empty($model->matc_segmento)),
                   'valueColOptions'=>['style'=>'width:30%'],
                   'labelColOptions'=>['style'=>'width:12%'],
                 ],

                 [
                   'attribute'=>'matc_tipoacao', 
                   'displayOnly'=>true,
                   'value'=> !empty($model->tipo->tip_descricao) ? $model->tipo->tip_descricao : '',
                   'visible' => (!empty($model->matc_tipoacao)),
                   'labelColOptions'=>['style'=>'width:12%'],
                 ],
             ],
          ],

         [
             'columns' => [

                 [
                   'attribute'=>'matc_curso', 
                   'displayOnly'=>true,
                   'valueColOptions'=>['style'=>'width:30%'],
                   'labelColOptions'=>['style'=>'width:12%'],
                 ],

                 [
                   'attribute'=>'matc_centrocusto', 
                   'displayOnly'=>true,
                   'labelColOptions'=>['style'=>'width:12%'],
                 ],
             ],
         ],
      ];
   echo DetailView::widget([
      'model'=>$model,
      'condensed'=>true,
      'hover'=>true,
      'mode'=>DetailView::MODE_VIEW,
      'attributes'=> $attributes,
   ]);
?>
                  <!-- SEÇÃO 2 INFORMAÇÕES DAS IMPRESSÕES -->
   <table class="table table-condensed table-hover">
      <thead>
         <tr class="info"><th colspan="12">SEÇÃO 2: Informações das Impressões</th></tr>
      </thead>
      <thead>
         <tr>
            <th>Material</th>
            <th>Qte Originais</th>
            <th>Qte Exempalres</th>
            <th>Qte Cópias</th>
            <th>Mono</th>
            <th>Color</th>
            <th>Qte Total</th>
            <th>Observação</th>
            <th>Arquivo</th>
         </tr>
     </thead>

      <tbody>
         <tr>
         <?php
            $query_itens = "SELECT * FROM materialcopias_item WHERE materialcopias_id = '".$model->matc_id."'";
            $itensModel = MaterialCopiasItens::findBySql($query_itens)->all(); 
            foreach ($itensModel as $itens) {
         ?>
            <tr>
               <td><?= $itens["item_descricao"]; ?></td>
               <td><?= $itens["item_qtoriginais"]; ?></td>
               <td><?= $itens["item_qtexemplares"]; ?></td>
               <td><?= $itens["item_qteCopias"]; ?></td>
               <td><?= $itens["item_mono"]; ?></td>
               <td><?= $itens["item_color"]; ?></td>
               <td><?= $itens["item_qteTotal"]; ?></td>
               <td><?= $itens["item_observacao"]; ?></td>
               <td valign="middle">
                  <?php if($model->matc_tipo == 1) { ?>
                     <a target="_blank" href="http://localhost/aux_planejamento/web/uploads/repositorio/<?= $itens["item_codrepositorio"]; ?>/<?= $itens["item_arquivo"]; ?>" "><?= $itens["item_arquivo"]; ?></a>
                  <?php }else{ ?>
                     <a target="_blank" href="http://localhost/reprografia/web/uploads/impressoes/<?= $itens["materialcopias_id"]; ?>/<?= $itens["item_arquivo"]; ?>" "><?= $itens["item_arquivo"]; ?></a>
                  <?php } ?>
            </td>
            </tr>
         <?php } ?>
         </tr> 
      </tbody>
   </table>
                   <!-- SESSÃO 3 SERVIÇOS DE ACABAMENTO -->
   <table class="table table-condensed table-hover">
      <thead>
         <tr class="info"><th colspan="12">SEÇÃO 3: Serviços de Acabamento</th></tr>
      </thead>
      <tbody>
         <tr>
            <td colspan="3"><strong>Acabamentos: </strong>
               <?php
                  $query_acabamento = "SELECT acab_descricao FROM acabamento_acab, copiasacabamento_copac WHERE materialcopias_id = '".$model->matc_id."' AND acabamento_id = acabamento_acab.id";
                  $acabamento = Acabamento::findBySql($query_acabamento)->all(); 
                  foreach ($acabamento as $acabamentos) {
                     echo $acabamentos["acab_descricao"] . " / ";
                  }
               ?>
            </td>
         </tr> 
      </tbody>
    </table>
                   <!-- SESSÃO 4 INFORMAÇÕES FINANCEIRAS -->
   <table class="table table-condensed table-hover">
      <thead>
         <tr class="info"><th colspan="12">SEÇÃO 4: Informações Financeiras</th></tr>
      </thead>
      <tbody>
         <tr class="warning" style="border-top: #dedede">
            <td>Subtotal Mono<i> (Qte Exemplares * Mono) * R$ 0,12</i></td>
            <td style="color:red"><?= 'R$ ' . number_format($model->matc_totalValorMono, 2, ',', '.') ?></td>
         </tr>
         <tr class="warning" style="border-top: #dedede">
            <td>Subtotal Color<i> (Qte Exemplares * Color) * R$ 0,6</i></td>
            <td style="color:red"><?= 'R$ ' . number_format($model->matc_totalValorColor, 2, ',', '.') ?></td>
         </tr>
         <?php
            //somatória de Quantidade * Valor de todas as linhas
            $query = (new \yii\db\Query())->from('materialcopias_matc')->where(['matc_id' => $model->matc_id]);
            $sum = $query->sum('matc_totalValorMono+matc_totalValorColor');
         ?>
         <tr class="warning" style="border-top: #dedede">
            <td>TOTAL GERAL<i> (Total Mono + Total Color)</i></td>
            <td style="color:red"><strong><?= 'R$ ' . number_format($sum, 2, ',', '.') ?></strong></td>
         </tr>
      </tbody>
   </table>
                  <!-- CAIXA DE AUTORIZAÇÃO GERÊNCIA DO SETOR -->
   <div class="container">
      <div class="row">
         <div class="col-md-4">
            <?php if($model->matc_ResponsavelGer != NULL){ ?>
            <table class="table" colspan="2"  border="1" style="max-width: 80%;">
               <thead>
                  <tr>
                     <th class="warning" colspan="2" style="border-top: #dedede;text-align: center;">AUTORIZAÇÃO DO SETOR</th>
                  </tr>
              </thead>
              <tbody>
                  <tr>
                     <td colspan="2" style="text-align: center;"><?= $model->matc_autorizadoGer ? '<span class="label label-success">AUTORIZADO</span>' : '<span class="label label-danger">NÃO AUTORIZADO</span>' ?></td>
                  </tr>
                  <tr>
                     <td colspan="2"><strong>Responsável:</strong> <?= $model->matc_ResponsavelGer ?></td>
                  </tr>
                  <tr>
                     <td colspan="2"><strong>Data</strong>: <?= date('d/m/Y à\s H:i', strtotime( $model->matc_dataGer )) ?></td>
                  </tr>
              </tbody>
            </table>
            <?php } ?>
         </div>
                  <!-- CAIXA DE AUTORIZAÇÃO DEP -->
         <div class="col-md-4">
            <?php if($model->matc_ResponsavelAut != NULL){ ?>
            <table class="table" colspan="2"  border="1" style="max-width: 80%;">
               <thead>
                  <tr>
                     <th class="warning" colspan="2" style="border-top: #dedede;text-align: center;">AUTORIZAÇÃO DEP</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td colspan="2" style="text-align: center;"><?= $model->matc_autorizado ? '<span class="label label-success">AUTORIZADO. À REPROGRAFIA PARA PROVIDÊNCIAS</span>' : '<span class="label label-danger">NÃO AUTORIZADO</span>' ?></td>
                  </tr>
                  <tr>
                     <td colspan="2"><strong>Responsável:</strong> <?= $model->matc_ResponsavelAut ?></td>
                  </tr>
                  <tr>
                     <td colspan="2"><strong>Data</strong>: <?= date('d/m/Y à\s H:i', strtotime( $model->matc_dataAut )) ?></td>
                  </tr>
               </tbody>
            </table>
            <?php } ?>
         </div>
                  <!-- CAIXA DE DE ENCAMINHAMENTO REPROGRAFIA -->
         <?php if($model->matc_ResponsavelRepro != NULL){ ?>
         <div class="col-md-4">
            <table class="table" colspan="2"  border="1" style="max-width: 50%;">
               <thead>
                  <tr>
                     <th class="warning" colspan="2" style="border-top: #dedede;text-align: center;">REPROGRAFIA</th>
                  </tr>
               </thead>
               <tbody>
                  <tr>
                     <td colspan="2" style="text-align: center;"><?= $model->matc_encaminhadoRepro ? '<span class="label label-warning">ENCAMINHADO À TERCEIRIZADA</span>' : '<span class="label label-info">ENCAMINHADO PARA     PRODUÇÃO INTERNA</span>' ?></td>
                  </tr>
                  <tr>
                     <td colspan="2"><strong>Responsável:</strong> <?= $model->matc_ResponsavelRepro ?></td>
                   </tr>
                  <tr>
                     <td colspan="2"><strong>Data</strong>: <?= date('d/m/Y à\s H:i', strtotime( $model->matc_dataRepro )) ?></td>
                  </tr>
               </tbody>
            </table>
               <?php } ?>
         </div>
      </div>
   </div>
   </div>
</div>
</div>
