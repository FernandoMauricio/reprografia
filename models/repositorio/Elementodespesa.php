<?php

namespace app\models\repositorio;

use Yii;

/**
 * This is the model class for table "elementodespesa_eled".
 *
 * @property integer $id
 * @property string $eled_codigo
 * @property string $eled_despesa
 * @property integer $eled_status
 */
class Elementodespesa extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elementodespesa_eled';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db_rep');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['eled_codigo', 'eled_despesa', 'eled_status'], 'required'],
            [['eled_status'], 'integer'],
            [['eled_codigo'], 'string', 'max' => 100],
            [['eled_despesa'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eled_codigo' => 'Eled Codigo',
            'eled_despesa' => 'Eled Despesa',
            'eled_status' => 'Eled Status',
        ];
    }
}
