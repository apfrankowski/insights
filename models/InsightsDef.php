<?php

namespace app\models;

use Yii;
use app\models\Categories;
use app\models\IndicatorNames;

/**
 * This is the model class for table "insights_def".
 *
 * @property integer $id
 * @property string $name
 * @property integer $id_category
 * @property integer $priority
 */
class InsightsDef extends \yii\db\ActiveRecord
{

    public $name;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'insights_def';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_category'], 'required'],
            [['id_category', 'priority'], 'integer'],
            [['hospitals', 'units', 'specialities'], 'match', 'pattern' => '/^!{0,1}[0-9-]+$/' ],
            [IndicatorNames::find()->select('indicator')->column(), 'integer'],
            ['name', 'integer']
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
            'id_category' => 'Kategoria',
            'priority' => 'Priorytet',
        ];
    }


    public function loadInsightFromDef ()
    {
        $indicators = [];

        // mam model, teraz muszę pobrać treść wniosków

        foreach ($this->toArray() as $key=>$value) {
            if (!in_array($key, ['id', 'id_category', 'priority', 'hospitals', 'units', 'specialities', 'name'])) {
                $indicators[$key] = $value;
            } elseif (in_array($key, ['hospitals', 'units', 'specialities']) && $value != '') {
                if (strstr($value, '!')) {
                    $indicators[$key] = ['!'];
                } else {
                    $indicators[$key] = [];
                }
                $valuesArray = explode(',', $value);
                foreach ($valuesArray as $k=>$val) {
                    if (strstr($val, '-')) {
                        $range = explode('-', $val);
                        if ($range[0] < $range[1]) {
                            for ($i = $range[0]; $i <= $range[1]; $i++) {
                                $indicators[$key][] = $i;
                            }
                        }
                    } else {
                        $indicators[$key][] = $val;
                    }
                }
            }
        }

        // mam treść, muszę spakować je w tablicę i zwrócić
        return ['values' => $this->toArray(), 'content' => InsightsDef::_getInsights($indicators)];    
    }

    public static function loadInsightFromValues ($post)
    {
        // muszę znaleźć definicję wniosku z wartości do modelu
        $whereArray = ['and'];
        $indicators = [];
        foreach ($post as $key=>$value) {
            if (!in_array($key, ['id', 'id_category', 'priority', 'hospitals', 'units', 'specialities', 'name'])) {
                $indicators[$key] = $value;
                if ($value !== "") {
                    array_push($whereArray, "$key = $value");
                }
            } elseif (in_array($key, ['hospitals', 'units', 'specialities']) && $value != '') {
                if (strstr($value, '!')) {
                    $indicators[$key] = ['!'];
                } else {
                    $indicators[$key] = [];
                }
                $valuesArray = explode(',', $value);
                foreach ($valuesArray as $k=>$val) {
                    if (strstr($val, '-')) {
                        $range = explode('-', $val);
                        if ($range[0] < $range[1]) {
                            for ($i = $range[0]; $i <= $range[1]; $i++) {
                                $indicators[$key][] = $i;
                            }
                        }
                    } else {
                        $indicators[$key][] = $val;
                    }
                }
            }
        }
        $insight = InsightsDef::find()->where($whereArray)->one();
        // mam model, teraz muszę pobrać treść wniosków

        return ['content' => InsightsDef::_getInsights($indicators)];    
        // mam treść, muszę spakować w wszystko w tablicę (lub obiekt) i zwrócić

    }

    private static function _getInsights($indicators)
    {

        $returnArray = ['exact' => ['content' => '', 'id' => 0, 'defId' => 0], 'general' => []];
        $whereArray = ['and'];
        $whereArrayExact = ['and'];

        foreach ($indicators as $key=>$value) {
            if (in_array($key, ['hospitals', 'units', 'specialities'])) {
                $negation = 0;
                if ($value[0] == '!') {
                    array_shift($value);
                    $negation = 1;
                }
                if (count($value) == 1) {
                    if ($negation) {
                        array_push($whereArray, "$key != $value[0]");
                        array_push($whereArrayExact, "$key != $value[0]");
                    } else {
                        array_push($whereArray, "$key = $value[0]");
                        array_push($whereArrayExact, "$key = $value[0]");
                    }
                } else {
                    if ($negation) {
                        array_push($whereArray, "$key NOT IN (".implode(',',$value).")");
                        array_push($whereArrayExact, "$key NOT IN (".implode(',',$value).")");
                    } else {
                        array_push($whereArray, "$key IN (".implode(',',$value).")");
                        array_push($whereArrayExact, "$key IN (".implode(',',$value).")");
                    }
                }
            } else {
                if ($value !== '' && $value !== null) {
                    array_push($whereArray, ['or', "$key = $value", "$key IS NULL"]);
                    array_push($whereArrayExact, "$key = $value");
                } else {
                    array_push($whereArray, "$key IS NULL");
                    array_push($whereArrayExact, "$key IS NULL");
                }
            }
        }

        if ($insightExact = InsightsDef::find()->where($whereArrayExact)->one()) {
            foreach ($insightExact->content as $insightLine) {
                // array_push ($returnArray['exact'], $insightLine->content);
                $returnArray['exact']['content'] = $insightLine->content;
                $returnArray['exact']['id'] = $insightLine->id;
                $returnArray['exact']['defId'] = $insightExact->id;
            }
        }

        if ($insights = InsightsDef::find()->where($whereArray)->orderBy('priority')->all()) {
            foreach ($insights as $insight) {
                if (!is_object($insightExact) || $insight->id != $insightExact->id) {
                    foreach ($insight->content as $insightLine) {
                        array_push ($returnArray['general'], $insightLine->content);
                    }
                }
            }
        }
        return $returnArray;
    }

    public function getCategory()
    {
        return $this->hasOne(Categories::className(), ['id' => 'id_category']);
    }

    public function getContent()
    {
        return $this->hasMany(InsightsContent::className(), ['id_insights_def' => 'id']);
    }
}
