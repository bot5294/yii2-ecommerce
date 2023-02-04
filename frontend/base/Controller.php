<?php
/** User: bot5294 */

namespace frontend\base;
use Codeception\Lib\Generator\Actions;
use common\models\CartItem;
use Yii;

/**
 * Class Controller
 * 
 * @package frontend\base
 */

 class Controller extends \yii\web\Controller
 {
    public function beforeAction($action)
    {
        $this->view->params['cartItemCount'] = CartItem::getTotalQuantityForUser(currUserId());
        return parent::beforeAction($action);
    }
 }