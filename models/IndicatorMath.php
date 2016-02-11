<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicator_math".
 *
 * @property integer $id
 * @property string $indicator
 * @property integer $id_hospital
 * @property integer $id_division
 * @property string $minus2
 * @property string $minus1
 * @property string $plus1
 * @property string $plus2
 */
class IndicatorMath extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indicator_math';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['indicator'], 'required'],
            [['id_hospital', 'id_division'], 'integer'],
            [['minus2', 'minus1', 'plus1', 'plus2'], 'number'],
            [['indicator'], 'string', 'max' => 45],
            [['id_specjalizacja'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'indicator' => 'Wskaźnik',
            'id_hospital' => 'Id szpitala',
            'id_division' => 'Id oddziału',
            'id_specjalizacja' => 'Id specjalizacji',
            'minus2' => 'Minus2',
            'minus1' => 'Minus1',
            'plus1' => 'Plus1',
            'plus2' => 'Plus2',
        ];
    }


    /**
     * @inheritdoc
     */    
    public function getIndicators()
    {
        return $this->hasOne(IndicatorNames::className(), ['indicator' => 'indicator']);
    }
}
