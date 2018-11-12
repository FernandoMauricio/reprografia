<?php

namespace app\models\solicitacoes;

use Yii;

/**
 * This is the model class for table "acabamento_acab".
 *
 * @property integer $id
 * @property string $acab_descricao
 * @property integer $acab_status
 *
 * @property CopiasacabamentoCopac[] $copiasacabamentoCopacs
 */
class Acabamento extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'acabamento_acab';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('db');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['acab_descricao', 'acab_status'], 'required'],
            [['acab_status'], 'integer'],
            [['acab_descricao'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'acab_descricao' => 'Descrição',
            'acab_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCopiasacabamentoCopacs()
    {
        return $this->hasMany(CopiasacabamentoCopac::className(), ['acabamento_id' => 'id']);
    }
}
