<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasPendentes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-copias-pendentes-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'matc_descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matc_qtoriginais')->textInput() ?>

    <?= $form->field($model, 'matc_qtexemplares')->textInput() ?>

    <?= $form->field($model, 'matc_mono')->textInput() ?>

    <?= $form->field($model, 'matc_color')->textInput() ?>

    <?= $form->field($model, 'matc_curso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matc_centrocusto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matc_unidade')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matc_solicitante')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matc_data')->textInput() ?>

    <?= $form->field($model, 'situacao_id')->textInput() ?>

    <?= $form->field($model, 'matc_qteCopias')->textInput() ?>

    <?= $form->field($model, 'matc_qteTotal')->textInput() ?>

    <?= $form->field($model, 'matc_totalValorMono')->textInput() ?>

    <?= $form->field($model, 'matc_totalValorColor')->textInput() ?>

    <?= $form->field($model, 'matc_autorizacao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'matc_dataAut')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
