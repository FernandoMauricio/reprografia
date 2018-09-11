<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "tiporelatorio_tiprel".
 *
 * @property integer $tiprel_id
 * @property string $tiprel_descricao
 * @property integer $tiprel_status
 */
class Tiporelatorio extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tiporelatorio_tiprel';
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
            [['tiprel_id', 'tiprel_descricao', 'tiprel_status'], 'required'],
            [['tiprel_id', 'tiprel_status'], 'integer'],
            [['tiprel_descricao'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tiprel_id' => 'Tiprel ID',
            'tiprel_descricao' => 'Tiprel Descricao',
            'tiprel_status' => 'Tiprel Status',
        ];
    }
}
