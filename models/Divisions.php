<?php

namespace app\models;

use Yii;
use app\models\BasicIndicators;

/**
 * This is the model class for table "hospitals".
 *
 * @property integer $id
 */
class Divisions extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'divisions';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nazwa', 'id_szpital', 'id_specjalizacja', 'id_data', 'id_oddzial'], 'required'],
            [['id_szpital', 'id_specjalizacja', 'id_data', 'id_oddzial'], 'integer'],
            [['nazwa'], 'string'],
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
            'nazwa' => 'Nazwa',
            'id_szpital' => 'Szpital',
            'id_specjalizacja' => 'Specjalność',
            'id_data' => 'Zakres raportu',
            'id_oddzial' => 'Oddział'
        ];
    }
}
