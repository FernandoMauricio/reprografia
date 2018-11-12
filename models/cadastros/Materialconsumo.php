<?php

namespace app\models\cadastros;

use Yii;
use app\models\base\Colaborador;

/**
 * This is the model class for table "materialconsumo_matcon".
 *
 * @property integer $matcon_codMXM
 * @property string $matcon_descricao
 * @property string $matcon_tipo
 * @property double $matcon_valor
 * @property integer $matcon_status
 *
 * @property PlanoMaterialconsumo[] $planoMaterialconsumos
 */
class Materialconsumo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materialconsumo_matcon';
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
            [['matcon_descricao', 'matcon_tipo', 'matcon_valor', 'matcon_status'], 'required'],
            ['matcon_codMXM', 'unique'],
            [['matcon_valor'], 'number'],
            [['matalu_data'], 'safe'],
            [['matcon_status', 'matcon_codMXM', 'matcon_codcolaborador'], 'integer'],
            [['matcon_descricao'], 'string', 'max' => 255],
            [['matcon_tipo'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'matcon_codMXM' => 'Código MXM',
            'matcon_descricao' => 'Descrição',
            'matcon_tipo' => 'Tipo',
            'matcon_valor' => 'Valor',
            'matcon_status' => 'Situação',
            'matcon_codcolaborador' => 'Atualizado por',
            'matcon_data' => 'Última Modifcação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanoMaterialconsumos()
    {
        return $this->hasMany(PlanoMaterialconsumo::className(), ['materialconsumo_cod' => 'matcon_codMXM']);
    }

    public function getColaborador()
    {
        return $this->hasOne(Colaborador::className(), ['col_codcolaborador' => 'matcon_codcolaborador']);
    }
}
