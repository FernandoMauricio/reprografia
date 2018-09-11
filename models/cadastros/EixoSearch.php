<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Eixo;

/**
 * EixoSearch represents the model behind the search form about `app\models\cadastros\Eixo`.
 */
class EixoSearch extends Eixo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eix_codeixo', 'eix_status'], 'integer'],
            [['eix_descricao'], 'safe'],
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
        $query = Eixo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'eix_codeixo' => $this->eix_codeixo,
            'eix_status' => $this->eix_status,
        ]);

        $query->andFilterWhere(['like', 'eix_descricao', $this->eix_descricao]);

        return $dataProvider;
    }
}
