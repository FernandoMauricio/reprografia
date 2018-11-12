<?php
$session = Yii::$app->session;
/* @var $this yii\web\View */

$this->title = 'Reprografia';
?>

<div class="site-index">
    <h1 class="text-center"> Módulo de Reprografia</h1>
        <div class="body-content">
            <div class="container">
                <h3>Bem vindo(a), <?php echo $session['sess_nomeusuario'] = utf8_encode(ucwords(strtolower($session['sess_nomeusuario'])))?>!</h3>
            </div>
        </div>

    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-star-empty"></i> O que há de novo? - Versão 1.1 - Publicado em 01/11/2018</div>
            <div class="panel-body">
              <h4><b style="color: #337ab7;">Implementações</b></h4>
                <h5><i class="glyphicon glyphicon-tag"></i><b> Solicitações de Cópias</b></h5>
                    <h5>- Alterado a nomenclatura de <b style="color: #c0392b;">Impressão</b> -> <b style="color: #27ae60;">Impressão Avulsa</b>.</h5>
                    <h5>- <b style="color: #27ae60;">Impressão Avulsa</b> não precisará mais da aprovação da DEP.</h5>
                    <h5>- Incluído o campo <b style="color: #2980b9;">Previsão Entrega</b> na solicitação de cópias.</h5>
                    <h5>- Incluído nas listagem das solicitações o <b style="color: #2980b9;">Tipo de Serviço</b> (Apostilas/Impressão Avulsa).</h5>
                    <h5>- Incluido nas listagem das solicitações a <b style="color: #2980b9;">Previsão Entrega</b>.</h5>
            </div>
        </div>
<!--         <div class="row">
            <div class="col-md-3">
              <div class="card-counter default">
                <i class="glyphicon glyphicon-inbox"></i>
                <span class="count-numbers">0</span>
                <span class="count-numbers-porcent">0%</span>
                <span class="count-name">Aguardando Atend.</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card-counter primary">
                <i class="glyphicon glyphicon-tasks"></i>
                <span class="count-numbers">0</span>
                <span class="count-numbers-porcent">0%</span>
                <span class="count-name">Em Processo</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card-counter danger">
                <i class="glyphicon glyphicon-warning-sign"></i>
                <span class="count-numbers">0</span>
                <span class="count-numbers-porcent">0%</span>
                <span class="count-name">Atrasados</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="card-counter success">
                <i class="glyphicon glyphicon-ok"></i>
                <span class="count-numbers">0</span>
                <span class="count-numbers-porcent">0%</span>
                <span class="count-name">Finaliz. Técnico</span>
              </div>
            </div>
        </div> -->
    </div>
</div>