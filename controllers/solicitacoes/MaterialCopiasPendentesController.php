<?php

namespace app\controllers\solicitacoes;

use Yii;

use app\models\base\Emailusuario;
use app\models\solicitacoes\MaterialCopias;
use app\models\solicitacoes\MaterialCopiasPendentes;
use app\models\solicitacoes\MaterialCopiasPendentesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaterialCopiasPendentesController implements the CRUD actions for MaterialCopiasPendentes model.
 */
class MaterialCopiasPendentesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {

      $this->AccessAllow(); //Irá ser verificado se o usuário está logado no sistema

        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all MaterialCopiasPendentes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main-full';
        $searchModel = new MaterialCopiasPendentesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['matc_id'=>SORT_DESC]];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionAprovar($id)
    {
        $session = Yii::$app->session;

        $model = $this->findModel($id);

        $model->matc_dataAut     = date('Y-m-d H:i:s');
        $model->matc_ResponsavelAut = $session['sess_nomeusuario'];

        //-------atualiza a situação pra aprovado
        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 2 , `matc_autorizado` = 1, `matc_ResponsavelAut` = "'.$model->matc_ResponsavelAut.'" , `matc_dataAut` = "'.$model->matc_dataAut.'" WHERE `matc_id` = '.$model->matc_id.'')
        ->execute();

        $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;
        $model->situacao_id = 2;
        if($model->situacao_id == 2){
            //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A APROVAÇÃO....
            Yii::$app->runAction('email/enviar-email-aprovacao-gabinete-tecnico', ['id' => $model->matc_id]);

            //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DA REPROGRAFIA SOBRE A APROVAÇÃO DA REQUISIÇÃO
            //-- 12 - GERENCIA DE MANUTENÇÃO E TRANSPORTE - GMT // 21 - REPROGRAFIA
            Yii::$app->runAction('email/enviar-email-reprografia', ['id' => $model->matc_id]);
        }

        Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');

        return $this->redirect(['index']);
    }

    public function actionReprovar($id) 
    {
        $model = MaterialCopias::findOne($id);
        $session = Yii::$app->session;
        $session->set('sess_materialcopias', $model->matc_id);

        return $this->redirect(Yii::$app->request->BaseUrl . '/index.php?r=solicitacoes/material-copias-justificativas/index', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the MaterialCopiasPendentes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MaterialCopiasPendentes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MaterialCopiasPendentes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('A página solicitada não existe.');
        }
    }

    public function AccessAllow()
    {
        $session = Yii::$app->session;
        if (!isset($session['sess_codusuario']) && !isset($session['sess_codcolaborador']) && !isset($session['sess_codunidade']) && !isset($session['sess_nomeusuario']) && !isset($session['sess_coddepartamento']) && !isset($session['sess_codcargo']) && !isset($session['sess_cargo']) && !isset($session['sess_setor']) && !isset($session['sess_unidade']) && !isset($session['sess_responsavelsetor'])) 
        {
           return $this->redirect('https://portalsenac.am.senac.br');
        }
    }
}
