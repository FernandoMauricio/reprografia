<?php

namespace app\models\solicitacoes;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\solicitacoes\MaterialCopiasPendentes;

/**
 * MaterialCopiasPendentesSearch represents the model behind the search form about `app\models\solicitacoes\MaterialCopiasPendentes`.
 */
class MaterialCopiasPendentesSearch extends MaterialCopiasPendentes
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matc_id', 'matc_totalValorMono', 'matc_totalValorColor'], 'integer'],
            [['matc_curso', 'matc_centrocusto', 'matc_unidade', 'matc_solicitante', 'matc_data', 'matc_ResponsavelAut', 'matc_dataAut', 'situacao_id'], 'safe'],
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
        $query = MaterialCopiasPendentes::find()->orderBy(['matc_id' => SORT_DESC]);

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

        $query->joinWith('situacao');

        // grid filtering conditions
        $query->andFilterWhere([
            'matc_id' => $this->matc_id,
            'matc_data' => $this->matc_data,
            'matc_totalValorMono' => $this->matc_totalValorMono,
            'matc_totalValorColor' => $this->matc_totalValorColor,
            'matc_dataAut' => $this->matc_dataAut,
            'matc_dataRepro' => $this->matc_dataRepro,
            'situacao_id' => 7, //AUTORIZADO PELO SETOR RESPONSÁVEL
        ]);

        $query->andFilterWhere(['like', 'matc_curso', $this->matc_curso])
            ->andFilterWhere(['like', 'matc_centrocusto', $this->matc_centrocusto])
            ->andFilterWhere(['like', 'matc_unidade', $this->matc_unidade])
            ->andFilterWhere(['like', 'matc_solicitante', $this->matc_solicitante])
            ->andFilterWhere(['like', 'matc_ResponsavelAut', $this->matc_ResponsavelAut])
            ->andFilterWhere(['=', 'situacaomatcopias_sitmat.sitmat_descricao', $this->situacao_id]);

        return $dataProvider;
    }
}
