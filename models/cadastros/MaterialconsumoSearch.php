<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Materialconsumo;

/**
 * MaterialconsumoSearch represents the model behind the search form about `app\models\cadastros\Materialconsumo`.
 */
class MaterialconsumoSearch extends Materialconsumo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matcon_codMXM', 'matcon_status'], 'integer'],
            [['matcon_descricao', 'matcon_tipo'], 'safe'],
            [['matcon_valor'], 'number'],
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
        $query = Materialconsumo::find();

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
            'matcon_codMXM' => $this->matcon_codMXM,
            'matcon_valor' => $this->matcon_valor,
            'matcon_status' => $this->matcon_status,
        ]);

        $query->andFilterWhere(['like', 'matcon_descricao', $this->matcon_descricao])
            ->andFilterWhere(['like', 'matcon_tipo', $this->matcon_tipo]);

        return $dataProvider;
    }
}
