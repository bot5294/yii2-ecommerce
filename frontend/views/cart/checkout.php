<?php
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;

/** User: bot5294 */
/** @var \common\models\Order $order */
/** @var \common\models\OrderAddress $orderAddress */
/** @var \common\models\OrderItem $order_items */
/** @var array $cartItems */
/** @var int $productQuantity */
/** @var float $totalPrice */
?>

<?php $form = ActiveForm::begin([
        'action' => ['submit-payment'],
    ]); ?>

<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h4>Order Summary</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <td>Products</td>
                        <td colspan="2"><?php echo $productQuantity ?></td>
                    </tr>
                    <tr>
                        <td>Total Price</td>
                        <td class="text-right">
                            <?php echo Yii::$app->formatter->asCurrency($totalPrice) ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <!-- PayPal btn start -->
        <?= $form->field($order,"total_price")->hiddenInput([
            'value'=>$totalPrice
        ])->label(false) ?>
        <?= $form->field($order,"created_at")->hiddenInput([
            'value'=>time()
        ])->label(false) ?>
        <?= $form->field($order,"created_by")->hiddenInput([
            'value'=>Yii::$app->user->getId()
        ])->label(false) ?>
        <button class="btn btn-secondary float-end mt-2 mb-2">Checkout</button>
        <!-- PayPal btn end -->

<!-- cart items -->
<?php foreach ($cartItems as $index => $item): ?>
        <?= $form->field($orderItem,"product_name[".$index."]")->hiddenInput([
            'value'=>$item["name"]
        ])->label(false) ?>
        <?= $form->field($orderItem,"product_id[".$index."]")->hiddenInput([
            'value'=>$item["id"]
        ])->label(false) ?>
        <?= $form->field($orderItem,"unit_price[".$index."]")->hiddenInput([
            'value'=>$item["price"]
        ])->label(false) ?>
        <?= $form->field($orderItem,"quantity[".$index."]")->hiddenInput([
            'value'=>$item["quantity"]
        ])->label(false) ?>
        <?php endforeach; ?>

<!-- cart items -->

    </div>
</div>


<div class="row">
    <!-- form : profile -->
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5>Address Information</h5>
            </div>
        <div class="card-body">
            <?= $form->field($orderAddress,'address') ?>
            <?= $form->field($orderAddress,'city') ?>
            <?= $form->field($orderAddress,'state') ?>
            <?= $form->field($orderAddress,'country') ?>
            <?= $form->field($orderAddress,'zipcode') ?>
        </div>
        </div>
    </div>
        <!-- form : account information -->
    <div class="col">
        <div class="card">
            <div class="card-header">
                <h5>Account Information</h5>
            </div>
            <div class="card-body">


        <div class="row">
            <div class="col-md-6">
                <?= $form->field($order, 'firstname')->textInput(['autofocus' => true]) ?>
            </div>
            <div class="col-md-6">
                <?= $form->field($order, 'lastname')->textInput(['autofocus' => true]) ?>
            </div>
        </div>

        <?= $form->field($order, 'email')->textInput(['autofocus'=>true]) ?>

            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>