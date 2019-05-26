<?php

use yii\widgets\ActiveForm;

$this->title = 'Enter your authentication code';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row login-page">
    <div class="container">
        <?php $form = ActiveForm::begin([
            'id' => 'two-factor-auth-form',
            'enableClientValidation' => true,
        ]); ?>

        <div class="two-fa-container">
            <h2><?php echo $this->title; ?></h2>

            <p class="text">Please enter the six digit code from your authenticator app to continue.</p>

            <?php echo $form->field($model, 'authenticationCode'])
                ->input('text', ['placeholder' => 'Enter Authentication Code']); ?>

            <button type="submit" class="btn btn-primary btn-submit">Submit</button>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
