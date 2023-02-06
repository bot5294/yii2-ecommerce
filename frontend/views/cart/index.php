<?php
/**
 * User: bot5294
 * @var array $items
 */

use common\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<?php if(isset($order_placed) && $order_placed) : ?>
<div class="alert alert-success">
    Your Order Placed successfully !!
</div>
<?php endif ?>

<div class="card">
    <div class="card-header">
        <h3>Your Cart Items</h3>
    </div>
    <div class="card-body p-0">
        <?php if(!empty($items)): ?>
        <table class="table table-hover">
        <thead>
            <tr>
                <th>Product</th>
                <th>Image</th>
                <th>Unit Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): ?>
                <tr data-id="<?php echo $item['id'] ?>" data-url="<?php echo Url::to(['/cart/change-quantity']) ?>">
                    <td><?php echo $item['name'] ?></td>
                    <td>
                        <img src="<?php echo Product::formatImageUrl($item['image']) ?>"
                        style="width:50px;"
                        alt="<?php echo $item['name'] ?>"
                        />
                    </td>
                    <td><?php echo $item['price'] ?></td>
                    <td>
                        <input type="number" min="1" class="form-control item-quantity" style="width:60px" value="<?php echo $item['quantity'] ?>">
                    </td>
                    <td><?php echo $item['total_price'] ?></td>
                    <td><?php echo Html::a('Delete',['/cart/delete','id'=>$item['id']],[
                        'class' => 'btn btn-outline-danger btn-sm',
                        'data-method'=>'post',
                        'data-confirm'=>'are you sure you want to remove this product from cart ?'
                    ]) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="card-body text-end">
        <a href="<?php echo Url::to(['/cart/checkout']) ?>" class="btn btn-primary m-2">Checkout</a>
    </div> 
    <?php else: ?>
        <p class="text-muted text-center p-4">There are no items in the cart</p>
    <?php endif; ?>
    </div>
</div>
