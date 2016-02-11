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
            [['indicator', 'name', 'numerator'], 'required'],
            [['indicator'], 'string', 'max' => 45],
            [['indicator'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' =>
                'Nazwa wskaźnika może składac się tylko ze znaków alfanumerycznych, podkreślenia i pauzy'],
            [['lang'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 100],
            [['numerator', 'denominator'], 'integer'],
            [['denominator_dec'], 'number']
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
            'lang' => 'Język',
            'name' => 'Opis',
            'numerator' => 'Licznik',
            'denominator' => 'Mianownik',
            'denominator_dec' => 'Stały mianownik'
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
