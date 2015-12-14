<?php

namespace abcms\sm\commands;

use yii\console\Controller;
use abcms\sm\models\Account;

class SmController extends Controller
{

    /**
     * Save all new posts for all social media accounts
     * @return integer Exit Code
     */
    public function actionIndex()
    {
        $accounts = Account::find()->andWhere(['active' => 1])->all();
        foreach($accounts as $account) {
            $smObject = $account->getSmObject();
            if($smObject) {
                $count = $smObject->savePosts();
                echo "$count posts saved for '$account->title' on $account->platformName \n";
            }
        }
        return self::EXIT_CODE_NORMAL;
    }

}
