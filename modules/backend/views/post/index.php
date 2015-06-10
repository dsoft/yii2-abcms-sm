<?php

use yii\helpers\Html;
use yii\grid\GridView;
use abcms\sm\models\Platform;

/* @var $this yii\web\View */
/* @var $searchModel abcms\sm\models\PostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Social Media Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            [
                'attribute' => 'platformId',
                'value' => function($data) {
            return $data->platformName;
        },
                'filter' => Platform::listAll(),
            ],
            [
                'attribute' => 'socialUserId',
                'value' => function($data) {
            return $data->userName;
        },
            ],
            'text',
            [
              'attribute' => 'image',
              'format' => ['image', ['width' => 160]],
            ],
            'updatedTime',
            ['class' => 'abcms\library\grid\ActivateColumn'],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
            ],
        ],
    ]);
    ?>

</div>
