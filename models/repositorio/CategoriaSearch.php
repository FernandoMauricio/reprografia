<?php

namespace app\models\repositorio;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\repositorio\Categoria;

/**
 * CategoriaSearch represents the model behind the search form about `app\models\repositorio\Categoria`.
 */
class CategoriaSearch extends Categoria
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_codcategoria', 'cat_status'], 'integer'],
            [['cat_descricao'], 'safe'],
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
        $query = Categoria::find();

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
            'cat_codcategoria' => $this->cat_codcategoria,
            'cat_status' => $this->cat_status,
        ]);

        $query->andFilterWhere(['like', 'cat_descricao', $this->cat_descricao]);

        return $dataProvider;
    }
}
