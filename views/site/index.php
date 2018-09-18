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
        <div class="row">
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
        </div>
    </div>
</div>