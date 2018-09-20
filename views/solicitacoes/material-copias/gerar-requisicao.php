<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\solicitacao\Solicitacao */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tiporequisicao-form">

    <?php $form = ActiveForm::begin(); ?>

<div class="panel-body">
	<div class="row">
		<div class="col-md-12">
			<?= $form->field($model, 'matc_tipo')->radioList(['Apostilas' => 'Apostilas', 'Impressao' => 'Impressão' ]) ?>
		</div>
	</div>
</div>

    <div class="form-group">
        <?= Html::submitButton('Criar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>