<?php

namespace app\models\repositorio;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\repositorio\Tipomaterial;

/**
 * TipomaterialSearch represents the model behind the search form about `app\models\repositorio\Tipomaterial`.
 */
class TipomaterialSearch extends Tipomaterial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tip_codtipo'], 'integer'],
            [['tip_descricao', 'tip_elementodespesa_id'], 'safe'],
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
        $query = Tipomaterial::find();

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

        $query->joinWith('elementodespesa');

        // grid filtering conditions
        $query->andFilterWhere([
            'tip_codtipo' => $this->tip_codtipo,
        ]);

        $query->andFilterWhere(['like', 'tip_descricao', $this->tip_descricao])
              ->andFilterWhere(['like', 'elementodespesa_eled.eled_despesa', $this->tip_elementodespesa_id]);

        return $dataProvider;
    }
}
