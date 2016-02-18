<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories_indicators".
 *
 * @property integer $id
 * @property integer $id_categories
 * @property integer $id_indicator_names
 *
 * @property Categories $idCategories
 * @property IndicatorNames $idIndicatorNames
 */
class CategoriesIndicators extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories_indicators';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_categories', 'id_indicator_names'], 'required'],
            [['id_categories', 'id_indicator_names'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_categories' => 'Id Categories',
            'id_indicator_names' => 'Id Indicator Names',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'id_categories']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIndicator()
    {
        return $this->hasOne(IndicatorNames::className(), ['id' => 'id_indicator_names']);
    }
}
