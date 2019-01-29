<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "tipoplanilha_tipla".
 *
 * @property string $tipla_codtipla
 * @property string $tipla_descricao
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 */
class Tipoplanilha extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipoplanilha_tipla';
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
            [['tipla_descricao'], 'required'],
            [['tipla_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipla_codtipla' => 'Tipla Codtipla',
            'tipla_descricao' => 'Tipla Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codtipla' => 'tipla_codtipla']);
    }
}
