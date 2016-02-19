<?php

namespace app\controllers;

use Yii;
use app\models\InsightsDef;
use app\models\InsightsContent;
use app\models\IndicatorNames;
use app\models\Categories;
use app\models\CategoriesIndicators;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;

/**
 * InsightsDefController implements the CRUD actions for InsightsDef model.
 */
class InsightsDefController extends Controller
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
     * Lists all InsightsDef models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => InsightsDef::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single InsightsDef model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new InsightsDef model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idCategory = 4, $id = 0)
    {
        $model = new InsightsDef();
        $content = new InsightsContent();

        $model->name = $id;
        $model->id_category = $idCategory;

        if ($model->load(Yii::$app->request->post())) {
            $priorityCounter = 0;
            foreach (Yii::$app->request->post()['InsightsDef'] as $key => $value) {
                if (!in_array($key, ['id', 'id_category', 'priority', 'hospitals', 'units', 'specialities', 'name']) && $value != '') {
                    $priorityCounter++;
                }
            }
            $model->priority = $priorityCounter;
            $model->save();
            $content->id_insights_def = $model->id;
            $content->content = Yii::$app->request->post()['InsightsContent']['content'];
            $content->lang = 'pl';
            $content->save();
            return $this->redirect(['create', 'idCategory' => $model->id_category, 'id' => $model->id]);
        } else {
            $categories = Categories::find()->select(['name', 'id'])->indexBy('id')->column();
            $indicatorNames = CategoriesIndicators::find()->where('id_categories = '.$idCategory)->all();

            $insights = InsightsDef::find()->where('id_category = '.$idCategory)->all();
            $insightsCollection = [0 => ''];
            foreach ($insights as $insight) {
                $name = '';
                foreach ($insight->toArray() as $key=>$value) {
                    if (!in_array($key, ['id', 'id_category', 'priority', 'hospitals', 'units', 'specialities']) && $value !== null) {
                        $name .= $key.'('.$value.')';
                    }
                }
                $insightsCollection[$insight->id] = $name;
            }
            return $this->render('create', [
                'model' => $model,
                'content' => $content,
                'categories' => $categories,
                'indicatorNames' => $indicatorNames,
                'insights' => $insightsCollection
            ]);
        }
    }

    public function actionGetInsights() 
    {

        if ($post = Yii::$app->request->post()) {
            if ($post['InsightsDef']['name'] > 0) {
                // load predefined insights
                $model = $this->findModel($post['InsightsDef']['name']);
                return Json::htmlEncode($model->loadInsightFromDef());
            } else {
                $post['InsightsDef']['name'] = '';
                // load insights from other values
                return Json::htmlEncode(InsightsDef::loadInsightFromValues($post['InsightsDef']));
            }
        } else {
            return $this->redirect(['create']);
        }
    }

    /**
     * Updates an existing InsightsDef model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate()
    {
        $post = Yii::$app->request->post();

        $model = $this->findModel($post['InsightsDef']['name']);
        $content = InsightsContent::findOne($post['InsightsContent']['id']);

        if ($model->load($post)) {
            $priorityCounter = 0;
            foreach ($post['InsightsDef'] as $key => $value) {
                if (!in_array($key, ['id', 'id_category', 'priority', 'hospitals', 'units', 'specialities', 'name']) && $value != '') {
                    $priorityCounter++;
                }
            }
            $model->priority = $priorityCounter;
            $model->save();
            $content->load($post);
            $content->save();
        }
        return $this->redirect(['create', 'idCategory' => $model->id_category, 'id' => $model->id]);
    }

    /**
     * Deletes an existing InsightsDef model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete()
    {
        $post = Yii::$app->request->post();

        InsightsContent::findOne($post['InsightsContent']['id'])->delete();
        $this->findModel($post['InsightsDef']['name'])->delete();

        return $this->redirect(['create']);
    }

    /**
     * Finds the InsightsDef model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return InsightsDef the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = InsightsDef::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
