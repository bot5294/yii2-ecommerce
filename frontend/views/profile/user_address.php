<?php
use yii\bootstrap5\ActiveForm;
use yii\widgets\Pjax;

/** @var \yii\web\View $this */
/** @var \common\models\UserAddress $userAddress */
/**
 * User: bot5294
 * Date:28/01/2023
 * Time:12:51 PM
 */
?>


<?php if(isset($success) && $success) : ?>
<div class="alert alert-success">
    Your address is successfully updated
</div>
<?php endif ?>

 <?php $addressForm = ActiveForm::begin([
     'action'=>['/profile/update-address'],
     'options'=>[
         'data-pjax'=>1
     ]
 ]); ?>
     <?= $addressForm->field($userAddress,'address') ?>
     <?= $addressForm->field($userAddress,'city') ?>
     <?= $addressForm->field($userAddress,'state') ?>
     <?= $addressForm->field($userAddress,'country') ?>
     <?= $addressForm->field($userAddress,'zipcode') ?>
     <button class="btn btn-primary">Submit</button>
 <?php ActiveForm::end() ?>
