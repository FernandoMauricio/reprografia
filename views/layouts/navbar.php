<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\nav\NavX;

$session = Yii::$app->session;

NavBar::begin([
    'brandLabel' => '<img src="css/img/logo_senac_topo.png"/>',
    'brandUrl' => Yii::$app->homeUrl,
    'options' => [
        'class' => 'navbar-inverse navbar-fixed-top',
    ],
]);
echo Navx::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'encodeLabels' => false,
    'items' => [
        ['label' => 'Home', 'url' => ['/site/index']],
        [
        'label' => 'Solicitações de Cópias',
        'items' => [
                     ['label' => 'Nova Solicitação', 'url' => ['/solicitacoes/material-copias/index']],
                                 '<li class="divider"></li>',
                        ['label' => 'Administração', 'items' => [
                            ['label' => 'Solicitações em aprovação', 'url' => ['/solicitacoes/material-copias-aut-gerencia/index']],
                                 '<li class="divider"></li>',
                            ['label' => 'Solicitações Pendentes', 'url' => ['/solicitacoes/material-copias-pendentes/index']],
                            ['label' => 'Solicitações Aprovadas', 'url' => ['/solicitacoes/material-copias-aprovadas/index']],
                            ['label' => 'Solicitações Encerradas', 'url' => ['/solicitacoes/material-copias-encerradas/index']],
                        ]],
                        ['label' => 'Cadastros', 'items' => [
                            ['label' => 'Tipos de Acabamento', 'url' => ['/solicitacoes/acabamento/index']],

                        ]],


                 ],
        ],
    ],
]);
NavBar::end();
?>