<?php

namespace app\models\solicitacoes;

use Yii;

use app\models\base\Colaborador;
use app\models\base\Unidade;
use app\models\cadastros\Segmento;
use app\models\cadastros\Tipo;

/**
 * This is the model class for table "materialcopias_matc".
 *
 * @property integer $matc_id
 * @property integer $matc_segmento
 * @property integer $matc_tipoacao
 * @property string $matc_curso
 * @property string $matc_centrocusto
 * @property string $matc_unidade
 * @property string $matc_solicitante
 * @property string $matc_data
 * @property integer $situacao_id
 * @property double $matc_totalValorMono
 * @property double $matc_totalValorColor
 * @property string $matc_ResponsavelAut
 * @property string $matc_dataAut
 * @property integer $matc_autorizado
 * @property string $matc_ResponsavelRepro
 * @property string $matc_dataRepro
 * @property integer $matc_encaminhadoRepro
 *
 * @property CopiasacabamentoCopac[] $copiasacabamentoCopacs
 * @property MaterialcopiasItem[] $materialcopiasItems
 * @property MaterialcopiasJustificativas[] $materialcopiasJustificativas
 * @property SituacaomatcopiasSitmat $situacao
 */
class MaterialCopiasAutGerencia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materialcopias_matc';
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
            [['matc_segmento', 'matc_tipoacao', 'matc_curso', 'situacao_id', 'matc_totalValorMono', 'matc_totalValorColor'], 'required'],
            [['matc_segmento', 'matc_tipoacao', 'situacao_id', 'matc_autorizado', 'matc_encaminhadoRepro'], 'integer'],
            [['matc_data', 'matc_dataGer', 'matc_dataAut', 'matc_dataRepro', 'matc_dataPrevisao'], 'safe'],
            [['matc_totalValorMono', 'matc_totalValorColor'], 'number'],
            [['matc_curso'], 'string', 'max' => 255],
            [['matc_centrocusto', 'matc_unidade', 'matc_solicitante', 'matc_ResponsavelAut', 'matc_ResponsavelRepro'], 'string', 'max' => 100],
            [['situacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Situacao::className(), 'targetAttribute' => ['situacao_id' => 'sitmat_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'matc_id' => 'Código',
            'matc_segmento' => 'Segmento',
            'matc_tipoacao' => 'Tipo de Ação',
            'matc_curso' => 'Curso',
            'matc_centrocusto' => 'Centro de Custo',
            'matc_unidade' => 'Unidade',
            'matc_solicitante' => 'Solicitante',
            'matc_data' => 'Data da Solicitação',
            'situacao_id' => 'Situação',
            'matc_totalValorMono' => 'Total em cópias mono',
            'matc_totalValorColor' => 'Total em cópias coloridas',
            'matc_totalGeral' => 'Total Geral',
            'matc_dataPrevisao' => 'Previsão Entrega',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCopiasacabamentoCopacs()
    {
        return $this->hasMany(CopiasacabamentoCopac::className(), ['materialcopias_id' => 'matc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialcopiasItems()
    {
        return $this->hasMany(MaterialcopiasItem::className(), ['materialcopias_id' => 'matc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialcopiasJustificativas()
    {
        return $this->hasMany(MaterialcopiasJustificativas::className(), ['id_materialcopias' => 'matc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSituacao()
    {
        return $this->hasOne(Situacao::className(), ['sitmat_id' => 'situacao_id']);
    }

    public function getColaborador()
    {
        return $this->hasOne(Colaborador::className(), ['col_codcolaborador' => 'matc_solicitante']);
    }

    public function getUnidade()
    {
        return $this->hasOne(Unidade::className(), ['uni_codunidade' => 'matc_unidade']);
    }

    public function getSegmento()
    {
        return $this->hasOne(Segmento::className(), ['seg_codsegmento' => 'matc_segmento']);
    }

    public function getTipo()
    {
        return $this->hasOne(Tipo::className(), ['tip_codtipoa' => 'matc_tipoacao']);
    }
}
