<?php

namespace app\models\repositorio;

use Yii;

/**
 * This is the model class for table "tipomaterial_tip".
 *
 * @property string $tip_codtipo
 * @property string $tip_descricao
 * @property integer $tip_status
 */
class Tipomaterial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tipomaterial_tip';
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
            [['tip_descricao', 'tip_status', 'tip_elementodespesa_id'], 'required'],
            [['tip_status'], 'integer'],
            [['tip_descricao'], 'string', 'max' => 45],
            [['tip_elementodespesa_id'], 'string', 'max' => 255],
            [['tip_elementodespesa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Elementodespesa::className(), 'targetAttribute' => ['tip_elementodespesa_id' => 'eled_despesa']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'tip_codtipo' => 'Código',
            'tip_descricao' => 'Descrição',
            'tip_elementodespesa_id' => 'Elemento de Despesa',
            'tip_status' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElementodespesa()
    {
        return $this->hasOne(Elementodespesa::className(), ['eled_despesa' => 'tip_elementodespesa_id']);
    }
}
