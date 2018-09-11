<?php

namespace app\models\solicitacoes;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\solicitacoes\MaterialCopiasAutGerencia;

/**
 * MaterialCopiasAutGerenciaSearch represents the model behind the search form about `app\models\solicitacoes\MaterialCopiasAutGerencia`.
 */
class MaterialCopiasAutGerenciaSearch extends MaterialCopiasAutGerencia
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['matc_id', 'matc_segmento', 'matc_tipoacao', 'situacao_id', 'matc_autorizadoGer', 'matc_autorizado', 'matc_encaminhadoRepro'], 'integer'],
            [['matc_curso', 'matc_centrocusto', 'matc_unidade', 'matc_solicitante', 'matc_data', 'matc_ResponsavelGer', 'matc_dataGer', 'matc_ResponsavelAut', 'matc_dataAut', 'matc_ResponsavelRepro', 'matc_dataRepro'], 'safe'],
            [['matc_totalValorMono', 'matc_totalValorColor'], 'number'],
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
        $query = MaterialCopiasAutGerencia::find();

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
            'matc_id' => $this->matc_id,
            'matc_segmento' => $this->matc_segmento,
            'matc_tipoacao' => $this->matc_tipoacao,
            'matc_data' => $this->matc_data,
            'matc_totalValorMono' => $this->matc_totalValorMono,
            'matc_totalValorColor' => $this->matc_totalValorColor,
            'matc_dataGer' => $this->matc_dataGer,
            'matc_autorizadoGer' => $this->matc_autorizadoGer,
            'matc_dataAut' => $this->matc_dataAut,
            'matc_autorizado' => $this->matc_autorizado,
            'matc_dataRepro' => $this->matc_dataRepro,
            'matc_encaminhadoRepro' => $this->matc_encaminhadoRepro,
            'situacao_id' => 1, //PARA AUTORIZAÇÃO DO GERENTE DE SETOR
        ]);

        $session = Yii::$app->session;
        
        $query->andFilterWhere(['matc_unidade' => $session['sess_codunidade']])
            ->andFilterWhere(['like', 'matc_curso', $this->matc_curso])
            ->andFilterWhere(['like', 'matc_centrocusto', $this->matc_centrocusto])
            ->andFilterWhere(['like', 'matc_solicitante', $this->matc_solicitante])
            ->andFilterWhere(['like', 'matc_ResponsavelGer', $this->matc_ResponsavelGer])
            ->andFilterWhere(['like', 'matc_ResponsavelAut', $this->matc_ResponsavelAut])
            ->andFilterWhere(['like', 'matc_ResponsavelRepro', $this->matc_ResponsavelRepro]);

        return $dataProvider;
    }
}
