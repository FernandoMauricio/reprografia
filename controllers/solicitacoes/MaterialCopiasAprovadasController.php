<?php

namespace app\controllers\solicitacoes;

use Yii;

use app\models\base\Emailusuario;
use app\models\solicitacoes\MaterialCopiasAprovadas;
use app\models\solicitacoes\MaterialCopiasAprovadasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MaterialCopiasAprovadasController implements the CRUD actions for MaterialCopiasAprovadas model.
 */
class MaterialCopiasAprovadasController extends Controller
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
     * Lists all MaterialCopiasAprovadas models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MaterialCopiasAprovadasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEncaminharterceirizada($id)
    {
        $session = Yii::$app->session;

        $model = $this->findModel($id);

        $model->matc_dataRepro     = date('Y-m-d H:i:s');
        $model->matc_ResponsavelRepro = $session['sess_nomeusuario'];

            //-------atualiza a situação pra encaminhado a terceirizada
            Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 4, `matc_encaminhadoRepro` = 1, `matc_ResponsavelRepro` = "'.$model->matc_ResponsavelRepro.'" , `matc_dataRepro` = "'.$model->matc_dataRepro.'" WHERE `matc_id` = '.$model->matc_id.'')
            ->execute();

            $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;


         $model->situacao_id = 4;
         if($model->situacao_id == 4){

             //ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE UMA NOVA MENSAGEM....
          $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";
          
          $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
          foreach ($email_solicitacao as $email)
              {
                $email_usuario  = $email["emus_email"];

                                Yii::$app->mailer->compose()
                                ->setFrom(['reprografia.suporte@am.senac.br' => 'REPROGRAFIA - INFORMA'])
                                ->setTo($email_usuario)
                                ->setSubject(''.$model->situacao->sitmat_descricao.'! - Solicitação de Cópia '.$model->matc_id.'')
                                ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->matc_id.' com status de '.$model->situacao->sitmat_descricao.' ')
                                ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

                                <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

                                <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

                                <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

                                <p><strong>Responsável pela Aprovação</strong>: '.$model->matc_ResponsavelAut.'</p>

                                <p><strong>Data/Hora da Autorização</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataAut)).'</p>

                                <p><strong>Responsável pelo Encaminhamento</strong>: '.$model->matc_ResponsavelRepro.'</p>

                                <p><strong>Data/Hora do Encaminhamento</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataRepro)).'</p>

                                <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

                                <p>Atenciosamente,</p>

                                <p>Reprografia - SENAC AM</p>')
                                ->send();
                   } 

               }

            Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
     
             return $this->redirect(['index']);
    }

    public function actionProducaointerna($id)
    {
        $session = Yii::$app->session;

        $model = $this->findModel($id);

        $model->matc_dataRepro     = date('Y-m-d H:i:s');
        $model->matc_ResponsavelRepro = $session['sess_nomeusuario'];

            //-------atualiza a situação pra produção interna
            Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 5, `matc_encaminhadoRepro` = 0, `matc_ResponsavelRepro` = "'.$model->matc_ResponsavelRepro.'" , `matc_dataRepro` = "'.$model->matc_dataRepro.'" WHERE `matc_id` = '.$model->matc_id.'')
            ->execute();

            $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;


         $model->situacao_id = 5;
         if($model->situacao_id == 5){

             //ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE UMA NOVA MENSAGEM....
          $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";
          
          $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
          foreach ($email_solicitacao as $email)
              {
                $email_usuario  = $email["emus_email"];

                                Yii::$app->mailer->compose()
                                ->setFrom(['reprografia.suporte@am.senac.br' => 'REPROGRAFIA - INFORMA'])
                                ->setTo($email_usuario)
                                ->setSubject(''.$model->situacao->sitmat_descricao.'! - Solicitação de Cópia '.$model->matc_id.'')
                                ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->matc_id.' com status de '.$model->situacao->sitmat_descricao.' ')
                                ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

                                <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

                                <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

                                <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

                                <p><strong>Responsável pela Aprovação</strong>: '.$model->matc_ResponsavelAut.'</p>

                                <p><strong>Data/Hora da Autorização</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataAut)).'</p>
                                
                                <p><strong>Responsável pelo Encaminhamento</strong>: '.$model->matc_ResponsavelRepro.'</p>

                                <p><strong>Data/Hora do Encaminhamento</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataRepro)).'</p>

                                <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

                                <p>Atenciosamente,</p>

                                <p>Reprografia - SENAC AM</p>')
                                ->send();
                   } 

               }

            Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
     
             return $this->redirect(['index']);
    }

 public function actionFinalizar($id)
    {
        $session = Yii::$app->session;

        $model = $this->findModel($id);

        if($model->situacao_id == 2){

        Yii::$app->session->setFlash('warning', '<strong>AVISO! </strong> Não é possível <strong>FINALIZAR</strong> a Solicitação de Cópia de código: ' . '<strong>' .$model->matc_id. '</strong>' . ' pois a mesma está com status de  ' . '<strong>' . $model->situacao->sitmat_descricao . '.</strong> Por gentileza, insira um encaminhamento!');

        return $this->redirect(['index']);

                }else 
            //-------atualiza a situação pra produção interna
            Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 6 WHERE `matc_id` = '.$model->matc_id.'')
            ->execute();

            $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;


         $model->situacao_id = 6;
         if($model->situacao_id == 6){

             //ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE UMA NOVA MENSAGEM....
          $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";
          
          $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
          foreach ($email_solicitacao as $email)
              {
                $email_usuario  = $email["emus_email"];

                                Yii::$app->mailer->compose()
                                ->setFrom(['reprografia.suporte@am.senac.br' => 'REPROGRAFIA - INFORMA'])
                                ->setTo($email_usuario)
                                ->setSubject(''.$model->situacao->sitmat_descricao.'! - Solicitação de Cópia '.$model->matc_id.'')
                                ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->matc_id.' com status de '.$model->situacao->sitmat_descricao.' ')
                                ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

                                <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

                                <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

                                <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

                                <p><strong>Responsável pela Aprovação</strong>: '.$model->matc_ResponsavelAut.'</p>

                                <p><strong>Data/Hora da Autorização</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataAut)).'</p>
                                
                                <p><strong>Responsável pelo Encaminhamento</strong>: '.$model->matc_ResponsavelRepro.'</p>

                                <p><strong>Data/Hora do Encaminhamento</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataRepro)).'</p>

                                <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

                                <p>Atenciosamente,</p>

                                <p>Reprografia - SENAC AM</p>')
                                ->send();
                   } 

               }

            Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
     
             return $this->redirect(['index']);
    }

    /**
     * Finds the MaterialCopiasAprovadas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MaterialCopiasAprovadas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MaterialCopiasAprovadas::findOne($id)) !== null) {
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
