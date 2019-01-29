<?php

namespace app\models\planos;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\planos\PlanoMaterial;

/**
 * PlanoMaterialSearch represents the model behind the search form about `app\models\planos\PlanoMaterial`.
 */
class PlanoMaterialSearch extends PlanoMaterial
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'plama_codplano', 'plama_codrepositorio'], 'integer'],
            [['plama_titulo', 'plama_tipoplano', 'plama_arquivo', 'plama_tipomaterial', 'plama_observacao'], 'safe'],
            [['plama_valor'], 'number'],
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
        $query = PlanoMaterial::find();

        // add conditions that should always apply here

        $dataProviderPlanoMaterial = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProviderPlanoMaterial;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'plama_codplano' => $this->plama_codplano,
            'plama_codrepositorio' => $this->plama_codrepositorio,
            'plama_valor' => $this->plama_valor,
        ]);

        $query->andFilterWhere(['like', 'plama_titulo', $this->plama_titulo])
            ->andFilterWhere(['like', 'plama_tipoplano', $this->plama_tipoplano])
            ->andFilterWhere(['like', 'plama_arquivo', $this->plama_arquivo])
            ->andFilterWhere(['like', 'plama_tipomaterial', $this->plama_tipomaterial])
            ->andFilterWhere(['like', 'plama_observacao', $this->plama_observacao]);

        return $dataProviderPlanoMaterial;
    }
}
