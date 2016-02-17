<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $type
 * @property string $name
 */
class Categories extends \yii\db\ActiveRecord
{

    public $indicator;

    public $indicators_available;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'name'], 'required'],
            [['type'], 'string', 'max' => 45],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Typ',
            'name' => 'Kategoria',
        ];
    }

    public function getIndicators()
    {
        return $this->hasMany(IndicatorNames::className(), ['id' => 'id_indicator_names'])
            ->viaTable('categories_indicators', ['id_categories' => 'id']);
    }
}
