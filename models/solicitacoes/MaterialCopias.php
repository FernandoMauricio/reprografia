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
 * @property integer $matc_qtoriginais
 * @property integer $matc_qtexemplares
 * @property integer $matc_mono
 * @property integer $matc_color
 * @property string $matc_curso
 * @property string $matc_centrocusto
 * @property string $matc_unidade
 * @property string $matc_solicitante
 * @property string $matc_data
 * @property integer $situacao_id
 * @property integer $matc_qteCopias
 * @property integer $matc_qteTotal
 * @property integer $matc_totalValorMono
 * @property integer $matc_totalValorColor
 * @property string $matc_ResponsavelAut
 * @property string $matc_dataAut
 *
 * @property CopiasacabamentoCopac[] $copiasacabamentoCopacs
 * @property SituacaomatcopiasSitmat $situacao
 */
class MaterialCopias extends \yii\db\ActiveRecord
{
    public $listAcabamento;
    public $segmentoLabel;
    public $tipoLabel;

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
            [['matc_centrocusto'], 'validarTipo', 'skipOnError' => false],
            [['listAcabamento', 'matc_curso', 'situacao_id', 'matc_centrocusto', 'matc_tipo'], 'required'],
            [['situacao_id','matc_autorizado', 'matc_encaminhadoRepro', 'matc_segmento', 'matc_tipoacao'], 'integer'],
            [['matc_data', 'matc_dataAut','matc_dataRepro', 'segmentoLabel', 'tipoLabel', 'matc_dataReceb'], 'safe'],
            [['matc_totalValorMono', 'matc_totalValorColor', 'matc_totalGeral'], 'number'],
            [['matc_curso', 'matc_descricaoReceb'], 'string', 'max' => 255],
            [['matc_tipo'], 'string', 'max' => 45],
            //[['matc_centrocusto'], 'string',  'min' => 6, 'max' => 6,'tooShort' => '"{attribute}" deve conter 5 números'], // exemplo: 25.555
            [['matc_unidade', 'matc_solicitante', 'matc_ResponsavelAut', 'matc_ResponsavelRepro', 'matc_responsavelReceb'], 'string', 'max' => 100],
            [['situacao_id'], 'exist', 'skipOnError' => true, 'targetClass' => Situacao::className(), 'targetAttribute' => ['situacao_id' => 'sitmat_id']],
            [['matc_segmento', 'matc_tipoacao'], 'required', 'when' => function ($model) { 
                return $model->matc_tipo=='Apostilas';
            }, 'whenClient' => "function (attribute, value) { 
                    return $('#materialcopias-matc_tipo').val() == 'Apostilas';
                }"
            ],
            // [['matc_totalGeral'], 'compare', 'compareValue' => 'matc_totalValorMono', 'operator' => '==', 'when' => function ($model) { 
            //     return $model->matc_totalGeral != $model->matc_totalValorMono;
            // }, 'whenClient' => "function (attribute, value) { 
            //         return $('#materialcopias-matc_totalgeral').val() != $('#materialcopias-matc_totalvalormono').val();
            //     }"
            // ],
        ];
    }

    public function validarTipo($attribute, $params){
        if($this->matc_centrocusto == NULL) {
            $this->addError($attribute, 'Verifique se existe Centro de Custo, caso não tenha, por favor contate a GIC!!');        
        }
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
            'matc_tipo' => 'Tipo de Serviço',
            'matc_responsavelReceb' => 'Recebido por',
            'matc_dataReceb' => 'Data Recebimento', 
            'matc_descricaoReceb' => 'Observação do Recebimento',
            'listAcabamento' => 'Serviços de Acabamento',
            'segmentoLabel' => 'Segmento',
            'tipoLabel' => 'Tipo de Ação',
        ];
    }

    //Busca dados de segmentos e tipos de ação vinculados aos planos de cursos
    public static function getPlanodeacaoSubCat($cat_id, $subcat_id) {
        $data=\app\models\planos\Planodeacao::find()
       ->where(['plan_codsegmento'=>$cat_id, 'plan_codtipoa'=> $subcat_id])
       ->select(['plan_descricao AS id','plan_descricao AS name'])->asArray()->all();

        return $data;
    }
        
    //Busca dados de segmentos e tipos de ação vinculados aos planos de cursos
    public static function getCentroCustoSubCat($cat_id, $subcat_id) {
        $session = Yii::$app->session;
        $connection = Yii::$app->db_base;
        $data=\app\models\cadastros\Centrocusto::find()
       ->where(['cen_codsegmento'=>$cat_id, 'cen_codtipoacao'=> $subcat_id, 'cen_codano' => date('Y'), 'cen_codunidade' => $session['sess_codunidade']])
       ->select(['cen_centrocustoreduzido AS id','cen_centrocustoreduzido AS name'])->asArray()->all();

        return $data;
    }

    public function getCopiasAcabamento() {//Relation between Acabamento & Material Copias table
    
        return $this->hasMany(CopiasAcabamento::className(), ['materialcopias_id' => 'matc_id']);
    }


    public function afterSave($insert, $changedAttributes){
        //Acabamento
        \Yii::$app->db->createCommand()->delete('copiasacabamento_copac', 'materialcopias_id = '.(int) $this->matc_id)->execute(); //Delete existing value
        foreach ($this->listAcabamento as $id) { //Write new values
            $tc = new CopiasAcabamento();
            $tc->materialcopias_id = $this->matc_id;
            $tc->acabamento_id = $id;
            $tc->save();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialCopiasItens()
    {
        return $this->hasMany(MaterialCopiasItens::className(), ['materialcopias_id' => 'matc_id']);
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
