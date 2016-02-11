<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "basic_indicators".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 */
class BasicIndicators extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'basic_indicators';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 45],
            [['unit'], 'string', 'max' => 45],
            [['name'], 'match', 'pattern' => '/^[a-zA-Z0-9_-]+$/', 'message' =>
                'Nazwa wskaźnika może składac się tylko ze znaków alfanumerycznych, podkreślenia i pauzy'],
            [['description'], 'string', 'max' => 255],
            [['name'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Wskaźnik',
            'description' => 'Opis',
            'unit' => 'Jednostka'
        ];
    }

}
