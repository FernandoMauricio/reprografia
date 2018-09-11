<?php

namespace app\models\cadastros;

use Yii;

use app\models\cadastros\Segmento;
use app\models\base\Anocentrocusto;
use app\models\base\Departamento;
use app\models\base\Situacaosistema;
use app\models\base\Unidade;

/**
 * This is the model class for table "centrocusto_cen".
 *
 * @property string $cen_codcentrocusto
 * @property string $cen_centrocusto
 * @property string $cen_nomecentrocusto
 * @property string $cen_coddepartamento
 * @property string $cen_codsituacao
 * @property string $cen_codunidade
 * @property integer $cen_codsegmento
 * @property integer $cen_codtipoacao
 * @property string $cen_nomesegmento
 * @property string $cen_nometipoacao
 * @property string $cen_codano
 * @property string $cen_centrocustoreduzido
 *
 * @property AnocentrocustoAnce $cenCodano
 * @property DepartamentoDep $cenCoddepartamento
 * @property SituacaosistemaSitsis $cenCodsituacao
 * @property UnidadeUni $cenCodunidade
 */
class Centrocusto extends \yii\db\ActiveRecord
{
    public $nomeUnidade;

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_base');
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'centrocusto_cen';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cen_centrocusto', 'cen_codsituacao', 'cen_codunidade', 'cen_codano'], 'required'],
            [['cen_coddepartamento', 'cen_codsituacao', 'cen_codunidade', 'cen_codsegmento', 'cen_codtipoacao', 'cen_codano'], 'integer'],
            [['cen_data', 'nomeUnidade'], 'safe'],
            [['cen_centrocusto'], 'string', 'max' => 45],
            [['cen_nomecentrocusto', 'cen_usuario'], 'string', 'max' => 100],
            [['cen_centrocustoreduzido'], 'string', 'max' => 10],
            [['cen_centrocusto'], 'string',  'min' => 23, 'max' => 23,'tooShort' => '"{attribute}" deve conter 17 números'], // exemplo: 25.555
            [['cen_codano'], 'exist', 'skipOnError' => true, 'targetClass' => Anocentrocusto::className(), 'targetAttribute' => ['cen_codano' => 'ance_coddocano']],
            [['cen_coddepartamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamento::className(), 'targetAttribute' => ['cen_coddepartamento' => 'dep_coddepartamento']],
            [['cen_codunidade'], 'exist', 'skipOnError' => true, 'targetClass' => Unidade::className(), 'targetAttribute' => ['cen_codunidade' => 'uni_codunidade']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cen_codcentrocusto' => 'Código',
            'cen_centrocusto' => 'Classificação Orçamentária',
            'cen_nomecentrocusto' => 'Descrição',
            'cen_coddepartamento' => 'Departamento',
            'cen_codsituacao' => 'Situação',
            'cen_codunidade' => 'Unidade',
            'cen_codsegmento' => 'Cód. Segmento',
            'cen_codtipoacao' => 'Cód. Tipo de Ação',
            'cen_codano' => 'Ano',
            'cen_centrocustoreduzido' => 'Centro de Custo',
            'cen_data' => 'Data Cadastro/Atualização',
            'cen_usuario' => 'Cadastrado/Atualizado por',
            'nomeUnidade'   => 'Unidade',
        ];
    }

    //Replace de '.' por '' nos valores da precificação
    public function beforeSave($insert) {
            if (parent::beforeSave($insert)) {
                $this->cen_centrocusto        = str_replace(".", "", $this->cen_centrocusto);
                return true;
            } else {
                return false;
            }
        }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegmento()
    {
        return $this->hasOne(Segmento::className(), ['seg_codsegmento' => 'cen_codsegmento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipoacao()
    {
        return $this->hasOne(Tipo::className(), ['tip_codtipoa' => 'cen_codtipoacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAno()
    {
        return $this->hasOne(Anocentrocusto::className(), ['ance_coddocano' => 'cen_codano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartamento()
    {
        return $this->hasOne(Departamento::className(), ['dep_coddepartamento' => 'cen_coddepartamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidade()
    {
        return $this->hasOne(Unidade::className(), ['uni_codunidade' => 'cen_codunidade']);
    }
}
