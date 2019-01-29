<?php

namespace app\models\planos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\planos\PlanoEstruturafisica;

/**
 * PlanoEstruturafisicaSearch represents the model behind the search form about `app\models\planos\PlanoEstruturafisica`.
 */
class PlanoEstruturafisicaSearch extends PlanoEstruturafisica
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'planodeacao_cod', 'estruturafisica_cod', 'planestr_quantidade'], 'integer'],
            [['planestr_tipo', 'planestr_descricao'], 'safe'],
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
        $query = PlanoEstruturafisica::find();

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
            'id' => $this->id,
            'planodeacao_cod' => $this->planodeacao_cod,
            'estruturafisica_cod' => $this->estruturafisica_cod,
            'planestr_quantidade' => $this->planestr_quantidade,
        ]);

        $query->andFilterWhere(['like', 'planestr_tipo', $this->planestr_tipo])
              ->andFilterWhere(['like', 'planestr_descricao', $this->planestr_descricao]);

        return $dataProvider;
    }
}
