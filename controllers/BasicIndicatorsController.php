<?php

namespace app\controllers;

use Yii;
use app\models\BasicIndicators;
use app\models\Hospitals;
use app\models\Divisions;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BasicIndicatorsController implements the CRUD actions for BasicIndicators model.
 */
class BasicIndicatorsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all BasicIndicators models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => BasicIndicators::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BasicIndicators model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BasicIndicators model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new BasicIndicators();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Yii::$app->db->createCommand()
            //     ->addColumn(Hospitals::tableName(), $model->name, 'decimal(12,2)')
            //     ->execute();
            Yii::$app->db->createCommand()
                ->addColumn(Divisions::tableName(), $model->name, 'decimal(12,2)')
                ->execute();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing BasicIndicators model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BasicIndicators model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = $this->findModel($id);
        // Yii::$app->db->createCommand()
        //     ->dropColumn(Hospitals::tableName(), $model->name)
        //     ->execute();
        Yii::$app->db->createCommand()
            ->dropColumn(Divisions::tableName(), $model->name)
            ->execute();
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the BasicIndicators model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BasicIndicators the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BasicIndicators::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
