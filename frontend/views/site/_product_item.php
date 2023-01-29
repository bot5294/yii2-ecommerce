<?php

/**
 * User: bot5294
 * Date: 27/01/2023
 * Time: 12:12 PM
 */

use yii\helpers\StringHelper;
use yii\helpers\Url;

 /** @var \common\models\Product $model */
 ?>

<!-- <div class=""> -->
    <div class="card h-100">
        <!-- Product image-->
        <img class="card-img-top" src="<?php echo $model->getImageUrl() ?>" alt="..." />
        <!-- Product details-->
        <div class="card-body p-4">
            <div class="text-center">
                <!-- Product name-->
                <h5 class="fw-bolder text-primary"><?php echo $model->name ?></h5>
                <!-- Product price-->
                <h5 style="color: green;"><?php echo Yii::$app->formatter->asCurrency($model->price) ?> /-</h5>
                <!-- Product description -->
                <div class="card-text">
                    <?php echo $model->getShortDescription() ?>
                </div>
            </div>
        </div>
        <!-- Product actions-->
        <div class="card-footer text-end">
            <a class="btn btn-outline-success btn-add-to-cart mt-auto" href="<?php echo Url::to(['/cart/add']) ?>">Add to cart</a>
        </div>
    </div>
<!-- </div> -->