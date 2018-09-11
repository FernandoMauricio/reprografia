<?php

namespace app\models\repositorio;

use Yii;

/**
 * This is the model class for table "editora_edi".
 *
 * @property string $edi_codeditora
 * @property string $edi_descricao
 * @property integer $edi_status
 *
 * @property RepositorioRep[] $repositorioReps
 */
class Editora extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'editora_edi';
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
            [['edi_descricao', 'edi_status'], 'required'],
            [['edi_status'], 'integer'],
            [['edi_descricao'], 'string', 'max' => 60],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'edi_codeditora' => 'Código',
            'edi_descricao' => 'Descrição',
            'edi_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRepositorioReps()
    {
        return $this->hasMany(RepositorioRep::className(), ['rep_editora' => 'edi_codeditora']);
    }
}
