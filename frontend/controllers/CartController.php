<?php

/** User: bot5294 */


namespace frontend\controllers;

use common\models\CartItem;
use common\models\Order;
use common\models\OrderAddress;
use common\models\OrderItem;
use common\models\Product;
use PhpParser\Node\Stmt\TryCatch;
use Yii;
use yii\base\Behavior;
use yii\base\ErrorException;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\helpers\VarDumper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class CartController
 * @package frontend\controllers 
 */

 class CartController extends \frontend\base\Controller
 {
    public function behaviors()
    {
        return [
            [
                'class' => ContentNegotiator::class,
                'only' => ['add'],
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ]
            ],
            [
                'class'=>VerbFilter::class,
                'actions'=>[
                    'delete'=>['POST','DELETE'],
                ]
            ],
            'verbs'=>[
                'class'=>VerbFilter::class,
                'actions'=>[
                    'change-quantity'=>['POST'],
                    'submit-payment'=>['POST']
                ],
            ]
        ];
    }
    public function actionIndex()
    {
        $cartItems = (Yii::$app->user->isGuest) ? Yii::$app->session->get(CartItem::SESSION_KEY,[]) : CartItem::getItemsForUser(currUserId());

        return $this->render('index', [
            'items' => $cartItems
        ]);
    }

    public function actionAdd()
    {
        try {
        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if(!$product){
            throw new NotFoundHttpException("Product does not exists!");
        }
        if(Yii::$app->user->isGuest){
            // Save in session
            $cartItem = [
                    'id' => $id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'quantity' => 1,
                    'total_price' => $product->price
            ];
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY,[]);
            $found = false;
            foreach($cartItems as &$item){
                if($item['id'] == $id){
                    $item['quantity']++;
                    $found = true;
                        break;
                }
            }
            if(!$found){
                $cartItem = [
                    'id' => $id,
                    'name' => $product->name,
                    'image' => $product->image,
                    'price' => $product->price,
                    'quantity' => 1,
                    'total_price' => $product->price
                ];
                $cartItems[] = $cartItem;
            }
                Yii::$app->session->set(CartItem::SESSION_KEY, $cartItems);
        }else{
            $userId = Yii::$app->user->id;
            $cartItem = CartItem::find()->userId($userId)->productId($id)->one();
            if($cartItem){
                $cartItem->quantity++;
            }else{
                $cartItem = new CartItem();
                $cartItem->product_id = $id;
                $cartItem->created_by = Yii::$app->user->id;
                $cartItem->quantity = 1;
            }
            if($cartItem->save()){
                return [
                    'success'=>true
                ];
            } else {
                return [
                    'success' => false,
                    'errors' => $cartItem->errors
                ];
            }
        }
        } catch (\Throwable $th) {
            //throw $th;
            echo '<pre>';
            echo print_r($th);
            echo '</pre>';
        }
        // echo "line 56";
        // exit;

    }


    public function actionDelete($id){
        if(isGuest()){
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY, []);
            foreach($cartItems as $i => $cartItem){
                if ($cartItem['id'] == $id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Yii::$app->session->set(CartItem::SESSION_KEY, $cartItems);
        }else{
            CartItem::deleteAll(['product_id' => $id, 'created_by' => currUserId()]);
        }
        return $this->redirect(['index']);
    }
    public function actionChangeQuantity(){
        $id = Yii::$app->request->post('id');
        $product = Product::find()->id($id)->published()->one();
        if(empty($product)){
            throw new NotFoundHttpException("Product does not exist");
        }
        $quantity = Yii::$app->request->post('quantity');
        if(isGuest()){
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY,[]);
            foreach ($cartItems as &$cartItem) {
                if($cartItem['id'] === $id){
                    $cartItem['quantity'] = $quantity;
                    break;
                }
            }
            Yii::$app->session->set(CartItem::SESSION_KEY,$cartItems);
        }else{
            $cartItem = CartItem::find()->userId(currUserId())->productId($id)->one();
            if(!empty($cartItem)){
                $cartItem->quantity = $quantity;
                $cartItem->save();
            }
        }
        return CartItem::getTotalQuantityForUser(currUserId());
    }
    public function actionCheckout(){
        $order = new Order();
        $orderAddress = new OrderAddress();
        $orderItem = new OrderItem();
        if(!isGuest()){
            /** @var \common\models\User $user */
            $user = Yii::$app->user->identity;
            $userAddress = $user->getAddress();
            $order->firstname = $user->firstname;
            $order->lastname = $user->lastname;
            $order->email = $user->email;
            $order->status = Order::STATUS_DRAFT;

            $orderAddress->address = $userAddress->address;
            $orderAddress->city = $userAddress->city;
            $orderAddress->state = $userAddress->state;
            $orderAddress->country = $userAddress->country;
            $orderAddress->zipcode = $userAddress->zipcode;
            $cartItems = CartItem::getItemsForUser(currUserId());
        }else{
            $cartItems = Yii::$app->session->get(CartItem::SESSION_KEY, []);
        }
        $productQuantity = CartItem::getTotalQuantityForUser(currUserId());
        $totalPrice = CartItem::getTotalPriceForUser(currUserId());
        return $this->render('checkout', [
            'order' => $order,
            'orderAddress' => $orderAddress,
            'cartItems'=>$cartItems,
            'productQuantity'=>$productQuantity,
            'totalPrice'=>$totalPrice,
            'orderItem'=>$orderItem
        ]);
    }

    public function actionSubmitPayment(){
        // Yii::$app->response = Response::FORMAT_JSON;
        try{
            $isCompleted = true;
            $order = new Order();
            $orderAddress = new OrderAddress();
            $orderItem = new OrderItem();
    
            $data = Yii::$app->request->post();
            $otemp = $data['Order'];
            $oatemp = $data['OrderAddress'];
            $oitemp = $data['OrderItem'];
    
            $order["total_price"]= $otemp["total_price"];
            $order["created_by"] = $otemp["created_by"];
            $order["created_at"] = $otemp["created_at"];
            $order["firstname"] = $otemp["firstname"];
            $order['transaction_id'] = 't-' . time() . rand(1000, 100000);
            $order['status'] = "1";
            $order["lastname"] = $otemp["lastname"];
            $order["email"] = $otemp["email"];
    
            // saving order details to db
            if(!$order->save()){
                echo '<pre>';
                echo "order not saved";
                print_r($order->getErrors());
                echo '</pre>';
                $isCompleted = false;
                exit;
            }
    
            $orderAddress['address'] = $oatemp['address'];
            $orderAddress['city'] = $oatemp['city'];
            $orderAddress['country'] = $oatemp['country'];
            $orderAddress['state'] = $oatemp['state'];
            $orderAddress['zipcode'] = $oatemp['zipcode'];
            $orderAddress['order_id'] = $order->id;
    
            // saving order address details to db
            if(!$orderAddress->save()){
                echo '<pre>';
                echo "address not saved";
                print_r($orderAddress->getErrors());
                echo '</pre>';
                $isCompleted = false;
                exit;
            }
    
            for($i = 0; $i < count($oitemp['product_id']);$i++){
                $orderItem['product_name'] = $oitemp['product_name'][$i];
                $orderItem['product_id'] = $oitemp['product_id'][$i];
                $orderItem['unit_price'] = $oitemp['unit_price'][$i];
                $orderItem['quantity'] = $oitemp['quantity'][$i];
                $orderItem['order_id'] = $order->id;
                if(!$orderItem->save()){
                    echo '<pre>';
                    print_r($orderItem->getErrors());
                    echo "Error !!! Order Item not saved !!";
                    echo '</pre>';
                    $isCompleted = false;
                    exit;
                }
            }
            if($isCompleted){
                if(!$order->sendEmailToVendor()){
                    Yii::error("Email to the vendor is not sent");
                }
                if(!$order->sendEmailToCustomer()){
                    Yii::error("Email to the Customer is not sent");
                }
                // echo '<pre>';
                // var_dump("i am here");
                // echo '</pre>';
                // exit;

                // clear cart
                CartItem::clearCartItems(currUserId());

                // setiing flash
                Yii::$app->session->addFlash("success", "Order Placed Successfully");
                return $this->redirect('index');
                // return json_encode([
                //     'success' => true
                // ]);
            }else{
                Yii::error("Order was not saved. Data: " . VarDumper::dumpAsString($order->toArray() . '.Errors.' . VarDumper::dumpAsString($order->errors)));
            }
        }catch(ErrorException $e){
            echo '<pre>';
            var_dump($e);
            echo '</pre>';
        }
        // Yii::$app->
    }
 }