<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
//namespace app\commands;
namespace app\controllers;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
/**
 * NoteController implements the CRUD actions for Note model.
@@ -95,6 +96,23 @@ public function actionUpdate($id)
        ]);
    }*/
class NoteController extends Controller {
	public function behaviours()
	{
		return [
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'noteAccess' => [
				'class' => NoteAccessBehaviour::className(),
				'except' => ['index','list'],
				'rules' => [
					['allow' => true, 'roles' => ['0']],
				],
			],
			'access' => [
				'class' => AccessControl::class(),
				'only' => ['index'],
				'rules' => [
					['allow' => true, 'roles' => ['0']],
				],
			],			
		];	
	}
	public function actionIndex()
	{
		$searchModel = new NoteSearch();
		$dataProvider = $searchModel -> search(Y$app->request->queryParams);
		
		$viewModel -> new NoteView();
		
		return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
			'viewModel' => $viewModel, 
		]);
		
	}
	
	public function actionJson(int $id): array
	{
		\Yii::$app->getResponse()->format = Response::FORMAT_JSON;
		$note = $this->findModel($id);
		return $note->toArray();
	}
}
    /**
     * Deletes an existing Note model.
     * If deletion is successful, the browser will be redirected to the 'index' page.