<?php

namespace app\models\repositorio;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\repositorio\Editora;

/**
 * EditoraSearch represents the model behind the search form about `app\models\repositorio\Editora`.
 */
class EditoraSearch extends Editora
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['edi_codeditora', 'edi_status'], 'integer'],
            [['edi_descricao'], 'safe'],
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
        $query = Editora::find();

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
            'edi_codeditora' => $this->edi_codeditora,
            'edi_status' => $this->edi_status,
        ]);

        $query->andFilterWhere(['like', 'edi_descricao', $this->edi_descricao]);

        return $dataProvider;
    }
}
