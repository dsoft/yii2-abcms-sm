<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model abcms\sm\models\Post */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'identifier',
            [
                'attribute' => 'platformId',
                'value' => $model->platformName,
            ],
            [
                'attribute' => 'socialUserId',
                'value' => $model->userName,
            ],
            [
                'attribute' => 'accountId',
                'value' => $model->accountTitle,
            ],
            'text',
            [
                'attribute' => 'image',
                'format' => ['image', ['width' => 350]],
            ],
            'image:url',
            'video:url',
            'link:url',
            'platformLink:url',
            'createdTime',
            'updatedTime',
            'active:boolean',
        ],
    ])
    ?>

</div>
