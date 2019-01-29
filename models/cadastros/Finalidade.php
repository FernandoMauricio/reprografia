<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "finalidade_fin".
 *
 * @property string $fin_codfinalidade
 * @property string $fin_descricao
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 */
class Finalidade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'finalidade_fin';
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
            [['fin_descricao'], 'required'],
            [['fin_descricao'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fin_codfinalidade' => 'Fin Codfinalidade',
            'fin_descricao' => 'Fin Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codfinalidade' => 'fin_codfinalidade']);
    }
}
