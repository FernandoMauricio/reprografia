<?php

namespace app\models\cadastros;

use Yii;


/**
 * This is the model class for table "eixo_eix".
 *
 * @property string $eix_codeixo
 * @property string $eix_descricao
 * @property integer $eix_status
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 * @property PlanodeacaoPlan[] $planodeacaoPlans
 * @property SegmentoSeg[] $segmentoSegs
 */
class Eixo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'eixo_eix';
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
            [['eix_descricao', 'eix_status'], 'required'],
            [['eix_status'], 'integer'],
            [['eix_descricao'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'eix_codeixo' => 'Código',
            'eix_descricao' => 'Descrição',
            'eix_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codeixo' => 'eix_codeixo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanodeacaoPlans()
    {
        return $this->hasMany(PlanodeacaoPlan::className(), ['plan_codeixo' => 'eix_codeixo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegmentoSegs()
    {
        return $this->hasMany(SegmentoSeg::className(), ['seg_codeixo' => 'eix_codeixo']);
    }
}
