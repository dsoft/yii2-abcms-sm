<?php

use yii\helpers\Html;
use yii\grid\GridView;
use abcms\sm\models\Platform;

/* @var $this yii\web\View */
/* @var $searchModel abcms\sm\models\AccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Accounts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="account-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Account', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'title',
            [
                'attribute' => 'platformId',
                'value' => function($data) {
            return $data->platformName;
        },
                'filter' => Platform::listAll(),
            ],
            'link:url',
            'identifier',
            [
                'class' => abcms\library\grid\ActivateColumn::className(),
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
    ?>

</div>
