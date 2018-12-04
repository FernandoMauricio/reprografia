<?php

namespace app\controllers\relatorios;

use Yii;
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

        if ($model->load(Yii::$app->request->post())) {

            return $this->redirect(['copias-mensal', 'relat_datainicio' => $model->relat_datainicio, 'relat_datafim'=> $model->relat_datafim]);

        }else{
            return $this->render('/relatorios/relatorio', [
                'model' => $model,
            ]);
        }
    }

    public function actionCopiasMensal($relat_datainicio, $relat_datafim)
    {
       $this->layout = 'main-imprimir';
       $copias = $this->findModelCopias($relat_datainicio, $relat_datafim);

            return $this->render('/relatorios/copias-mensal', [
              'copias' => $copias, 
            ]);
    }

    protected function findModelCopias($relat_datainicio, $relat_datafim)
    {
        $queryCopias = "SELECT 
        `matc_id`,
        `matc_unidade`,
        `matc_centrocusto`,
        `item_descricao`,
        `item_qtoriginais`,
        `item_qtexemplares`,
        `item_qteCopias`,
        `item_mono`,
        `item_color`
        FROM `materialcopias_matc`
        INNER JOIN `materialcopias_item` ON `materialcopias_matc`.`matc_id` = `materialcopias_item`.`materialcopias_id`
        WHERE (`matc_data` BETWEEN '".$relat_datainicio."' AND '".$relat_datafim."')
        AND `situacao_id` = 6"; //Finalizadas

        if (($copias = MaterialCopias::findBySql($queryCopias)->all()) !== null) {
            return $copias;
        } else {
            throw new NotFoundHttpException('A página requisitada não existe.');
        }
    }
}
