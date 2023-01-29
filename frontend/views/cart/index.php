<?php
/**
 * User: bot5294
 * @var array $items
 */

use common\models\Product;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="card">
    <div class="card-header">
        <h3>Your Cart Items</h3>
    </div>
    <div class="card-body p-0">
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
                <tr>
                    <td><?php echo $item['name'] ?></td>
                    <td>
                        <img src="<?php echo Product::formatImageUrl($item['image']) ?>"
                        style="width:50px;"
                        alt="<?php echo $item['name'] ?>"
                        />
                    </td>
                    <td><?php echo $item['price'] ?></td>
                    <td><?php echo $item['quantity'] ?></td>
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
    </div>
</div>
