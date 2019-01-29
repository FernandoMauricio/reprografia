<?php

namespace app\models\planos;


use Yii;
use app\models\cadastros\Eixo;
use app\models\cadastros\Nivel;
use app\models\cadastros\Segmento;
use app\models\cadastros\Tipo;
use app\models\base\Colaborador;
use app\models\despesas\Despesasdocente;


/**
 * This is the model class for table "planodeacao_plan".
 *
 * @property string $plan_codplano
 * @property string $plan_descricao
 * @property string $plan_codeixo
 * @property string $plan_codsegmento
 * @property string $plan_codtipoa
 * @property string $plan_codnivel
 * @property string $plan_cargahoraria
 * @property integer $plan_qntaluno
 * @property string $plan_sobre
 * @property string $plan_prerequisito
 * @property integer $plan_nivelDocente
 * @property string $plan_perfTecnico
 * @property integer $plan_codcolaborador
 * @property string $plan_data
 * @property integer $plan_status
 *
 * @property AtualizarplanilhaAtupla[] $atualizarplanilhaAtuplas
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 * @property PlanilhaMaterialPlanima[] $PlanilhaMaterialPlanimas
 * @property PlanodeacaoEstruturafisica[] $planodeacaoEstruturafisicas
 * @property EixoEix $planCodeixo
 * @property NivelNiv $planCodnivel
 * @property SegmentoSeg $planCodsegmento
 * @property TipodeacaoTip $planCodtipoa
 */
class Planodeacao extends \yii\db\ActiveRecord
{
    public $nivelLabel;
    public $segmentoLabel;
    public $eixoLabel;
    public $tipoLabel;
    public $plan_categoriasPlano;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'planodeacao_plan';
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
            [['plan_descricao', 'plan_codeixo', 'plan_codsegmento', 'plan_codtipoa', 'plan_codnivel', 'plan_cargahoraria', 'plan_qntaluno', 'plan_codcolaborador', 'plan_data', 'plan_status', 'plan_modelonacional', 'plan_nivelDocente', 'plan_categoriasPlano', 'plan_codnacional'], 'required'],
            [['plan_codeixo', 'plan_codsegmento', 'plan_codtipoa', 'plan_codnivel', 'plan_cargahoraria','plan_nivelDocente', 'plan_qntaluno', 'plan_codcolaborador', 'plan_status','plan_modelonacional', 'plan_codnacional'], 'integer'],
            [['plan_sobre', 'plan_prerequisito', 'plan_perfConclusao', 'plan_perfTecnico'], 'string'],
            [['plan_data','nivelLabel', 'segmentoLabel', 'eixoLabel', 'tipoLabel', 'plan_custoMaterialLivro', 'plan_custoMaterialApostila', 'plan_custoTotalConsumo', 'plan_custoTotalAluno'], 'safe'],
            [['plan_descricao'], 'string', 'max' => 100],
            [['plan_codeixo'], 'exist', 'skipOnError' => true, 'targetClass' => Eixo::className(), 'targetAttribute' => ['plan_codeixo' => 'eix_codeixo']],
            [['plan_codnivel'], 'exist', 'skipOnError' => true, 'targetClass' => Nivel::className(), 'targetAttribute' => ['plan_codnivel' => 'niv_codnivel']],
            [['plan_codsegmento'], 'exist', 'skipOnError' => true, 'targetClass' => Segmento::className(), 'targetAttribute' => ['plan_codsegmento' => 'seg_codsegmento']],
            [['plan_codtipoa'], 'exist', 'skipOnError' => true, 'targetClass' => Tipo::className(), 'targetAttribute' => ['plan_codtipoa' => 'tip_codtipoa']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'plan_codplano' => 'Código do Plano',
            'plan_descricao' => 'Título',
            'plan_codeixo' => 'Eixo',
            'plan_codsegmento' => 'Segmento',
            'plan_codtipoa' => 'Tipo de Ação',
            'plan_codnivel' => 'Nível',
            'plan_cargahoraria' => 'Carga Horária',
            'plan_qntaluno' => 'Qnt Alunos',
            'plan_sobre' => 'Informações Comerciais',
            'plan_prerequisito' => 'Pré-Requisito',
            'plan_perfConclusao' => 'Perfil Profissional de Conclusão',
            'plan_nivelDocente' => 'Nível do Docente',
            'plan_perfTecnico' => 'Perfil do Docente',
            'plan_codcolaborador' => 'Atualizado Por',
            'plan_data' => 'Data',
            'plan_status' => 'Situação',
            'plan_modelonacional' => 'Novo Modelo Pedagógico',

            'nivelLabel' => 'Nível',
            'segmentoLabel' => 'Segmento',
            'eixoLabel' => 'Eixo',
            'tipoLabel' => 'Tipo de Ação',
            'plan_categoriasPlano' => 'Categorias do Plano',
            'plan_codnacional' => 'Cód. Plano DN',
        ];
    }

    //Busca dados de segmentos e tipos de ação vinculados aos planos de cursos
    public static function getPlanodeacaoSubCat($cat_id, $subcat_id) {
        $data=\app\models\planos\Planodeacao::find()
       ->where(['plan_codsegmento'=>$cat_id, 'plan_codtipoa'=> $subcat_id])
       ->select(['plan_codplano AS id','plan_descricao AS name'])->asArray()->all();

            return $data;
        }


    public function getPlanoCategorias()
    {
        return $this->hasMany(PlanoCategorias::className(), ['planodeacao_cod' => 'plan_codplano']);
    }

    public function afterSave($insert, $changedAttributes){
        \Yii::$app->db_apl->createCommand()->delete('plano_categorias', 'planodeacao_cod = '.(int) $this->plan_codplano)->execute(); //Delete existing value
        foreach ($this->plan_categoriasPlano as $id) { //Write new values
            $tc = new PlanoCategorias();
            $tc->planodeacao_cod = $this->plan_codplano;
            $tc->categoria_cod = $id;
            $tc->save();
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAtualizarplanilhaAtuplas()
    {
        return $this->hasMany(AtualizarplanilhaAtupla::className(), ['atupla_codplano' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codplano' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadescurriculares()
    {
        return $this->hasMany(Unidadescurriculares::className(), ['planodeacao_cod' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhaMaterialPlanimas()
    {
        return $this->hasMany(PlanilhaMaterialPlanima::className(), ['planima_codplano' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoEstruturafisica()
    {
        return $this->hasMany(PlanoEstruturafisica::className(), ['planodeacao_cod' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoMateriais()
    {
        return $this->hasMany(PlanoMaterial::className(), ['plama_codplano' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoConsumo()
    {
        return $this->hasMany(PlanoConsumo::className(), ['planodeacao_cod' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoAluno()
    {
        return $this->hasMany(PlanoAluno::className(), ['planodeacao_cod' => 'plan_codplano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEixo()
    {
        return $this->hasOne(Eixo::className(), ['eix_codeixo' => 'plan_codeixo']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNivel()
    {
        return $this->hasOne(Nivel::className(), ['niv_codnivel' => 'plan_codnivel']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSegmento()
    {
        return $this->hasOne(Segmento::className(), ['seg_codsegmento' => 'plan_codsegmento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTipo()
    {
        return $this->hasOne(Tipo::className(), ['tip_codtipoa' => 'plan_codtipoa']);
    }

    public function getColaborador()
    {
        return $this->hasOne(Colaborador::className(), ['col_codcolaborador' => 'plan_codcolaborador']);
    }

    public function getDespesasDocente()
    {
        return $this->hasOne(Despesasdocente::className(), ['doce_id' => 'plan_nivelDocente']);
    }

}
