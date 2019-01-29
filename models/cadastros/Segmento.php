<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "segmento_seg".
 *
 * @property string $seg_codsegmento
 * @property string $seg_descricao
 * @property string $seg_codeixo
 * @property integer $seg_status
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 * @property PlanodeacaoPlan[] $planodeacaoPlans
 * @property EixoEix $segCodeixo
 * @property SegmentotipoacaoSegtip[] $segmentotipoacaoSegtips
 */
class Segmento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'segmento_seg';
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
            [['seg_descricao', 'seg_codeixo', 'seg_status'], 'required'],
            [['seg_codeixo', 'seg_status'], 'integer'],
            [['seg_descricao'], 'string', 'max' => 80],
            [['seg_codeixo'], 'exist', 'skipOnError' => true, 'targetClass' => Eixo::className(), 'targetAttribute' => ['seg_codeixo' => 'eix_codeixo']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'seg_codsegmento' => 'Código',
            'seg_descricao' => 'Descrição',
            'seg_codeixo' => 'Eixo',
            'seg_status' => 'Situação',
        ];
    }

    //Busca dados dos eixos vinculados aos segmentos
    public static function getSegmentoSubCat($cat_id) {
        $data=\app\models\cadastros\Segmento::find()
        ->where(['seg_codeixo'=>$cat_id, 'seg_status' => 1])
        ->select(['seg_codsegmento AS id','seg_descricao AS name'])->asArray()->all();

        return $data;
    }

    public static function getDefaultSubCat($id) {

        $sql = 'SELECT seg_codsegmento AS id, seg_descricao AS name FROM segmento_seg, planodeacao_plan WHERE plan_codplano = "'.$id.'" AND seg_codsegmento = plan_codsegmento';
        $data = \app\models\planos\Segmento::findBySql($sql)->asArray()->all();

        return $data;
    }



    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codsegmento' => 'seg_codsegmento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanodeacaoPlans()
    {
        return $this->hasMany(PlanodeacaoPlan::className(), ['plan_codsegmento' => 'seg_codsegmento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEixo()
    {
        return $this->hasOne(Eixo::className(), ['eix_codeixo' => 'seg_codeixo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegmentotipoacaoSegtips()
    {
        return $this->hasMany(SegmentotipoacaoSegtip::className(), ['segtip_codsegmento' => 'seg_codsegmento']);
    }

}
