<?php

namespace app\models\planos;

use Yii;

/**
 * This is the model class for table "plano_categorias".
 *
 * @property integer $id
 * @property string $planodeacao_cod
 * @property integer $categoria_cod
 *
 * @property Categoria $categoriaCod
 * @property PlanodeacaoPlan $planodeacaoCod
 */
class PlanoCategorias extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'plano_categorias';
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
            [['planodeacao_cod', 'categoria_cod'], 'required'],
            [['planodeacao_cod', 'categoria_cod'], 'integer'],
            [['categoria_cod'], 'exist', 'skipOnError' => true, 'targetClass' => Categoria::className(), 'targetAttribute' => ['categoria_cod' => 'idcategoria']],
            [['planodeacao_cod'], 'exist', 'skipOnError' => true, 'targetClass' => Planodeacao::className(), 'targetAttribute' => ['planodeacao_cod' => 'plan_codplano']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'planodeacao_cod' => 'Planodeacao Cod',
            'categoria_cod' => 'Categoria Cod',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategoria()
    {
        return $this->hasOne(Categoria::className(), ['idcategoria' => 'categoria_cod']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanodeacaoCod()
    {
        return $this->hasOne(PlanodeacaoPlan::className(), ['plan_codplano' => 'planodeacao_cod']);
    }
}
