<?php

namespace app\models\repositorio;

use Yii;

/**
 * This is the model class for table "categoria_cat".
 *
 * @property string $cat_codcategoria
 * @property string $cat_descricao
 * @property integer $cat_status
 *
 * @property RepositorioRep[] $repositorioReps
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria_cat';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_rep');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cat_descricao', 'cat_status'], 'required'],
            [['cat_status'], 'integer'],
            [['cat_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_codcategoria' => 'Código',
            'cat_descricao' => 'Descrição',
            'cat_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepositorioReps()
    {
        return $this->hasMany(RepositorioRep::className(), ['rep_codcategoria' => 'cat_codcategoria']);
    }
}
