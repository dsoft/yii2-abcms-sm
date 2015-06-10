<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use abcms\sm\models\Platform;

/* @var $this yii\web\View */
/* @var $model abcms\sm\models\Account */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="account-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'platformId')->dropDownList(Platform::listAll()) ?>

    <?= $form->field($model, 'link')->textInput(['maxlength' => 255]) ?>

    <?= $form->field($model, 'identifier')->textInput(['maxlength' => 500])->hint('Platform specific identifier like Facebook ID') ?>

    <?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
