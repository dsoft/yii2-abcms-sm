<?php

namespace abcms\sm\models;

use Yii;

/**
 * This is the model class for table "social_user".
 *
 * @property integer $id
 * @property integer $platformId
 * @property string $identifier
 * @property string $username
 * @property string $name
 * @property string $image
 * @property string $link
 */
class User extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platformId', 'identifier', 'username', 'name'], 'required'],
            [['platformId'], 'integer'],
            [['identifier', 'link', 'image'], 'string', 'max' => 500],
            [['username', 'name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'platformId' => 'Platform',
            'identifier' => 'Identifier',
            'username' => 'Username',
            'name' => 'Name',
            'image' => 'Image',
            'link' => 'Link',
        ];
    }

    /**
     * Platform relation
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

}
