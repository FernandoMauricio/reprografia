<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "tipoprogramacao_tipro".
 *
 * @property string $tipro_codprogramacao
 * @property string $tipro_descricao
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 */
class Tipoprogramacao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipoprogramacao_tipro';
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
            [['tipro_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipro_codprogramacao' => 'Tipro Codprogramacao',
            'tipro_descricao' => 'Tipro Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codprogramacao' => 'tipro_codprogramacao']);
    }
}
