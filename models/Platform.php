<?php

namespace abcms\sm\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "social_platform".
 *
 * @property integer $id
 * @property string $name
 */
class Platform extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_platform';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 50]
        ];
    }
    
    /**
     * Return array of all platforms, can be used in drop down lists
     * @return array
     */
    public static function listAll(){
        $all = ArrayHelper::map(self::find()->all(), 'id', 'name');
        return $all;
    }
}
