<?php
use \yii\helpers\Html;

$this->params['breadcrumbs']=array(
	$model->title=>$model->url,
	'Update',
);
?>

<h1>Update <i><?php echo Html::encode($model->title); ?></i></h1>

<?php echo $this->context->renderPartial('_form', array('model'=>$model)); ?>