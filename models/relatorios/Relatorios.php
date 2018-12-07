<?php

namespace app\models\relatorios;

use Yii;
use yii\base\Model;

class Relatorios extends Model
{
    public $relat_unidade;
    public $relat_datainicio;
    public $relat_datafim;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['relat_datainicio', 'relat_datafim'], 'required'],
            [['relat_unidade', 'relat_datainicio', 'relat_datafim'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'relat_unidade' => 'Unidade',
            'relat_datainicio' => 'Início',
            'relat_datafim' => 'Fim',
        ];
    }
}
