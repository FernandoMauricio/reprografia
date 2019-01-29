<?php

namespace app\models\cadastros;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\cadastros\Centrocusto;

/**
 * CentrocustoSearch represents the model behind the search form about `app\models\cadastros\Centrocusto`.
 */
class CentrocustoSearch extends Centrocusto
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cen_codcentrocusto', 'cen_coddepartamento', 'cen_codsituacao', 'cen_codunidade', 'cen_codsegmento', 'cen_codtipoacao', 'cen_codano'], 'integer'],
            [['cen_centrocusto', 'cen_nomecentrocusto', 'cen_centrocustoreduzido', 'nomeUnidade'], 'safe'],
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
        $query = Centrocusto::find()->where(['in', 'cen_codano', [date('Y'),date('Y') + 1]])->orderBy(['cen_codcentrocusto' => SORT_DESC]);;

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
            'cen_codcentrocusto' => $this->cen_codcentrocusto,
            'cen_coddepartamento' => $this->cen_coddepartamento,
            'cen_codsituacao' => $this->cen_codsituacao,
            'cen_codunidade' => $this->cen_codunidade,
            'cen_codsegmento' => $this->cen_codsegmento,
            'cen_codtipoacao' => $this->cen_codtipoacao,
            'cen_codano' => $this->cen_codano,
        ]);

        $query->joinWith('unidade');

        $query->andFilterWhere(['like', 'unidade_uni.uni_nomeabreviado', $this->nomeUnidade])
            ->andFilterWhere(['like', 'cen_centrocusto', $this->cen_centrocusto])
            ->andFilterWhere(['like', 'cen_nomecentrocusto', $this->cen_nomecentrocusto])
            ->andFilterWhere(['like', 'cen_centrocustoreduzido', $this->cen_centrocustoreduzido]);

        return $dataProvider;
    }
}
