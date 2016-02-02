<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "indicator_names".
 *
 * @property integer $id
 * @property string $indicator
 * @property string $lang
 * @property string $name
 */
class IndicatorNames extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'indicator_names';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['indicator', 'name', 'numerator', 'denominator'], 'required'],
            [['indicator'], 'string', 'max' => 45],
            [['indicator'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' =>
                'Nazwa wskaźnika może składac się tylko ze znaków alfanumerycznych, podkreślenia i pauzy'],
            [['lang'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 100],
            [['numerator', 'denominator'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'indicator' => 'Indicator',
            'lang' => 'Lang',
            'name' => 'Name',
            'numerator' => 'Licznik',
            'denominator' => 'Mianownik'
        ];
    }

    public function getMaths()
    {
        return $this->hasMany(IndicatorMath::className(), ['indicator' => 'indicator']);
    }

    public function getBasicNumerator()
    {
        return $this->hasOne(BasicIndicators::className(), ['id' => 'numerator']);
    }

    public function getBasicDenominator()
    {
        return $this->hasOne(BasicIndicators::className(), ['id' => 'denominator']);
    }
}
