<?php

/** @var yii\web\View $this */

use yii\bootstrap5\LinkPager;
use yii\widgets\ListView;

/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <div class="body-content">
        <section class="py-1">
        <div class="container px-4 px-lg-5">
                <?php echo ListView::widget([
                    'dataProvider'=>$dataProvider,
                    'layout' => '{summary}<div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">{items}</div>{pager}',
                    'itemView' => '_product_item',
                    'itemOptions' => [
                        'class' => 'col mb-5'
                    ],
                    'pager'=>[
                        'class'=>LinkPager::class
                    ]
                ]) ?>                
        </div>
    </section>
    </div>
</div>
