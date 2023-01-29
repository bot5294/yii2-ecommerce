<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\widgets\Pjax;

/**
 * User: bot5294
 * Date: 28/01/2023
 * Time: 11:52 AM
 */
/** @var \common\models\User $user */
/** @var \yii\web\View $this */
/** @var \common\models\UserAddress $userAddress */

?>

<div class="row">
    <!-- form : profile -->
    <div class="col">
        <div class="card">
            <div class="card-header">
                Address Information
            </div>
        <div class="card-body">
            <?php Pjax::begin([
                'enablePushState'=>false
            ]) ?>
           <?php echo $this->render('user_address',[
                'userAddress' => $userAddress
           ]) ?>
            <?php Pjax::end() ?>
        </div>
        </div>
    </div>
    <!-- form : account information -->
    <div class="col">
        <div class="card">
            <div class="card-header">
                Account Information
            </div>
            <div class="card-body">
                <?php Pjax::begin([
                    'enablePushState'=>false
                ]) ?>
                <?php echo $this->render('user_account',[
                    'user'=>$user
                ]) ?>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
</div>