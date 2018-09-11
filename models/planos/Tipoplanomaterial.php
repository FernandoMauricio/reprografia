<?php

namespace app\models\planos;

use Yii;

/**
 * This is the model class for table "tipoplanomaterial_tiplama".
 *
 * @property string $tiplama_codtiplama
 * @property string $tiplama_descricao
 *
 * @property PlanilhamaterialPlanima[] $planilhamaterialPlanimas
 */
class Tipoplanomaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipoplanomaterial_tiplama';
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
            [['tiplama_descricao'], 'required'],
            [['tiplama_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tiplama_codtiplama' => 'Tiplama Codtiplama',
            'tiplama_descricao' => 'Tiplama Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhamaterialPlanimas()
    {
        return $this->hasMany(PlanilhamaterialPlanima::className(), ['planima_codtiplama' => 'tiplama_codtiplama']);
    }
}
