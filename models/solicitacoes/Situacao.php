<?php

namespace app\models\solicitacoes;

use Yii;

/**
 * This is the model class for table "situacaomatcopias_sitmat".
 *
 * @property integer $sitmat_id
 * @property string $sitmat_descricao
 * @property integer $sitmat_status
 *
 * @property MaterialcopiasMatc[] $materialcopiasMatcs
 */
class Situacao extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'situacaomatcopias_sitmat';
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
            [['sitmat_descricao', 'sitmat_status'], 'required'],
            [['sitmat_status'], 'integer'],
            [['sitmat_descricao'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'sitmat_id' => 'Sitmat ID',
            'sitmat_descricao' => 'Sitmat Descricao',
            'sitmat_status' => 'Sitmat Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialcopiasMatcs()
    {
        return $this->hasMany(MaterialcopiasMatc::className(), ['situacao_id' => 'sitmat_id']);
    }
}
