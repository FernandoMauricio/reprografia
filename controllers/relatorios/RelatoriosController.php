<?php

namespace app\controllers\relatorios;

use Yii;
use app\models\base\Unidade;
use app\models\solicitacoes\MaterialCopias;
use app\models\solicitacoes\MaterialCopiasItens;
use app\models\relatorios\Relatorios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

class RelatoriosController extends Controller
{

    public function actionRelatorio()
    {
    	$model = new Relatorios();
        $unidades = Unidade::find()->where(['uni_codsituacao' => 1])->orderBy('uni_nomeabreviado')->all();

        if ($model->load(Yii::$app->request->post())) {

            return $this->redirect(['copias-mensal', 'relat_unidade' => $model->relat_unidade, 'relat_encaminhamento' => $model->relat_encaminhamento, 'relat_datainicio' => $model->relat_datainicio, 'relat_datafim'=> $model->relat_datafim]);

        }else{
            return $this->render('/relatorios/relatorio', [
                'model' => $model,
                'unidades' => $unidades,
            ]);
        }
    }

    public function actionCopiasMensal($relat_unidade, $relat_encaminhamento, $relat_datainicio, $relat_datafim)
    {
       $this->layout = 'main-imprimir';
       $copias = $this->findModelCopias($relat_unidade, $relat_encaminhamento, $relat_datainicio, $relat_datafim);

            return $this->render('/relatorios/copias-mensal', [
              'copias' => $copias, 
            ]);
    }

    protected function findModelCopias($relat_unidade, $relat_encaminhamento, $relat_datainicio, $relat_datafim)
    {

    if($relat_unidade != 0) {
        $queryCopias = "SELECT 
        `matc_id`,
        `matc_unidade`,
        `matc_centrocusto`,
        `item_descricao`,
        `item_qtoriginais`,
        `item_qtexemplares`,
        `item_qteCopias`,
        `item_mono`,
        `item_color`,
        GROUP_CONCAT(`acab_descricao` separator ', ') as `acabamento`
        FROM `materialcopias_matc`
        INNER JOIN `materialcopias_item` ON `materialcopias_matc`.`matc_id` = `materialcopias_item`.`materialcopias_id`
        INNER JOIN `copiasacabamento_copac` ON `copiasacabamento_copac`.`materialcopias_id` = `materialcopias_item`.`materialcopias_id`
        INNER JOIN `acabamento_acab` ON `acabamento_acab`.`id` = `copiasacabamento_copac`.`acabamento_id`
        WHERE (`matc_data` BETWEEN '".$relat_datainicio."' AND '".$relat_datafim."')
        AND `situacao_id`IN (6,9)
        AND `matc_unidade` = '".$relat_unidade."'
        AND `matc_encaminhadoRepro` = '".$relat_encaminhamento."'
        GROUP BY `materialcopias_item`.`id`
        ";
    }else{
        $queryCopias = "SELECT 
        `matc_id`,
        `matc_unidade`,
        `matc_centrocusto`,
        `item_descricao`,
        `item_qtoriginais`,
        `item_qtexemplares`,
        `item_qteCopias`,
        `item_mono`,
        `item_color`,
        GROUP_CONCAT(`acab_descricao` separator ', ') as `acabamento`
        FROM `materialcopias_matc`
        INNER JOIN `materialcopias_item` ON `materialcopias_matc`.`matc_id` = `materialcopias_item`.`materialcopias_id`
        INNER JOIN `copiasacabamento_copac` ON `copiasacabamento_copac`.`materialcopias_id` = `materialcopias_item`.`materialcopias_id`
        INNER JOIN `acabamento_acab` ON `acabamento_acab`.`id` = `copiasacabamento_copac`.`acabamento_id`
        WHERE (`matc_data` BETWEEN '".$relat_datainicio."' AND '".$relat_datafim."')
        AND `situacao_id`IN (6,9)
        AND `matc_encaminhadoRepro` = '".$relat_encaminhamento."'
        AND `matc_unidade`IS NOT NULL
        GROUP BY `materialcopias_item`.`id`
        ";
    }

        if (($copias = MaterialCopias::findBySql($queryCopias)->all()) !== null) {
            return $copias;
        } else {
            throw new NotFoundHttpException('A página requisitada não existe.');
        }
    }
}
