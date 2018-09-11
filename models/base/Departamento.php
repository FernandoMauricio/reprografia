<?php

namespace app\models\base;

use Yii;

/**
 * This is the model class for table "departamento_dep".
 *
 * @property string $dep_coddepartamento
 * @property string $dep_nomecompleto
 * @property string $dep_nomeabreviado
 * @property string $dep_codunidade
 * @property string $dep_codsituacao
 *
 * @property CentrocustoCen[] $centrocustoCens
 * @property ColaboradorCol[] $colaboradorCols
 * @property SituacaosistemaSitsis $depCodsituacao
 * @property UnidadeUni $depCodunidade
 * @property FonedepartamentoFodep[] $fonedepartamentoFodeps
 * @property ResponsaveldepartamentoRede[] $responsaveldepartamentoRedes
 * @property ColaboradorCol[] $redeCodcolaboradors
 */
class Departamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'departamento_dep';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['dep_nomecompleto'], 'required'],
            [['dep_codunidade', 'dep_codsituacao'], 'integer'],
            [['dep_nomecompleto'], 'string', 'max' => 100],
            [['dep_nomeabreviado'], 'string', 'max' => 40],
            [['dep_codsituacao'], 'exist', 'skipOnError' => true, 'targetClass' => SituacaosistemaSitsis::className(), 'targetAttribute' => ['dep_codsituacao' => 'sitsis_codsituacao']],
            [['dep_codunidade'], 'exist', 'skipOnError' => true, 'targetClass' => UnidadeUni::className(), 'targetAttribute' => ['dep_codunidade' => 'uni_codunidade']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'dep_coddepartamento' => 'Dep Coddepartamento',
            'dep_nomecompleto' => 'Dep Nomecompleto',
            'dep_nomeabreviado' => 'Dep Nomeabreviado',
            'dep_codunidade' => 'Dep Codunidade',
            'dep_codsituacao' => 'Dep Codsituacao',
        ];
    }

    //Busca dados dos eixos vinculados aos segmentos
    public static function getDepartamentoSubCat($cat_id) {
        $data=\app\models\base\Departamento::find()
       ->where(['dep_codunidade'=>$cat_id])
       ->select(['dep_coddepartamento AS id','dep_nomecompleto AS name'])->asArray()->all();

            return $data;
        }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCentrocustoCens()
    {
        return $this->hasMany(CentrocustoCen::className(), ['cen_coddepartamento' => 'dep_coddepartamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getColaboradorCols()
    {
        return $this->hasMany(ColaboradorCol::className(), ['col_coddepartamento' => 'dep_coddepartamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepCodsituacao()
    {
        return $this->hasOne(SituacaosistemaSitsis::className(), ['sitsis_codsituacao' => 'dep_codsituacao']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepCodunidade()
    {
        return $this->hasOne(UnidadeUni::className(), ['uni_codunidade' => 'dep_codunidade']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFonedepartamentoFodeps()
    {
        return $this->hasMany(FonedepartamentoFodep::className(), ['fodep_coddepartamento' => 'dep_coddepartamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getResponsaveldepartamentoRedes()
    {
        return $this->hasMany(ResponsaveldepartamentoRede::className(), ['rede_coddepartamento' => 'dep_coddepartamento']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRedeCodcolaboradors()
    {
        return $this->hasMany(ColaboradorCol::className(), ['col_codcolaborador' => 'rede_codcolaborador'])->viaTable('responsaveldepartamento_rede', ['rede_coddepartamento' => 'dep_coddepartamento']);
    }
}
