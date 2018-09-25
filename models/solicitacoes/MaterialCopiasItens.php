<?php

namespace app\models\solicitacoes;

use Yii;

/**
 * This is the model class for table "materialcopias_item".
 *
 * @property integer $id
 * @property string $item_descricao
 * @property integer $item_qtoriginais
 * @property integer $item_qtexemplares
 * @property integer $item_qteCopias
 * @property integer $item_mono
 * @property integer $item_color
 * @property integer $item_qteTotal
 * @property string $item_observacao
 * @property integer $materialcopias_id
 *
 * @property MaterialcopiasMatc $materialcopias
 */
class MaterialCopiasItens extends \yii\db\ActiveRecord
{
    public $file;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'materialcopias_item';
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
            [['item_descricao', 'item_qtoriginais', 'item_qtexemplares', 'item_qteCopias', 'item_mono', 'item_color', 'item_qteTotal'], 'required'],
            [['id', 'item_qtoriginais', 'item_qtexemplares', 'item_qteCopias', 'item_mono', 'item_color', 'item_qteTotal', 'materialcopias_id', 'item_codrepositorio'], 'integer'],
            [['item_descricao', 'item_observacao', 'item_arquivo'], 'string', 'max' => 255],
            [['file'], 'safe'],
            [['item_qteTotal'], 'validarCampo'],
            [['materialcopias_id'], 'exist', 'skipOnError' => true, 'targetClass' => MaterialCopias::className(), 'targetAttribute' => ['materialcopias_id' => 'matc_id']],
        ];
    }

    public function validarCampo($attribute, $params){
        if($this->item_qteCopias != $this->item_qteTotal ) {
            $this->addError($attribute, '"Qte Total" deve ser igual a "Qte Cópias"!');        
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Item ID',
            'item_descricao' => 'Material',
            'item_arquivo' => 'Arquivo',
            'item_qtoriginais' => 'Qte Originais',
            'item_qtexemplares' => 'Qte Exemplares',
            'item_qteCopias' => 'Qte Copias',
            'item_mono' => 'Mono',
            'item_color' => 'Color',
            'item_qteTotal' => 'Qte Total',
            'item_observacao' => 'Observação',
            'materialcopias_id' => 'Materialcopias ID',
            'file' => 'Arquivo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMaterialcopias()
    {
        return $this->hasOne(MaterialcopiasMatc::className(), ['matc_id' => 'materialcopias_id']);
    }
}
