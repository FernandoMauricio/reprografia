<?php

namespace app\models\solicitacoes;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\solicitacoes\MaterialCopiasJustificativas;

/**
 * MaterialCopiasJustificativasSearch represents the model behind the search form about `app\models\solicitacoes\MaterialCopiasJustificativas`.
 */
class MaterialCopiasJustificativasSearch extends MaterialCopiasJustificativas
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'id_materialcopias'], 'integer'],
            [['descricao', 'usuario'], 'safe'],
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
        $query = MaterialCopiasJustificativas::find();

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
            'id_materialcopias' => $this->id_materialcopias,
        ]);

        $session = Yii::$app->session;
        $query->andFilterWhere(['id_materialcopias' => $session['sess_materialcopias']])
            ->andFilterWhere(['like', 'descricao', $this->descricao])
            ->andFilterWhere(['like', 'usuario', $this->usuario]);

        return $dataProvider;
    }
}
