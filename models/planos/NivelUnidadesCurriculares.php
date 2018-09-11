<?php

namespace app\models\planos;

use Yii;

/**
 * This is the model class for table "nivelunidcurriculares_nivuc".
 *
 * @property integer $nivuc_id
 * @property string $nivuc_descricao
 * @property integer $nivuc_status
 *
 * @property UnidadescurricularesUncu[] $unidadescurricularesUncus
 */
class NivelUnidadesCurriculares extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'nivelunidcurriculares_nivuc';
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
            [['nivuc_descricao', 'nivuc_status'], 'required'],
            [['nivuc_status'], 'integer'],
            [['nivuc_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'nivuc_id' => 'Nivuc ID',
            'nivuc_descricao' => 'Nivuc Descricao',
            'nivuc_status' => 'Nivuc Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUnidadescurricularesUncus()
    {
        return $this->hasMany(UnidadescurricularesUncu::className(), ['nivel_uc' => 'nivuc_id']);
    }
}
