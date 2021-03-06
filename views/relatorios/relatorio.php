<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use kartik\datecontrol\DateControl;

$this->title = 'Relatório Mensal';
$this->params['breadcrumbs'][] = 'Relatórios';
?>

<div class="relatorios">

  <h1><?= Html::encode($this->title) ?></h1>
    <?php $form = ActiveForm::begin(['options'=>['target'=>'_blank']]); ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-5">
                <?php
                    $data_unidades = ArrayHelper::map($unidades, 'uni_codunidade', 'uni_nomeabreviado');
                    echo $form->field($model, 'relat_unidade')->widget(Select2::classname(), [
                            'data' =>  $data_unidades,
                            'options' => ['placeholder' => 'Todas as Unidades...'],
                            'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                ?>
                </div>
                <div class="col-md-3">
                <?php
                    echo $form->field($model, 'relat_encaminhamento')->widget(Select2::classname(), [
                            'data' =>  [0 => 'Produção Interna', 1 => 'Terceirizada'],
                            'options' => ['placeholder' => 'Tipo de Encaminhamento...'],
                            'pluginOptions' => [
                                    'allowClear' => true
                                ],
                            ]);
                ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'relat_datainicio')->widget(DateControl::classname(), [
                            'type'=>DateControl::FORMAT_DATE,
                            'ajaxConversion'=>false,
                            'widgetOptions' => [
                                'removeButton' => false,
                                'pluginOptions' => [
                                    'autoclose' => true
                                ]
                            ]
                        ]);
                    ?>
				</div>
                <div class="col-md-2">
                    <?= $form->field($model, 'relat_datafim')->widget(DateControl::classname(), [
                            'type'=>DateControl::FORMAT_DATE,
                            'ajaxConversion'=>false,
                            'widgetOptions' => [
                                'removeButton' => false,
                                'pluginOptions' => [
                                    'autoclose' => true
                                ]
                            ]
                        ]);
                    ?>
                </div>
			</div>
        <?= Html::a('Gerar Relatório', ['relatorio'], [
            'class' => 'btn btn-success',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>