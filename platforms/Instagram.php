<?php

namespace abcms\sm\platforms;

use abcms\sm\models\Post;
use abcms\sm\models\User;
use abcms\library\helpers\TimeHelper;

/**
 * This is the class for Instagram platform.
 */
class Instagram extends PlatformAbstract
{

    /**
     * The id of the platform in platform table
     */
    const PLATFORM_ID = 2;

    /**
     * @inheritdocs
     */
    public function savePosts()
    {
        $instagram = new \MetzWeb\Instagram\Instagram(array(
            'apiKey' => '440ef1f4a6a64c87847a879206c1989d',
            'apiSecret' => 'c86549b31fe14c1fabe349715ac88bd0',
            'apiCallback' => ''
        ));
        $count = 0;
        $result = $instagram->getUserMedia($this->accountIdentifier);
        while($result) {
            if(isset($result->data) && is_array($result->data)) {
                foreach($result->data as $postData) {
                    if($this->savePost($postData)) {
                        $count++;
                    }
                    else {
                        return $count;
                    }
                }
            }
            $result = $instagram->pagination($result);
        }
        return $count;
    }

    /**
     * @inheritdocs
     */
    public function savePost($data)
    {
        $result = FALSE;
        if(isset($data->id, $data->user)) {
            $model = Post::findOne(['identifier' => $data->id, 'platformId' => self::PLATFORM_ID]);
            if($model) { // If already saved before, return False because we can't know if updated or no
                return $result;
            }
            $model = new Post;
            $result = $this->saveModel($model, $data);
        }
        return $result;
    }

    /**
     * Save data to the post model provided
     * @param \abcms\sm\models\Post $model
     * @param object $data
     * @return bool
     */
    private function saveModel($model, $data)
    {
        $model->identifier = $data->id;
        $model->platformId = self::PLATFORM_ID;
        $model->accountId = $this->accountId;
        if(!$model->socialUserId && isset($data->user)) {
            $model->socialUserId = $this->savePostUser($data->user);
        }
        if(isset($data->caption->text)) {
            $model->text = $data->caption->text;
        }
        if(isset($data->images->standard_resolution->url)) {
            $model->image = $data->images->standard_resolution->url;
        }
        if(isset($data->videos->standard_resolution->url)) {
            $model->video = $data->videos->standard_resolution->url;
        }
        if(isset($data->link)) {
            $model->platformLink = $data->link;
        }
        if(isset($data->created_time)) {
            $time = TimeHelper::MysqlFormat($data->created_time);
            $model->createdTime = $time;
            $model->updatedTime = $time;
        }
        return $model->save(FALSE);
    }

    /**
     * Create or update \abcms\sm\models\User object from the post data
     * @param object $from
     * @return integer|NULL the id of the user in social_user table
     */
    private function savePostUser($data)
    {
        $result = NULL;
        if(isset($data->id)) {
            $user = User::findOne(['identifier' => $data->id, 'platformId' => self::PLATFORM_ID]);
            if(!$user) {
                $user = new User;
                $user->identifier = $data->id;
                $user->platformId = self::PLATFORM_ID;
                if(isset($data->full_name)) {
                    $user->name = $data->full_name;
                }
                if(isset($data->username)) {
                    $user->username = $data->username;
                }
                if(isset($data->profile_picture)) {
                    $user->image = $data->profile_picture;
                }
                $user->save(FALSE);
            }
            $result = $user->id;
        }
        return $result;
    }

}
