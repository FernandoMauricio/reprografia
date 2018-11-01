<?php

namespace app\controllers\solicitacoes;

use Yii;
use app\models\base\Emailusuario;
use app\models\solicitacoes\MaterialCopiasAutGerencia;
use app\models\solicitacoes\MaterialCopiasAutGerenciaSearch;
use app\models\solicitacoes\Acabamento;
use app\models\solicitacoes\MaterialCopiasItens;
use app\models\solicitacoes\MaterialCopias;
use app\models\solicitacoes\MaterialCopiasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaterialCopiasAutGerenciaController implements the CRUD actions for MaterialCopiasAutGerencia model.
 */
class MaterialCopiasAutGerenciaController extends Controller
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
    * Lists all MaterialCopiasAutGerencia models.
    * @return mixed
    */
   public function actionIndex()
   {
     $this->layout = 'main-full';
     $searchModel = new MaterialCopiasAutGerenciaSearch();
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

      $model->matc_dataGer     = date('Y-m-d H:i:s');
      $model->matc_dataAut     = date('Y-m-d H:i:s');
      $model->matc_ResponsavelGer = $session['sess_nomeusuario'];
      $model->matc_ResponsavelAut = $session['sess_nomeusuario'];

      if($model->matc_tipo == 2) { //Se for Impressão Avulsa, será aprovado também a DEP automaticamente
        //-------atualiza a situação pra aprovado pela gerência do setor e pela DEP
         Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 2 , `matc_autorizadoGer` = 1, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" , `matc_autorizado` = 1, `matc_ResponsavelAut` = "'.$model->matc_ResponsavelAut.'" , `matc_dataAut` = "'.$model->matc_dataAut.'" WHERE `matc_id` = '.$model->matc_id.'')
         ->execute();
      } else{
         //-------atualiza a situação pra aprovado pela gerência do setor
         Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 7 , `matc_autorizadoGer` = 1, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" WHERE `matc_id` = '.$model->matc_id.'')
         ->execute();
      }

      $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;

      $model->situacao_id = 7;
      if($model->situacao_id == 7) {
         //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DO GABINETE TÉCNICO INFORMANDO SOBRE O RECEBIMENTO DE UMA NOVA SOLICITAÇÃO DE CÓPIA 
         Yii::$app->runAction('email/enviar-email-gabinete-tecnico', ['id' => $model->matc_id]);
         Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
      
         return $this->redirect(['index']);
      }
   }

   public function actionReprovar($id) 
   {
      $session = Yii::$app->session;

      $model = $this->findModel($id);

      $model->matc_dataGer     = date('Y-m-d H:i:s');
      $model->matc_ResponsavelGer = $session['sess_nomeusuario'];

      //-------atualiza a situação pra aprovado pela gerência do setor
      Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 8 , `matc_autorizadoGer` = 0, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" WHERE `matc_id` = '.$model->matc_id.'')->execute();

      $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;

      $model->situacao_id = 8;
      if($model->situacao_id == 8){
         //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DO GABINETE TÉCNICO INFORMANDO SOBRE O RECEBIMENTO DE UMA NOVA SOLICITAÇÃO DE CÓPIA 
         Yii::$app->runAction('email/enviar-email-reprovacao-gerencia', ['id' => $model->matc_id]);
      } 

      Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
      return $this->redirect(['index']);
   }

   /**
    * Finds the MaterialCopiasAutGerencia model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return MaterialCopiasAutGerencia the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
       if (($model = MaterialCopiasAutGerencia::findOne($id)) !== null) {
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
