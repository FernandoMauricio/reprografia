<?php

namespace app\models\planos;

use Yii;

/**
 * This is the model class for table "categoria".
 *
 * @property integer $idcategoria
 * @property string $descricao
 * @property integer $status
 *
 * @property PlanoCategorias[] $planoCategorias
 */
class Categoria extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoria';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_apl');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['descricao'], 'required'],
            [['status'], 'integer'],
            [['descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idcategoria' => 'Idcategoria',
            'descricao' => 'Descricao',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoCategorias()
    {
        return $this->hasMany(PlanoCategorias::className(), ['descricao' => 'descricao']);
    }
}
