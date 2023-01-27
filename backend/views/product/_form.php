<?php

// use dosamigos\ckeditor\CKEditor;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Product $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="product-form">

    <?php $form = ActiveForm::begin([
        'options'=>['enctype'=>'multipart/form-data']
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'basic'
    ]) ?>

<!-- <div class="input-group mb-3">
  <label class="input-group-text" for="inputGroupFile01">Upload</label>
  <input type="file" class="form-control" id="inputGroupFile01">
</div> -->
<!-- <div class="input-group mb-3">
  <input type="file" class="form-control" id="inputGroupFile02">
  <label class="input-group-text" for="inputGroupFile02">Upload</label>
</div> -->
<?php if(!$model->image){ ?>
    <?= $form->field($model, 'imageFile',[
        'template' => '<div class="input-group mb-3">{input} {label} {error}</div>',
        'labelOptions' => ['class'=>'input-group-text'],
        'inputOptions' => ['class'=>'form-control']
    ])->textInput(['type'=>'file',
        'placeholder'=>'Upload Image'
    ]) ?>
    <?php }else{ 
    //     echo '<pre> img url =>';
    // var_dump(Html::encode($model->image));
    //     echo '</pre>';
    // exit;
        ?>
        <div class="container-fluid">
            <div class="row">
                <div class="col-4"><img src="<?= $model->getImageUrl() ?>" width="250px" ></div>
                <div class="col-8">
                <?= $form->field($model, 'imageFile')->textInput([
        'placeholder' => $model->image,
        'disabled'=>true
                ]) ?>
                    <?= $form->field($model, 'imageFile',[
        'template' => '<div class="input-group mb-3">{input} {label} {error}</div>',
        // 'label'=>'Change Picture',
        'labelOptions' => ['class'=>'input-group-text'],
        'inputOptions' => ['class'=>'form-control']
    ])->textInput(['type'=>'file',
        'placeholder'=>'Upload Image'
    ]) ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?= $form->field($model, 'price')->textInput([
        'maxlength' => true,
        'type' => 'number'
        ]) ?>

    <?= $form->field($model, 'status')->checkbox() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
