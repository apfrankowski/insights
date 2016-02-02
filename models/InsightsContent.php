<?php

namespace app\models;

use Yii;
use app\models\InsightsDef;

/**
 * This is the model class for table "insights_content".
 *
 * @property integer $id
 * @property string $name
 * @property string $lang
 * @property string $content
 * @property integer $usage_counter
 */
class InsightsContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'insights_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content', 'usage_counter'], 'required'],
            [['content'], 'string'],
            [['usage_counter'], 'integer'],
            [['name'], 'string', 'max' => 45],
            [['lang'], 'string', 'max' => 2]
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
            'lang' => 'Lang',
            'content' => 'Treść',
            'usage_counter' => 'Usage Counter',
        ];
    }

    public function getInsight()
    {
        return $this->hasOne(InsightsDef::className(), ['name' => 'name']);
    }
}
