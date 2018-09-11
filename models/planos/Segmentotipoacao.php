<?php

namespace app\models\planos;

use Yii;

use app\models\cadastros\Segmento;
use app\models\cadastros\Tipo;

/**
 * This is the model class for table "segmentotipoacao_segtip".
 *
 * @property string $segtip_codsegtip
 * @property string $segtip_codsegmento
 * @property string $segtip_codtipoa
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 * @property SegmentoSeg $segtipCodsegmento
 * @property TipodeacaoTip $segtipCodtipoa
 */
class Segmentotipoacao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'segmentotipoacao_segtip';
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
            [['segtip_codsegmento', 'segtip_codtipoa'], 'required'],
            [['segtip_codsegmento', 'segtip_codtipoa'], 'integer'],
            [['segtip_codsegmento'], 'exist', 'skipOnError' => true, 'targetClass' => Segmento::className(), 'targetAttribute' => ['segtip_codsegmento' => 'seg_codsegmento']],
            [['segtip_codtipoa'], 'exist', 'skipOnError' => true, 'targetClass' => Tipo::className(), 'targetAttribute' => ['segtip_codtipoa' => 'tip_codtipoa']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'segtip_codsegtip' => 'Segtip Codsegtip',
            'segtip_codsegmento' => 'Segtip Codsegmento',
            'segtip_codtipoa' => 'Segtip Codtipoa',
        ];
    }

    //localiza os tipos de ação vinculados ao eixo e segmento
    public static function getTiposSubCat($subcat_id) {

        $sql = 'SELECT tip_codtipoa AS id, tip_descricao AS name 
                FROM tipodeacao_tip, segmentotipoacao_segtip 
                WHERE segtip_codsegmento = "'.$subcat_id.'" AND segtip_codtipoa = tip_codtipoa 
                ORDER BY tip_descricao';

        $data = \app\models\planos\Segmentotipoacao::findBySql($sql)->asArray()->all();

        return $data;
   }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegmento()
    {
        return $this->hasOne(Segmento::className(), ['seg_codsegmento' => 'segtip_codsegmento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegtipCodtipoa()
    {
        return $this->hasOne(TipodeacaoTip::className(), ['tip_codtipoa' => 'segtip_codtipoa']);
    }
}
