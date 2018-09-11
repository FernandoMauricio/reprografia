<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacoes\MaterialCopiasPendentesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="material-copias-pendentes-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'matc_id') ?>

    <?php // echo $form->field($model, 'matc_color') ?>

    <?php // echo $form->field($model, 'matc_curso') ?>

    <?php // echo $form->field($model, 'matc_centrocusto') ?>

    <?php // echo $form->field($model, 'matc_unidade') ?>

    <?php // echo $form->field($model, 'matc_solicitante') ?>

    <?php // echo $form->field($model, 'matc_data') ?>

    <?php // echo $form->field($model, 'situacao_id') ?>

    <?php // echo $form->field($model, 'matc_qteCopias') ?>

    <?php // echo $form->field($model, 'matc_qteTotal') ?>

    <?php // echo $form->field($model, 'matc_totalValorMono') ?>

    <?php // echo $form->field($model, 'matc_totalValorColor') ?>

    <?php // echo $form->field($model, 'matc_ResponsavelAut') ?>

    <?php // echo $form->field($model, 'matc_dataAut') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
