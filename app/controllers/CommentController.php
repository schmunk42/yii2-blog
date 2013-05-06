<?php
use \Yii;
use \yii\web\Controller;
use \yii\web\Pagination;

use app\models\Post;
use app\models\Comment;

class CommentController extends Controller
{
	public $layout='column2';

	/**
	 * @var CActiveRecord the currently loaded data model instance.
	 */
	private $_model;

	public function behaviors() {
		return array(
			'AccessControl' => array(
				'class' => '\yii\web\AccessControl',
				'rules' => array(
		            array(
						'allow'=>true, // allow authenticated users to access all actions
		                'roles'=>array('@'),
		            ),  
		            array(
						'allow'=>false
		            ),
				)
			)
		);
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionUpdate()
	{
		$model=$this->loadModel();
		if ($this->populate($_POST, $model) && $model->save()) {
			Yii::$app->response->redirect(array('index'));
		}
		echo $this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 */
	public function actionDelete()
	{
		if(Yii::$app->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel()->delete();
			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_POST['ajax']))
				Yii::$app->response->redirect(array('index'));
		}
		else
			throw new \yii\base\HttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$query = Comment::find()->orderBy('status, create_time DESC');

		$countQuery = clone $query;
		$pages = new Pagination($countQuery->count());

		$models = $query->offset($pages->offset)
				->limit($pages->limit)
				->with('post')
				->all();

		echo $this->render('index',array(
			'models'=>$models,
			'pages'=>$pages
		));
	}

	public function actionApprove()
	{
		$comment=$this->loadModel();
		$comment->approve();
		Yii::$app->response->redirect(array('index'));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 */
	public function loadModel()
	{
		if($this->_model===null)
		{
			if(isset($_GET['id']))
				$this->_model=Comment::find($_GET['id']);
			if($this->_model===null)
				throw new \yii\base\HttpException(404,'The requested page does not exist.');
		}
		return $this->_model;
	}
}
