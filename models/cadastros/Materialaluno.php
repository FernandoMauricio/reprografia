<?php

namespace app\models\cadastros;

use Yii;
use app\models\base\Colaborador;

/**
 * This is the model class for table "materialaluno_matalu".
 *
 * @property integer $matalu_cod
 * @property string $matalu_descricao
 * @property string $matalu_unidade
 * @property double $matalu_valor
 * @property integer $matalu_status
 *
 * @property PlanoMaterialaluno[] $planoMaterialalunos
 */
class Materialaluno extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materialaluno_matalu';
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
            [['matalu_descricao', 'matalu_unidade', 'matalu_valor', 'matalu_status'], 'required'],
            ['matalu_codMXM', 'unique'],
            [['matalu_valor'], 'number'],
            [['matalu_data'], 'safe'],
            [['matalu_status', 'matalu_codcolaborador'], 'integer'],
            [['matalu_descricao'], 'string', 'max' => 100],
            [['matalu_unidade'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'matalu_codMXM' => 'Código MXM',
            'matalu_descricao' => 'Descrição',
            'matalu_unidade' => 'Unidade',
            'matalu_valor' => 'Valor',
            'matalu_status' => 'Situação',
            'matalu_codcolaborador' => 'Atualizado por',
            'matalu_data' => 'Última Modifcação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoMaterialalunos()
    {
        return $this->hasMany(PlanoMaterialaluno::className(), ['materialaluno_cod' => 'matalu_cod']);
    }

    public function getColaborador()
    {
        return $this->hasOne(Colaborador::className(), ['col_codcolaborador' => 'matalu_codcolaborador']);
    }
}
