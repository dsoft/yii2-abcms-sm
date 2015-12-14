<?php

namespace abcms\sm\platforms;

/**
 * This is the class for Instagram platform.
 */
class InstagramTag extends Instagram
{

    /**
     * The id of the platform in platform table
     */
    const PLATFORM_ID = 3;

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
        $result = $instagram->getTagMedia($this->accountIdentifier, 100);
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

}
