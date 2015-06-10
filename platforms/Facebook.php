<?php

namespace abcms\sm\platforms;

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use abcms\sm\models\Post;
use abcms\sm\models\User;
use abcms\library\helpers\TimeHelper;

/**
 * This is the class for Facebook platform.
 */
class Facebook extends PlatformAbstract
{

    /**
     * The id of the platform in platform table
     */
    const PLATFORM_ID = 1;

    /**
     * @inheritdocs
     */
    public function savePosts()
    {
        FacebookSession::setDefaultApplication('1703104623250581', '8b86ff7e2be002af6ce473bbedae2270');
        $count = 0;
        $session = FacebookSession::newAppSession();
        $request = new FacebookRequest($session, 'GET', "/$this->accountIdentifier/feed");
        while($request) { // Read all pages
            $response = $request->execute();
            $object = $response->getGraphObject();
            $array = $object->asArray();
            if(isset($array['data']) && is_array($array['data'])) {
                foreach($array['data'] as $postData) {
                    if($this->savePost($postData)) {
                        $count++;
                    }
                    else {
                        return $count;
                    }
                }
            }
            $request = $response->getRequestForNextPage();
        }
        return $count;
    }

    /**
     * @inheritdocs
     */
    public function savePost($data)
    {
        $result = FALSE;
        if(isset($data->id, $data->updated_time, $data->from)) {
            $model = Post::findOne(['identifier' => $data->id, 'platformId' => self::PLATFORM_ID]);
            if($model) { // If already saved before
                if(strtotime($model->updatedTime) == strtotime($data->updated_time)) { // Nothing changed from the previous object saved
                    return $result;
                }
            }
            else {
                $model = new Post;
            }
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
        if(!$model->socialUserId && isset($data->from)) {
            $model->socialUserId = $this->savePostUser($data->from);
        }
        if(isset($data->message)) {
            $model->text = $data->message;
        }
        if(isset($data->picture)) {
            $model->image = $data->picture;
        }
        if(isset($data->source, $data->type) && $data->type == 'video') {
            $model->video = $data->source;
        }
        if(isset($data->link, $data->type) && $data->type == 'link') {
            $model->link = $data->link;
        }
        if(isset($data->link, $data->type) && $data->type != 'link') {
            $model->platformLink = $data->link;
        }
        if(isset($data->created_time)) {
            $model->createdTime = TimeHelper::MysqlFormat(strtotime($data->created_time));
        }
        if(isset($data->updated_time)) {
            $model->updatedTime = TimeHelper::MysqlFormat(strtotime($data->updated_time));
        }
        return $model->save(FALSE);
    }

    /**
     * Create or update \abcms\sm\models\User object from the post data
     * @param object $from
     * @return integer|NULL the id of the user in social_user table
     */
    private function savePostUser($from)
    {
        $result = NULL;
        if(isset($from->id)) {
            $user = User::findOne(['identifier' => $from->id, 'platformId' => self::PLATFORM_ID]);
            if(!$user) {
                $user = new User;
                $user->identifier = $from->id;
                $user->platformId = self::PLATFORM_ID;
                if(isset($from->name)) {
                    $user->name = $from->name;
                }
                $user->save(FALSE);
            }
            $result = $user->id;
        }
        return $result;
    }

}
