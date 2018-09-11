<?php

namespace app\models\solicitacoes;

use Yii;

/**
 * This is the model class for table "copiasacabamento_copac".
 *
 * @property integer $id
 * @property integer $materialcopias_id
 * @property integer $acabamento_id
 *
 * @property AcabamentoAcab $acabamento
 * @property MaterialcopiasMatc $materialcopias
 */
class CopiasAcabamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'copiasacabamento_copac';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['materialcopias_id', 'acabamento_id'], 'required'],
            [['materialcopias_id', 'acabamento_id'], 'integer'],
            [['acabamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Acabamento::className(), 'targetAttribute' => ['acabamento_id' => 'id']],
            [['materialcopias_id'], 'exist', 'skipOnError' => true, 'targetClass' => Materialcopias::className(), 'targetAttribute' => ['materialcopias_id' => 'matc_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'materialcopias_id' => 'Materialcopias ID',
            'acabamento_id' => 'Acabamento ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcabamento()
    {
        return $this->hasOne(Acabamento::className(), ['id' => 'acabamento_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialcopias()
    {
        return $this->hasOne(MaterialcopiasMatc::className(), ['matc_id' => 'materialcopias_id']);
    }
}
