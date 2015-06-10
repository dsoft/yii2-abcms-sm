<?php

namespace abcms\sm\platforms;


/**
 * This is the abstract class for all platforms classes
 * These classes should include functions to save posts from each platform
 * In addition to other actions related to each platform
 */
abstract class PlatformAbstract
{
    
    /**
     * The account ID in social_account table
     * @var mixed
     */
    public $accountId;
    
    /**
     * The page or account ID related to each platform: like Facebook Page ID
     * @var mixed
     */
    public $accountIdentifier;
    
    /**
     * Function called to get posts from each platform and save it
     * @return integer Number of posts saved
     */
    abstract public function savePosts();
    
    /**
     * Function called to update or create an individual post from the data received
     * @var array|object the post data received from the platform
     */
    abstract public function savePost($data);
}
