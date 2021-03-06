<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use app\models\solicitacoes\Acabamento;
use app\models\solicitacoes\MaterialCopiasItens;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopias */
?>
<div class="material-copias-view">
   <div class="panel-body">
         <div class="row">
            <h4>Solicitação de Cópia: <?= $model->matc_id ?><small><span class="label-material-copias-view"><?= $model->matc_tipo == 1 ? 'Requisição de Apostilas' : 'Requisição de Impressão' ?></span></small>
            </h4><br />
            <h5>
            <span class="pull-left">
               <b>Data da Solicitação: </b><small><span class="label label-primary" style="font-size: 100%;font-weight:normal"><?= date('d/m/Y', strtotime($model->matc_data)); ?></span></small><br /><br />
               <b>Previsão de Entrega: </b><small><span class="label label-success" style="font-size: 100%;font-weight:normal"><?= isset($model->matc_dataPrevisao) ?  date('d/m/Y', strtotime($model->matc_dataPrevisao)) : ''; ?></span></small>
            </span>
            <span class="pull-right">
               <b>Situação: </b><small><span class="label label-warning" style="font-size: 100%;font-weight:normal"><?= $model->situacao->sitmat_descricao; ?></span></small>
            </span>
            </h5><br />
         </div>
   </div>

<!-- Mensagem informando o recebimento  -->
<?php if($model->matc_dataReceb != NULL): ?> 
    <div class='alert alert-success' align='center' role='alert'>
        <span class='glyphicon glyphicon-alert' aria-hidden='true'></span> Requisição <b>recebida</b> por: <b><?= ucwords(mb_strtolower($model->matc_responsavelReceb)) ?></b> em <?= date('d/m/Y à\s H:i', strtotime($model->matc_dataReceb)) ?> <br />
        Observação: <?= $model->matc_descricaoReceb ?>
        </div>
<?php endif; ?>

<div class="panel panel-info">
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
            <th>Qte Exemplares</th>
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
                     <a target="_blank" data-pjax="0" href="http://portalsenac.am.senac.br/aux_planejamento/web/uploads/repositorio/<?= $itens["item_codrepositorio"]; ?>/<?= $itens["item_arquivo"]; ?>" "><?= $itens["item_arquivo"]; ?></a>
                  <?php }else{ ?>
                     <a target="_blank" data-pjax="0" href="http://portalsenac.am.senac.br/reprografia/web/uploads/impressoes/<?= $itens["materialcopias_id"]; ?>/<?= $itens["item_arquivo_descricao"]; ?>" "><?= $itens["item_arquivo"]; ?></a>
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
                  $query_acabamento = "SELECT GROUP_CONCAT(acab_descricao) as acab_descricao FROM acabamento_acab, copiasacabamento_copac WHERE materialcopias_id = '".$model->matc_id."' AND acabamento_id = acabamento_acab.id";
                  $acabamento = Acabamento::findBySql($query_acabamento)->one();
                  echo str_replace(",", " / ", $acabamento['acab_descricao']);
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
         <?php
            $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
            $sumSubtotalMono = $query->sum('((item_qtexemplares*item_mono) * 0.12)');
         ?>
            <td><strong>Subtotal Mono</strong><i> (Qte Exemplares * Mono) * R$ 0,12</i></td>
            <td style="color:#c0392b"><?= 'R$ ' . number_format($sumSubtotalMono, 2, ',', '.') ?></td>
         </tr>
         <?php
            $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
            $sumSubtotalColor = $query->sum('((item_qtexemplares*item_color) * 0.95)');
         ?>
         <tr class="warning" style="border-top: #dedede">
            <td><strong>Subtotal Color</strong><i> (Qte Exemplares * Color) * R$ 0,95</i></td>
            <td style="color:#c0392b"><?= 'R$ ' . number_format($sumSubtotalColor, 2, ',', '.') ?></td>
         </tr>
         <?php
            //somatória de Quantidade de Exemplares * 4,00 da Encadernação
            $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
            $sumEncadernacao = $query->sum('item_qtexemplares*4');
         ?>
         <?php  if(strpos($acabamento["acab_descricao"], 'Encadernação')) { ?>
         <tr class="warning" style="border-top: #dedede">
            <td><strong>Encadernação</strong><i> (Qte Exemplares * R$ 4,00)</i></td>
            <td style="color:#c0392b"><strong><?= 'R$ ' . number_format($sumEncadernacao, 2, ',', '.') ?></strong></td>
         </tr>
         <?php } ?>
         <tr class="warning" style="border-top: #dedede">
            <td><strong>TOTAL GERAL</strong></td>
            <td style="color:#c0392b"><strong><?= 'R$ ' . number_format($model->matc_totalGeral, 2, ',', '.') ?></strong></td>
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