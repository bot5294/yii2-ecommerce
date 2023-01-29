<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/** @var \common\models\User $user */
/** @var \yii\web\View $this */
/**
 * User: bot5294
 * Date:28/01/2023
 * Time:12:51 PM
 */
?>



<?php if(isset($success) && $success) : ?>
<div class="alert alert-success">
    Your account is successfully updated
</div>
<?php endif ?>

    <?php $form = ActiveForm::begin([
        'action' => ['/profile/update-account'],
        'options'=>[
            'data-pjax'=>1
        ]
    ]); ?>
        <?= $form->field($user, 'username')->textInput(['autofocus' => true]) ?>

        <div class="row">
            <div class="col-md-6">
                <?= $form->field($user, 'firstname')->textInput(['autofocus' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($user, 'lastname')->textInput(['autofocus' => true]) ?>
            </div>
        </div>

        <?= $form->field($user, 'email') ?>
        <div class="row">
            <div class="col">
                <?= $form->field($user, 'password')->passwordInput() ?>
            </div>
            <div class="col">
                <?= $form->field($user, 'passwordConfirm')->passwordInput() ?>
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
    <?php ActiveForm::end(); ?>
