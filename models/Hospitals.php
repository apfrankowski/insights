<?php

namespace app\models;

use Yii;
use app\models\BasicIndicators;

/**
 * This is the model class for table "hospitals".
 *
 * @property integer $id
 */
class Hospitals extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hospitals';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'name'], 'required'],
            [['id'], 'integer'],
            [['name'], 'string'],
            [BasicIndicators::find()->select('name')->column(), 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Nazwa'
        ];
    }
}
