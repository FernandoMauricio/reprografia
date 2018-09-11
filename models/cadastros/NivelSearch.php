<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Nivel;

/**
 * NivelSearch represents the model behind the search form about `app\models\cadastros\Nivel`.
 */
class NivelSearch extends Nivel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['niv_codnivel', 'niv_status'], 'integer'],
            [['niv_descricao', 'niv_sigla'], 'safe'],
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
        $query = Nivel::find();

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
            'niv_codnivel' => $this->niv_codnivel,
            'niv_status' => $this->niv_status,
        ]);

        $query->andFilterWhere(['like', 'niv_descricao', $this->niv_descricao])
            ->andFilterWhere(['like', 'niv_sigla', $this->niv_sigla]);

        return $dataProvider;
    }
}
