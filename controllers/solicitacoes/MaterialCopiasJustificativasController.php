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
        $searchModel = new MaterialCopiasJustificativasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionObservacoes()
    {
        $searchModel = new MaterialCopiasJustificativasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('observacoes', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
    public function actionCreate()
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

            $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;

            $model->materialcopias->situacao_id = 3;
            if($model->materialcopias->situacao_id == 3){

             //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A REPROVAÇÃO....
              $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->materialcopias->matc_solicitante."'";
          
                      $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
                      foreach ($email_solicitacao as $email)
                          {
                            $email_usuario  = $email["emus_email"];

                                Yii::$app->mailer->compose()
                                ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
                                ->setTo($email_usuario)
                                ->setSubject('Reprovada! - Solicitação de Cópia '.$model->materialcopias->matc_id.'')
                                ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->materialcopias->matc_id.' com status de '.$model->materialcopias->situacao->sitmat_descricao.' ')
                                ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

                                <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->materialcopias->matc_id.'</strong></span> foi atualizada:</p>

                                <p><strong>Situação</strong>: '.$model->materialcopias->situacao->sitmat_descricao.'</p>

                                <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

                                <p><strong>Responsável pela Reprovação</strong>: '.$model->materialcopias->matc_ResponsavelAut.'</p>

                                <p><strong>Data/Hora da Reprovação</strong>: '.date('d/m/Y H:i', strtotime($model->materialcopias->matc_dataAut)).'</p>

                                <p><strong>Motivo da Reprovação</strong>: '.$model->descricao.'</p>

                                <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

                                <p>Atenciosamente,</p>

                                <p>Divisão de Educação Profissional - DEP</p>')
                                ->send();
                   }
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
