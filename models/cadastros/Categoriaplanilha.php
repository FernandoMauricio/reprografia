<?php

namespace app\models\cadastros;

use Yii;

/**
 * This is the model class for table "categoriaplanilha_cat".
 *
 * @property string $cat_codcategoria
 * @property string $cat_descricao
 *
 * @property PlanilhadecursoPlacu[] $planilhadecursoPlacus
 */
class Categoriaplanilha extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categoriaplanilha_cat';
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
            [['cat_descricao'], 'required'],
            [['cat_descricao'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cat_codcategoria' => 'Cat Codcategoria',
            'cat_descricao' => 'Cat Descricao',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPlanilhadecursoPlacus()
    {
        return $this->hasMany(PlanilhadecursoPlacu::className(), ['placu_codcategoria' => 'cat_codcategoria']);
    }
}
