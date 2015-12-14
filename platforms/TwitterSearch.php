<?php

namespace abcms\sm\platforms;

use abcms\sm\models\Post;
use abcms\sm\models\User;
use abcms\library\helpers\TimeHelper;
use TwitterAPIExchange;

/**
 * This is the class for Instagram platform.
 */
class TwitterSearch extends PlatformAbstract
{

    /**
     * The id of the platform in platform table
     */
    const PLATFORM_ID = 4;

    /**
     * @inheritdocs
     */
    public function savePosts()
    {
        $count = 0;
        $settings = array(
            'oauth_access_token' => "1580498666-7oI1idhyxmsEozd9t8LFWX2IVbT4BCwidxK8hvf",
            'oauth_access_token_secret' => "vnHt9SpRGvFNx6m9RlZP16yLnYUK2gdLQfdILrE0bR4cP",
            'consumer_key' => "a1IkvFIXumVZLtLqFxnZTQJY5",
            'consumer_secret' => "I2YMl4ekBqG8Zgs5Ja5tbhaMLqDoA6OM9GFsj2plUcJmlsenOd"
        );
        $url = 'https://api.twitter.com/1.1/search/tweets.json';
        $requestMethod = 'GET';
        $getfield = '?q='.$this->accountIdentifier;
        $twitter = new TwitterAPIExchange($settings);
        $result = $twitter->setGetfield($getfield)
                ->buildOauth($url, $requestMethod)
                ->performRequest(true, [CURLOPT_SSL_VERIFYPEER => false]);
        $result = json_decode($result);
        if(isset($result->statuses) && is_array($result->statuses)) {
            foreach($result->statuses as $postData) {
                if($this->savePost($postData)) {
                    $count++;
                }
                else {
                    return $count;
                }
            }
        }

        return $count;
    }

    /**
     * @inheritdocs
     */
    public function savePost($data)
    {
        $result = FALSE;
        if(isset($data->id_str, $data->user)) {
            $model = Post::findOne(['identifier' => $data->id_str, 'platformId' => self::PLATFORM_ID]);
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
        $model->identifier = $data->id_str;
        $model->platformId = self::PLATFORM_ID;
        $model->accountId = $this->accountId;
        if(!$model->socialUserId && isset($data->user)) {
            $model->socialUserId = $this->savePostUser($data->user);
        }
        if(isset($data->text)) {
            $model->text = $data->text;
        }
        if(isset($data->entities->media[0])) {
            $media = $data->entities->media[0];
            if($media->type == 'photo'){
                $model->image = $media->media_url;
            }     
        }
        if(isset($data->entities->urls[0]->url)) {
            $model->link = $data->entities->urls[0]->url;
        }
        if(isset($data->created_at)) {
            $time = TimeHelper::MysqlFormat(strtotime($data->created_at));
            $model->createdTime = $time;
            $model->updatedTime = $time;
        }
        return $model->save(false);
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
            $user = User::findOne(['identifier' => $data->id_str, 'platformId' => self::PLATFORM_ID]);
            if(!$user) {
                $user = new User;
                $user->identifier = $data->id_str;
                $user->platformId = self::PLATFORM_ID;
                if(isset($data->name)) {
                    $user->name = $data->name;
                }
                if(isset($data->screen_name)) {
                    $user->username = $data->screen_name;
                }
                if(isset($data->profile_image_url)) {
                    $user->image = $data->profile_image_url;
                }
                $user->save(false);
            }
            $result = $user->id;
        }
        return $result;
    }

}
