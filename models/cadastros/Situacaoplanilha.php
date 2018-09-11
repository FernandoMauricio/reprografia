<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "situacaoplanilha_sipla".
 *
 * @property string $sipla_codsituacao
 * @property string $sipla_descricao
 *
 * @property HistoricoplanilhaHis[] $historicoplanilhaHis
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 */
class Situacaoplanilha extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'situacaoplanilha_sipla';
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
            [['sipla_descricao'], 'required'],
            [['sipla_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sipla_codsituacao' => 'Sipla Codsituacao',
            'sipla_descricao' => 'Sipla Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoricoplanilhaHis()
    {
        return $this->hasMany(HistoricoplanilhaHis::className(), ['his_codsituacao' => 'sipla_codsituacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codsituacao' => 'sipla_codsituacao']);
    }
}
