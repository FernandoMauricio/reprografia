<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "tipo_unidade".
 *
 * @property integer $tipuni_cod
 * @property string $tipuni_descricao
 */
class TipoUnidade extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipo_unidade';
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
            [['tipuni_descricao'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tipuni_cod' => 'Tipuni Cod',
            'tipuni_descricao' => 'Tipuni Descricao',
        ];
    }
}
