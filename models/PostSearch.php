<?php

namespace abcms\sm\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use abcms\sm\models\Post;

/**
 * PostSearch represents the model behind the search form about `abcms\sm\models\Post`.
 */
class PostSearch extends Post
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'platformId', 'socialUserId', 'accountId', 'active'], 'integer'],
            [['identifier', 'text', 'image', 'video', 'link', 'platformLink', 'createdTime', 'updatedTime'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Post::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'platformId' => $this->platformId,
            'socialUserId' => $this->socialUserId,
            'accountId' => $this->accountId,
            'createdTime' => $this->createdTime,
            'updatedTime' => $this->updatedTime,
            'active' => $this->active,
        ]);

        $query->andFilterWhere(['like', 'identifier', $this->identifier])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'video', $this->video])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'platformLink', $this->platformLink]);

        return $dataProvider;
    }
}
