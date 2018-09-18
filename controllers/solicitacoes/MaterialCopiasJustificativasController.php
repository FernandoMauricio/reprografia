<?php

namespace app\controllers\solicitacoes;

use Yii;

use app\models\base\Emailusuario;
use app\models\solicitacoes\MaterialCopias;
use app\models\solicitacoes\MaterialCopiasJustificativas;
use app\models\solicitacoes\MaterialCopiasJustificativasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaterialCopiasJustificativasController implements the CRUD actions for MaterialCopiasJustificativas model.
 */
class MaterialCopiasJustificativasController extends Controller
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
     * Lists all MaterialCopiasJustificativas models.
     * @return mixed
     */
    public function actionIndex()
    {
      $this->layout = 'main-full';
      $searchModel = new MaterialCopiasJustificativasSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $dataProvider->sort = ['defaultOrder' => ['matc_id'=>SORT_DESC]];

      return $this->render('index', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
      ]);
    }

    public function actionObservacoes($id)
    {
      $model = MaterialCopias::findOne($id);
      $searchModel = new MaterialCopiasJustificativasSearch();
      $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      $dataProvider->sort = ['defaultOrder' => ['id'=>SORT_DESC]];

      return $this->render('observacoes', [
          'searchModel' => $searchModel,
          'dataProvider' => $dataProvider,
          'model' => $model,
      ]);
    }

    /**
     * Displays a single MaterialCopiasJustificativas model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new MaterialCopiasJustificativas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
      $session = Yii::$app->session;

      $model = new MaterialCopiasJustificativas();

      $model->id_materialcopias = $session['sess_materialcopias'];
      $model->usuario = $session['sess_nomeusuario'];
      $model->data = date('Y-m-d H:i:s');

      if ($model->load(Yii::$app->request->post()) && $model->save()) {
      //envia para reprovação a solicitação de cópia que está pendente
      $sql_materialCopia = "SELECT * FROM materialcopias_matc WHERE matc_id = '".$model->id_materialcopias."' ";
      $materialCopia = MaterialCopias::findBySql($sql_materialCopia)->one(); 

      $connection = Yii::$app->db;
      $command = $connection->createCommand(
      "UPDATE `reprografia`.`materialcopias_matc` SET `situacao_id` = 3, `matc_dataAut` = '".date('Y-m-d H:i:s')."', `matc_ResponsavelAut` = '". $session['sess_nomeusuario']."'   WHERE `matc_id` = '".$materialCopia->matc_id."'");
      $command->execute();

      $model->materialcopias->matc_totalGeral = $model->materialcopias->matc_totalValorMono + $model->materialcopias->matc_totalValorColor;
      $model->materialcopias->situacao_id = 3;
      if($model->materialcopias->situacao_id == 3){
         //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A REPROVAÇÃO....
         Yii::$app->runAction('email/enviar-email-reprovacao-gabinete-tecnico', ['id' => $model->matc_id]);
      } 

      //MENSAGEM DE CONFIRMAÇÃO DA SOLICITAÇÃO DE CÓPIA REPROVADA
      Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia reprovada!</strong>');
      return $this->redirect(['solicitacoes/material-copias-pendentes/index']);
      } else {
         return $this->renderAjax('create', [
            'model' => $model,
         ]);
      }
   }

    /**
     * Deletes an existing MaterialCopiasJustificativas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
   public function actionDelete($id)
   {
       $this->findModel($id)->delete();

       return $this->redirect(['index']);
   }

   /**
    * Finds the MaterialCopiasJustificativas model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return MaterialCopiasJustificativas the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */
   protected function findModel($id)
   {
       if (($model = MaterialCopiasJustificativas::findOne($id)) !== null) {
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
