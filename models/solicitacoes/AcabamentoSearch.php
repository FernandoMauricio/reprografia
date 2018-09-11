<?php

namespace app\models\solicitacoes;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\solicitacoes\Acabamento;

/**
 * AcabamentoSearch represents the model behind the search form about `app\models\solicitacoes\Acabamento`.
 */
class AcabamentoSearch extends Acabamento
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'acab_status'], 'integer'],
            [['acab_descricao'], 'safe'],
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
        $query = Acabamento::find();

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
            'acab_status' => $this->acab_status,
        ]);

        $query->andFilterWhere(['like', 'acab_descricao', $this->acab_descricao]);

        return $dataProvider;
    }
}
