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
if($session['sess_responsavelsetor'] == 1) { //ÁREA DO GERENTE
    echo Navx::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            [
            'label' => 'Solicitações de Cópias',
            'items' =>  [
                            ['label' => 'Nova Solicitação', 'url' => ['/solicitacoes/material-copias/index']],
                            '<li class="divider"></li>',
                            ['label' => 'Administração', 'items' => [
                                ['label' => 'Solicitações em aprovação', 'url' => ['/solicitacoes/material-copias-aut-gerencia/index']],
                            ]],
                            '<li class="divider"></li>',
                            ['label' => 'Relatórios', 'items' => [
                                ['label' => 'Relatório Mensal', 'url' => ['/relatorios/relatorios/relatorio']],
                            ]],
                        ],
            ],
            [
            'label' => 'Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' =>  [
                            '<li class="dropdown-header">Área Usuário</li>',
                            ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                        ],
            ],
        ],
    ]);
}else if($session['sess_codunidade'] == 11) { //ÁREA DA DEP
    echo Navx::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            [
            'label' => 'Solicitações de Cópias',
            'items' =>  [
                         ['label' => 'Nova Solicitação', 'url' => ['/solicitacoes/material-copias/index']],
                            '<li class="divider"></li>',
                            ['label' => 'Administração', 'items' => [
                                ['label' => 'Solicitações Pendentes', 'url' => ['/solicitacoes/material-copias-pendentes/index']],
                                ['label' => 'Solicitações Encerradas', 'url' => ['/solicitacoes/material-copias-encerradas/index']],
                            ]],
                            '<li class="divider"></li>',
                            ['label' => 'Relatórios', 'items' => [
                                ['label' => 'Relatório Mensal', 'url' => ['/relatorios/relatorios/relatorio']],
                            ]],
                        ],
            ],
            [
            'label' => 'Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' =>  [
                            '<li class="dropdown-header">Área Usuário</li>',
                            ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                        ],
            ],
        ],
    ]);
}else if($session['sess_codusuario'] == 409) { //ÁREA DA TERCEIRIZADA
    echo NavX::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            [
            'label' => 'Solicitações de Cópias',
            'items' => [
                         ['label' => 'Nova Solicitação', 'url' => ['/solicitacoes/material-copias/index']],
                            '<li class="divider"></li>',
                            ['label' => 'Administração', 'items' => [
                                ['label' => 'Solicitações Aprovadas', 'url' => ['/solicitacoes/material-copias-aprovadas/index']],
                                ['label' => 'Solicitações Encerradas', 'url' => ['/solicitacoes/material-copias-encerradas/index']],
                            ]],
                            '<li class="divider"></li>',
                            ['label' => 'Relatórios', 'items' => [
                                ['label' => 'Relatório Mensal', 'url' => ['/relatorios/relatorios/relatorio']],
                            ]],
                        ],
            ],
            [
            'label' => 'Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' =>  [
                            '<li class="dropdown-header">Área Usuário</li>',
                            ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                        ],
            ],
        ],
    ]);
    
}else if($session['sess_codunidade'] == 12 && $session['sess_responsavelsetor'] == 0) { //ÁREA DA REPROGRAFIA - GMT
    echo NavX::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            [
            'label' => 'Solicitações de Cópias',
            'items' => [
                         ['label' => 'Nova Solicitação', 'url' => ['/solicitacoes/material-copias/index']],
                            '<li class="divider"></li>',
                            ['label' => 'Administração', 'items' => [
                                ['label' => 'Solicitações Aprovadas', 'url' => ['/solicitacoes/material-copias-aprovadas/index-gmt']],
                                ['label' => 'Solicitações Encerradas', 'url' => ['/solicitacoes/material-copias-encerradas/index']],
                            ]],
                            '<li class="divider"></li>',
                            ['label' => 'Relatórios', 'items' => [
                                ['label' => 'Relatório Mensal', 'url' => ['/relatorios/relatorios/relatorio']],
                            ]],
                        ],
            ],
            [
            'label' => 'Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' =>  [
                            '<li class="dropdown-header">Área Usuário</li>',
                            ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                        ],
            ],
        ],
    ]);
}else if($session['sess_codunidade'] != 12 && $session['sess_responsavelsetor'] == 0) { //ÁREA DO USUÁRIO
    echo Navx::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'encodeLabels' => false,
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            [
            'label' => 'Solicitações de Cópias',
            'items' =>  [
                            ['label' => 'Nova Solicitação', 'url' => ['/solicitacoes/material-copias/index']],
                            '<li class="divider"></li>',
                            ['label' => 'Relatórios', 'items' => [
                                ['label' => 'Relatório Mensal', 'url' => ['/relatorios/relatorios/relatorio']],
                            ]],
                        ],
            ],
            [
            'label' => 'Usuário (' . utf8_encode(ucwords(strtolower($session['sess_nomeusuario']))) . ')',
            'items' => [
                            '<li class="dropdown-header">Área Usuário</li>',
                            ['label' => 'Sair', 'url' => 'https://portalsenac.am.senac.br/portal_senac/control_base_vermodulos/control_base_vermodulos.php'],
                        ],
            ],
        ],
    ]);
}
NavBar::end();
?>