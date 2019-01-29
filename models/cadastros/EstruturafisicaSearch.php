<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Estruturafisica;

/**
 * EstruturafisicaSearch represents the model behind the search form about `app\models\cadastros\Estruturafisica`.
 */
class EstruturafisicaSearch extends Estruturafisica
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['estr_cod', 'estr_status'], 'integer'],
            [['estr_descricao'], 'safe'],
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
        $query = Estruturafisica::find();

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
            'estr_cod' => $this->estr_cod,
            'estr_status' => $this->estr_status,
        ]);

        $query->andFilterWhere(['like', 'estr_descricao', $this->estr_descricao]);

        return $dataProvider;
    }
}
