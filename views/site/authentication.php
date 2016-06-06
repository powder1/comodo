<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Authentication';

?>

<?php $form = ActiveForm::begin([
        'id' => 'authentication-form',
        'options' => ['class' => 'form-horizontal'],
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>

<?= $form->field($model, 'apikey')->textInput(['autofocus' => true]) ?>

<?= Html::submitButton('Authentication', ['class' => 'btn btn-primary', 'name' => 'authentication-button']) ?>

<?php ActiveForm::end(); ?>
