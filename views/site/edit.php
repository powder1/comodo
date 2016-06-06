<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Work */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Update';
?>

<div class="work-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rate')->textInput() ?>

    <?= $form->field($model, 'title')->textInput() ?>

    <?= $form->field($model, 'release_date')->textInput() ?>

    <?= $form->field($model, 'original_title')->textInput() ?>

    <?= $form->field($model, 'overview')->textInput() ?>

    <?= $form->field($model, 'poster_path')->textInput() ?>
    
    <?= $form->field($model, 'sort')->textInput() ?>
    
    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
