<?php

namespace app\models\repositorio;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\repositorio\Repositorio;

/**
 * RepositorioSearch represents the model behind the search form about `app\models\repositorio\Repositorio`.
 */
class RepositorioSearch extends Repositorio
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rep_codrepositorio', 'rep_codcategoria', 'rep_codunidade', 'rep_codcolaborador', 'rep_codvisualizacao'], 'integer'],
            [['rep_titulo', 'rep_tipo', 'rep_editora', 'rep_sobre', 'rep_arquivo', 'rep_data', 'rep_palavrachave'], 'safe'],
            [['rep_valor'], 'number'],
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
        $query = Repositorio::find();

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
            'rep_codrepositorio' => $this->rep_codrepositorio,
            'rep_codcategoria' => $this->rep_codcategoria,
            'rep_valor' => $this->rep_valor,
            'rep_codunidade' => $this->rep_codunidade,
            'rep_codcolaborador' => $this->rep_codcolaborador,
            'rep_data' => $this->rep_data,
            'rep_codvisualizacao' => $this->rep_codvisualizacao,
        ]);

        $query->andFilterWhere(['like', 'rep_titulo', $this->rep_titulo])
            ->andFilterWhere(['like', 'rep_tipo', $this->rep_tipo])
            ->andFilterWhere(['like', 'rep_editora', $this->rep_editora])
            ->andFilterWhere(['like', 'rep_sobre', $this->rep_sobre])
            ->andFilterWhere(['like', 'rep_arquivo', $this->rep_arquivo])
            ->andFilterWhere(['like', 'rep_palavrachave', $this->rep_palavrachave]);

        return $dataProvider;
    }
}
