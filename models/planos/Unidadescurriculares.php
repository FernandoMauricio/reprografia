<?php

namespace app\models\planos;

use Yii;

/**
 * This is the model class for table "unidadescurriculares_uncu".
 *
 * @property integer $id
 * @property string $uncu_descricao
 * @property integer $uncu_cargahoraria
 * @property string $planodeacao_cod
 *
 * @property PlanodeacaoPlan $planodeacaoCod
 */
class Unidadescurriculares extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'unidadescurriculares_uncu';
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
            [['uncu_descricao', 'uncu_cargahoraria', 'nivel_uc'], 'required'],
            [['uncu_cargahoraria', 'planodeacao_cod', 'nivel_uc'], 'integer'],
            [['uncu_descricao'], 'string', 'max' => 255],
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
            'nivel_uc' => 'UC',
            'uncu_descricao' => 'Descrição',
            'uncu_cargahoraria' => 'Carga Horária',
            'planodeacao_cod' => 'Planodeacao Cod',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanodeacaoCod()
    {
        return $this->hasOne(PlanodeacaoPlan::className(), ['plan_codplano' => 'planodeacao_cod']);
    }
}
