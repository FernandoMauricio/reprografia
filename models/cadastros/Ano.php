<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "ano_an".
 *
 * @property string $an_codano
 * @property integer $an_ano
 * @property integer $an_status
 *
 * @property ModeloaModa[] $modeloaModas
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 */
class Ano extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ano_an';
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
            [['an_ano', 'an_status'], 'required'],
            [['an_ano', 'an_status'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'an_codano' => 'Código',
            'an_ano' => 'Ano',
            'an_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModeloaModas()
    {
        return $this->hasMany(ModeloaModa::className(), ['moda_codano' => 'an_codano']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codano' => 'an_codano']);
    }
}
