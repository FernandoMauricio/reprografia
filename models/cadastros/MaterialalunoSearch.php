<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Materialaluno;

/**
 * MaterialalunoSearch represents the model behind the search form about `app\models\cadastros\Materialaluno`.
 */
class MaterialalunoSearch extends Materialaluno
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matalu_cod', 'matalu_codMXM', 'matalu_status'], 'integer'],
            [['matalu_descricao', 'matalu_unidade'], 'safe'],
            [['matalu_valor'], 'number'],
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
        $query = Materialaluno::find();

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
            'matalu_cod' => $this->matalu_cod,
            'matalu_codMXM' => $this->matalu_codMXM,
            'matalu_valor' => $this->matalu_valor,
            'matalu_status' => $this->matalu_status,
        ]);

        $query->andFilterWhere(['like', 'matalu_descricao', $this->matalu_descricao])
            ->andFilterWhere(['like', 'matalu_unidade', $this->matalu_unidade]);

        return $dataProvider;
    }
}
