<?php

namespace abcms\sm\models;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "social_post".
 *
 * @property integer $id
 * @property string $identifier
 * @property integer $platformId
 * @property integer $socialUserId
 * @property integer $accountId
 * @property string $text
 * @property string $image
 * @property string $video
 * @property string $link
 * @property string $platformLink
 * @property string $createdTime
 * @property string $updatedTime
 * @property integer $active
 */
class Post extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'social_post';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['platformId', 'socialUserId', 'accountId', 'createdTime', 'updatedTime'], 'required'],
            [['platformId', 'socialUserId', 'accountId', 'active'], 'integer'],
            [['text'], 'string'],
            [['createdTime', 'updatedTime'], 'safe'],
            [['identifier'], 'string', 'max' => 255],
            [['video', 'link', 'platformLink', 'image'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'identifier' => 'Identifier',
            'platformId' => 'Platform',
            'socialUserId' => 'Social User',
            'accountId' => 'Account',
            'text' => 'Text',
            'image' => 'Image',
            'video' => 'Video',
            'link' => 'Link',
            'platformLink' => 'Platform Link',
            'createdTime' => 'Created Time',
            'updatedTime' => 'Updated Time',
            'active' => 'Active',
        ];
    }

    /**
     * @inheritdoc
     * @return PostQuery the newly created [[PostQuery]] instance.
     */
    public static function find()
    {
        return new PostQuery(get_called_class());
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

    /**
     * User reltion
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'socialUserId']);
    }

    /**
     * User names
     * @return string
     */
    public function getUserName()
    {
        return ($this->user) ? $this->user->name : NULL;
    }

    /**
     * Account relation
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['id' => 'accountId']);
    }

    /**
     * Account title
     * @return string
     */
    public function getAccountTitle()
    {
        return ($this->account) ? $this->account->title : NULL;
    }

    /**
     * Activate or Deactivate Model
     * @return ActiveRecord current model
     */
    public function activate()
    {
        if($this->active == 1) {
            $this->active = 0;
        }
        else {
            $this->active = 1;
        }
        return $this;
    }

}

class PostQuery extends ActiveQuery
{

    public function init()
    {
        $orderBy = 'updatedTime DESC';
        $this->orderBy($orderBy);
        parent::init();
    }

    public function active($state = true)
    {
        return $this->andWhere(['active' => $state]);
    }

}
