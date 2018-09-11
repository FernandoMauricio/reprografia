<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "tipodeacao_tip".
 *
 * @property string $tip_codtipoa
 * @property string $tip_descricao
 * @property string $tip_sigla
 * @property integer $tip_status
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 * @property PlanodeacaoPlan[] $planodeacaoPlans
 * @property SegmentotipoacaoSegtip[] $segmentotipoacaoSegtips
 */
class Tipo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipodeacao_tip';
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
            [['tip_descricao', 'tip_sigla', 'tip_status'], 'required'],
            [['tip_status'], 'integer'],
            [['tip_descricao'], 'string', 'max' => 80],
            [['tip_sigla'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tip_codtipoa' => 'Código',
            'tip_descricao' => 'Descrição',
            'tip_sigla' => 'Sigla',
            'tip_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codtipoa' => 'tip_codtipoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanodeacaoPlans()
    {
        return $this->hasMany(PlanodeacaoPlan::className(), ['plan_codtipoa' => 'tip_codtipoa']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegmentotipoacaoSegtips()
    {
        return $this->hasMany(SegmentotipoacaoSegtip::className(), ['segtip_codtipoa' => 'tip_codtipoa']);
    }
}
