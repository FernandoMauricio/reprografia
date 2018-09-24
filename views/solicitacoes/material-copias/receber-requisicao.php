<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="receber-requisicao-form">

    <?php $form = ActiveForm::begin(); ?>

<div class="panel-body">
	<div class="row">
		<div class="col-md-12"><?= $form->field($model, 'matc_descricaoReceb')->textarea(['rows' => '6']) ?></div>
	</div>
</div>

    <div class="form-group">
        <?= Html::submitButton('Criar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>