<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Tipo;

/**
 * TipoSearch represents the model behind the search form about `app\models\cadastros\Tipo`.
 */
class TipoSearch extends Tipo
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tip_codtipoa', 'tip_status'], 'integer'],
            [['tip_descricao', 'tip_sigla'], 'safe'],
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
        $query = Tipo::find();

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
            'tip_codtipoa' => $this->tip_codtipoa,
            'tip_status' => $this->tip_status,
        ]);

        $query->andFilterWhere(['like', 'tip_descricao', $this->tip_descricao])
            ->andFilterWhere(['like', 'tip_sigla', $this->tip_sigla]);

        return $dataProvider;
    }
}
