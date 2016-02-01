<?php

namespace app\models;

use Yii;
use app\models\Categories;

/**
 * This is the model class for table "insights_def".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_category
 * @property integer $priority
 */
class InsightsDef extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'insights_def';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'id_category'], 'required'],
            [['id_category', 'priority'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['roi2benchmark', 'profit', 'income2benchmark', 'costs2benchmark'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nazwa wniosku',
            'id_category' => 'Kategoria',
            'priority' => 'Priorytet',
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'id_category']);
    }

    public function getContent()
    {
        return $this->hasMany(InsightsContent::className(), ['name' => 'name']);
    }
}
