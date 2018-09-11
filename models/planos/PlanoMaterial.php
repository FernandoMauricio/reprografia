<?php

namespace app\models\planos;

use Yii;

/**
 * This is the model class for table "planomaterial_plama".
 *
 * @property string $id
 * @property string $plama_codplano
 * @property string $plama_tipoplano
 * @property integer $plama_codrepositorio
 * @property string $plama_titulo
 * @property double $plama_valor
 * @property string $plama_arquivo
 * @property string $plama_tipomaterial
 * @property string $plama_observacao
 */
class PlanoMaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'planomaterial_plama';
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
            [['plama_codrepositorio', 'plama_tipoplano', 'plama_valor', 'plama_tipomaterial', 'plama_editora', 'nivel_uc'], 'required'],
            [['plama_codplano', 'plama_codmxm', 'plama_codrepositorio', 'nivel_uc'], 'integer'],
            [['plama_valor'], 'number'],
            [['planmatcon_valor'], 'safe'],
            [['plama_titulo', 'plama_tipoplano', 'plama_arquivo', 'plama_observacao'], 'string', 'max' => 100],
            [['plama_tipomaterial', 'plama_editora'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Plama Codplama',
            'nivel_uc' => 'Nível',
            'plama_codplano' => 'Plama Codplano',
            'plama_tipoplano' => 'Plano A/B',
            'plama_codrepositorio' => 'Descrição',
            'plama_titulo' => 'Titulo',
            'plama_codmxm' => 'Cód. MXM',
            'plama_valor' => 'Valor',
            'plama_arquivo' => 'Plama Arquivo',
            'plama_tipomaterial' => 'Tipo Material',
            'plama_editora' => 'Editora',
            'plama_observacao' => 'Observação',
            'planmatcon_valor' => 'Valores Atualizados Em'
        ];
    }
}
