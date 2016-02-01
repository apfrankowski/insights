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
            [['indicator', 'name'], 'required'],
            [['indicator'], 'string', 'max' => 45],
            [['lang'], 'string', 'max' => 2],
            [['name'], 'string', 'max' => 100]
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
        ];
    }

    public function getMaths()
    {
        return $this->hasMany(IndicatorMath::className(), ['indicator' => 'indicator']);
    }
}
