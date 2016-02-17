<?php

namespace app\controllers;

use Yii;
use app\models\Categories;
use app\models\CategoriesIndicators;
use app\models\IndicatorNames;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CategoriesController implements the CRUD actions for Categories model.
 */
class CategoriesController extends Controller
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
     * Lists all Categories models.
     * @return mixed
     */
    public function actionIndex()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Categories::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Categories model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => CategoriesIndicators::find()->where(['id_categories' => $id]),
        ]);
        return $this->render('view', [
            'model' => $this->findModel($id),
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Categories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new Categories();
        $indicatorsAvailable = IndicatorNames::find()->select(['name', 'id'])->indexBy('id')->column();
        $indicatorsPresent = [];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            foreach (Yii::$app->request->post()['Categories']['indicators'] as $id_indicator) {
                $catInd = new CategoriesIndicators();
                $catInd->id_indicator_names = $id_indicator;
                $catInd->id_categories = $model->id;
                $catInd->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'indicatorsAvailable' => $indicatorsAvailable,
                'indicatorsPresent' => $indicatorsPresent
            ]);
        }
    }

    /**
     * Updates an existing Categories model.
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

        $indicatorsAvailable = IndicatorNames::find()->select(['name', 'id'])->indexBy('id')->column();
        $indicatorsPresent = $model->getIndicators()->select(['name', 'id'])->indexBy('id')->column();

        foreach ($indicatorsPresent as $value) {
            if(($key = array_search($value, $indicatorsAvailable)) !== false) {
                unset($indicatorsAvailable[$key]);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            CategoriesIndicators::deleteAll('id_categories = '.$id);
            foreach (Yii::$app->request->post()['Categories']['indicators'] as $id_indicator) {
                $catInd = new CategoriesIndicators();
                $catInd->id_indicator_names = $id_indicator;
                $catInd->id_categories = $model->id;
                $catInd->save();
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'indicatorsAvailable' => $indicatorsAvailable,
                'indicatorsPresent' => $indicatorsPresent
            ]);
        }
    }

    /**
     * Deletes an existing Categories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Categories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Categories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Categories::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
