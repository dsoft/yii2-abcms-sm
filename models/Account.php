<?php

namespace abcms\sm\models;

use Yii;
use abcms\library\base\BackendActiveRecord;
use yii\helpers\Inflector;

/**
 * This is the model class for table "social_account".
 *
 * @property integer $id
 * @property string $title
 * @property integer $platformId
 * @property string $link
 * @property string $identifier
 * @property integer $active
 * @property integer $deleted
 */
class Account extends BackendActiveRecord
{

    public static $enableOrdering = false;
    public static $enableTime = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'platformId', 'link', 'identifier'], 'required'],
            [['platformId', 'active'], 'integer'],
            [['title', 'link'], 'string', 'max' => 255],
            [['identifier'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'platformId' => 'Platform',
            'link' => 'Link',
            'identifier' => 'Identifier',
            'active' => 'Active',
            'deleted' => 'Deleted',
        ];
    }

    /**
     * Paltform relation
     * @return \yii\db\ActiveQuery
     */
    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'platformId']);
    }

    /**
     * Platform name
     * @return string
     */
    public function getPlatformName()
    {
        return ($this->platform) ? $this->platform->name : NULL;
    }

    /**
     * Return the SM Platform Object related to each account
     * The object that will be used to save posts and other actions specific for each platform
     * @return \abcms\sm\platforms\PlatformAbstract
     */
    public function getSmObject()
    {
        $smObject = null;
        $platform = $this->platform;
        if($platform) {
            $class = Inflector::camelize($platform->name);
            $config = [
                'class' => '\abcms\sm\platforms\\'.$class,
                'accountId' => $this->id,
                'accountIdentifier' => $this->identifier,
            ];
            $smObject = Yii::createObject($config);
        }
        return $smObject;
    }

}
