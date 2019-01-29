<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\Acabamento */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="acabamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'acab_descricao')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'acab_status')->radioList(['1' => 'Ativo', '0' => 'Inativo']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Criar' : 'Atualizar', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
