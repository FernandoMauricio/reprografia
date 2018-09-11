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
        $searchModel = new MaterialCopiasAutGerenciaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

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
            $model->matc_ResponsavelGer = $session['sess_nomeusuario'];

                //-------atualiza a situação pra aprovado pela gerência do setor
                Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 7 , `matc_autorizadoGer` = 1, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" WHERE `matc_id` = '.$model->matc_id.'')
                ->execute();

                $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;

             $model->situacao_id = 7;
             if($model->situacao_id == 7){

            //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DO GABINETE TÉCNICO INFORMANDO SOBRE O RECEBIMENTO DE UMA NOVA SOLICITAÇÃO DE CÓPIA 
            //-- 15 - DIVISÃO DE EDUCAÇÃO PROFISSIONAL // 87 - GABINETE TÉCNICO
                  $sql_email = "SELECT DISTINCT emus_email FROM emailusuario_emus,colaborador_col,responsavelambiente_ream,responsaveldepartamento_rede WHERE ream_codunidade = '15' AND rede_coddepartamento = '87' AND rede_codcolaborador = col_codcolaborador AND col_codusuario = emus_codusuario";
                  
                  $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
                  foreach ($email_solicitacao as $email)
                      {
                        $email_usuario  = $email["emus_email"];

                                    Yii::$app->mailer->compose()
                                    ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
                                    ->setTo($email_usuario)
                                    ->setSubject('Aprovada! - Solicitação de Cópia '.$model->matc_id.'')
                                    ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->matc_id.' com status de '.$model->situacao->sitmat_descricao.' ')
                                    ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

                                    <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

                                    <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

                                    <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

                                    <p><strong>Responsável pela Aprovação do Setor</strong>: '.$model->matc_ResponsavelGer.'</p>

                                    <p><strong>Data/Hora da Aprovação do Setor</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataGer)).'</p>

                                    <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

                                    <p>Atenciosamente,</p>

                                    <p>Divisão de Educação Profissional - DEP</p>')
                                    ->send();
                       } 

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
                Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 8 , `matc_autorizadoGer` = 0, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" WHERE `matc_id` = '.$model->matc_id.'')
                ->execute();

                $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;

             $model->situacao_id = 8;
             if($model->situacao_id == 8){

            //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A REPROVAÇÃO....
              $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";

                  $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
                  foreach ($email_solicitacao as $email)
                      {
                        $email_usuario  = $email["emus_email"];

                                    Yii::$app->mailer->compose()
                                    ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
                                    ->setTo($email_usuario)
                                    ->setSubject('Reprovada! - Solicitação de Cópia '.$model->matc_id.'')
                                    ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->matc_id.' com status de '.$model->situacao->sitmat_descricao.' ')
                                    ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

                                    <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

                                    <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

                                    <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

                                    <p><strong>Responsável pela Reprovação do Setor</strong>: '.$model->matc_ResponsavelGer.'</p>

                                    <p><strong>Data/Hora da Reprovação do Setor</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataGer)).'</p>

                                    <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

                                    <p>Atenciosamente,</p>

                                    <p>Divisão de Educação Profissional - DEP</p>')
                                    ->send();
                       } 

                Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
         
                 return $this->redirect(['index']);
            }
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
