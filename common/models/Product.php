<?php

namespace common\models;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property string|null $image
 * @property float $price
 * @property int $status
 * @property int|null $created_at
 * @property int|null $updated_at
 * @property int|null $created_by
 * @property int|null $updated_by
 *
 * @property CartItems[] $cartItems
 * @property User $createdBy
 * @property OrderItems[] $orderItems
 * @property User $updatedBy
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * Summary of imageFile
     * @var \yii\web\UploadedFile
     */
    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::class,
            BlameableBehavior::class
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'price', 'status'], 'required'],
            [['description'], 'string'],
            [['price'], 'number'],
            [['imageFile'],'image','extensions'=>'png, jpeg, jpg, webp','maxSize'=>10*1024*1024],
            [['status', 'created_at', 'updated_at', 'created_by', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['image'], 'string', 'max' => 2000],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['updated_by' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'image' => 'Product Image',
            'imageFile' => 'Product Image',
            'price' => 'Price',
            'status' => 'Published',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Gets query for [[CartItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\CartItemsQuery
     */
    public function getCartItems()
    {
        return $this->hasMany(CartItems::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[CreatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'created_by']);
    }

    /**
     * Gets query for [[OrderItems]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\OrderItemsQuery
     */
    public function getOrderItems()
    {
        return $this->hasMany(OrderItems::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[UpdatedBy]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\UserQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::class, ['id' => 'updated_by']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\ProductQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\ProductQuery(get_called_class());
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        try {
            if($this->imageFile && gettype($this->imageFile)!="boolean"){
                echo '<pre>';
                print_r("inside l 146");//gettype($this->imageFile) == "boolean");
                echo '</pre>';
                // exit;
                // if($this->image != "http://yii2-ecommerce.localhost/img/No-Image-Placeholder.png"){
                    $this->image = '/products/' . Yii::$app->security->generateRandomString() . '/' . $this->imageFile->name;
                // }
            }
            $transaction = Yii::$app->db->beginTransaction();
            $ok = parent::save($runValidation, $attributeNames);
            if ($ok && $this->image && gettype($this->imageFile)!="boolean") {
                $fullPath = Yii::getAlias('@frontend/web/storage' . $this->image);
                $dir = dirname($fullPath);
                // echo '<pre>';
                // var_dump($this->image);
                // echo '\n fullpath ==>';
                // var_dump($fullPath);
                // echo '\n imageFile ==>';
                // var_dump($this->imageFile);
                // echo '</pre>';
                if (!FileHelper::createDirectory($dir) | !$this->imageFile->saveAs($fullPath)) {
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return $ok;
        } catch (\Throwable $th) {
            echo '<pre> throwable msg line 169 product->save : ';
            print_r($th->getMessage());
            echo '</pre>';
        }
    }
    public function getImageUrl(){
        // echo '<pre> inside getImageUrl ----> ';
        // var_dump($this->image);
        // echo '</pre>';
        // exit;
        return self::formatImageUrl($this->image);
    }

    public static function formatImageUrl($imagePath){
        if($imagePath=="http://yii2-ecommerce.localhost/img/No-Image-Placeholder.png"){
            return Yii::$app->params['frontendUrl'] . '/img/No-Image-Placeholder.png';        
        }
        return Yii::$app->params['frontendUrl'] .'/storage'. $imagePath;
    }


    public function getShortDescription(){
        return StringHelper::truncateWords(strip_tags($this->description), 30);
    }
}
