<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasJustificativas */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-copias-justificativas-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'id_materialcopias')->textInput(['readonly'=>true]) ?></div>

        <div class="col-md-9"><?= $form->field($model, 'usuario')->textInput(['readonly'=>true]) ?></div>
    </div>
    <div class="row">
        <div class="col-md-12"><?= $form->field($model, 'descricao')->textarea(['rows' => 3]) ?></div>
    </div>
   
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Inserir Justificativa' : 'Atualizar Justificativa', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
