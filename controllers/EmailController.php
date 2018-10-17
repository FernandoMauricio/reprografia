<?php

namespace app\controllers;

use Yii;
use app\models\base\Usuario;
use app\models\base\Emailusuario;
use app\models\base\Colaborador;
use app\models\solicitacoes\MaterialCopias;
use app\models\solicitacoes\MaterialCopiasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SolicitacaoController implements the CRUD actions for MaterialCopias model.
 */
class EmailController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionEnviarEmailAutorizacaoGerencia($id)
    {
        $model = $this->findModel($id);
        $sql_email = "SELECT emus_email FROM emailusuario_emus, colaborador_col, responsavelambiente_ream WHERE ream_codunidade = '".$model->matc_unidade."' AND ream_codcolaborador = col_codcolaborador AND col_codusuario = emus_codusuario";      
        $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
        foreach ($email_solicitacao as $email) {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
            ->setTo($email["emus_email"])
            ->setSubject('Solicitação de Cópia - ' . $model->matc_id)
            ->setTextBody('Existe uma solicitação de Cópia de código: '.$model->matc_id.' - Pendente de Autorização pelo Setor')
            ->setHtmlBody('<p>Prezado(a) Senhor(a),</p>

            <p>Existe uma Solicita&ccedil;&atilde;o de Cópia de c&oacute;digo: <strong><span style="color:#F7941D">'.$model->matc_id.' </span></strong>- <strong><span style="color:#F7941D">Pendente de Autorização pelo Setor</span></strong></p>

            <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

            <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

            <p>Por favor, n&atilde;o responda esse e-mail. Acesse https://portalsenac.am.senac.br para ANALISAR a solicita&ccedil;&atilde;o de Cópia.</p>

            <p>Atenciosamente,</p>

            <p>Divisão de Educação Profissional -&nbsp;DEP</p>
            ')
            ->send();
        }
    }

    public function actionEnviarEmailReprovacaoGerencia($id)
    {
        $model = $this->findModel($id);
        //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A REPROVAÇÃO....
        $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";
        $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
        foreach ($email_solicitacao as $email) {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
            ->setTo($email["emus_email"])
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
    }

    public function actionEnviarEmailGabineteTecnico($id)
    {
        $model = $this->findModel($id);
        //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DO GABINETE TÉCNICO INFORMANDO SOBRE O RECEBIMENTO DE UMA NOVA SOLICITAÇÃO DE CÓPIA 
        //-- 15 - DIVISÃO DE EDUCAÇÃO PROFISSIONAL // 87 - GABINETE TÉCNICO
        $sql_email = "SELECT DISTINCT emus_email FROM emailusuario_emus,colaborador_col,responsavelambiente_ream,responsaveldepartamento_rede WHERE ream_codunidade = '15' AND rede_coddepartamento = '87' AND rede_codcolaborador = col_codcolaborador AND col_codusuario = emus_codusuario";
        $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
        foreach ($email_solicitacao as $email) {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
            ->setTo($email["emus_email"])
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
    }

    public function actionEnviarEmailAprovacaoGabineteTecnico($id)
    {
        $model = $this->findModel($id);
        //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A APROVAÇÃO....
        $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";
          
        $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
        foreach ($email_solicitacao as $email)
        {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
            ->setTo($email["emus_email"])
            ->setSubject('Aprovada! - Solicitação de Cópia '.$model->matc_id.'')
            ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->matc_id.' com status de '.$model->situacao->sitmat_descricao.' ')
            ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

            <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

            <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

            <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

            <p><strong>Responsável pela Aprovação</strong>: '.$model->matc_ResponsavelAut.'</p>

            <p><strong>Data/Hora da Autorização</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataAut)).'</p>

            <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

            <p>Atenciosamente,</p>

            <p>Divisão de Educação Profissional - DEP</p>')
            ->send();
        } 
    }

    public function actionEnviarEmailReprovacaoGabineteTecnico($id)
    {
        $model = $this->findModel($id);
        //ENVIANDO EMAIL PARA O USUÁRIO SOLICITANTE INFORMANDO SOBRE A REPROVAÇÃO....
        $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->materialcopias->matc_solicitante."'";
        $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
        foreach ($email_solicitacao as $email)
        {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
            ->setTo($email["emus_email"])
            ->setSubject('Reprovada! - Solicitação de Cópia '.$model->materialcopias->matc_id.'')
            ->setTextBody('Por favor, verique a situação da solicitação de cópia de código: '.$model->materialcopias->matc_id.' com status de '.$model->materialcopias->situacao->sitmat_descricao.' ')
            ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>

            <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->materialcopias->matc_id.'</strong></span> foi atualizada:</p>

            <p><strong>Situação</strong>: '.$model->materialcopias->situacao->sitmat_descricao.'</p>

            <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->materialcopias->matc_totalGeral, 2, ',', '.').'</p>

            <p><strong>Responsável pela Reprovação</strong>: '.$model->materialcopias->matc_ResponsavelAut.'</p>

            <p><strong>Data/Hora da Reprovação</strong>: '.date('d/m/Y H:i', strtotime($model->materialcopias->matc_dataAut)).'</p>

            <p><strong>Motivo da Reprovação</strong>: '.$model->descricao.'</p>

            <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

            <p>Atenciosamente,</p>

            <p>Divisão de Educação Profissional - DEP</p>')
            ->send();
        }
    }
    public function actionEnviarEmailReprografia($id)
    {
        $model = $this->findModel($id);

        //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DA REPROGRAFIA SOBRE A APROVAÇÃO DA REQUISIÇÃO
        //-- 12 - GERENCIA DE MANUTENÇÃO E TRANSPORTE - GMT // 21 - REPROGRAFIA
        $sql_emailRepro = "SELECT DISTINCT emus_email FROM emailusuario_emus,colaborador_col,responsavelambiente_ream,responsaveldepartamento_rede WHERE ream_codunidade = '12' AND rede_coddepartamento = '21' AND rede_codcolaborador = col_codcolaborador AND col_codusuario = emus_codusuario";
        $email_solicitacaoRepro = Emailusuario::findBySql($sql_emailRepro)->all(); 
        foreach ($email_solicitacaoRepro as $emailRepro)
        {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'DEP - INFORMA'])
            ->setTo([$emailRepro["emus_email"], 'maria.lourdes@am.senac.br'])
            ->setSubject('Solicitação de Cópia - ' . $model->matc_id)
            ->setTextBody('Existe uma solicitação de Cópia de código: '.$model->matc_id.' - Pendente de Encaminhamento')
            ->setHtmlBody('<p>Prezado(a), Senhor(a)</p>
            <p>A solicitação de cópia de código <span style="color:rgb(247, 148, 29)"><strong>'.$model->matc_id.'</strong></span> foi atualizada:</p>

            <p><strong>Situação</strong>: '.$model->situacao->sitmat_descricao.'</p>

            <p><strong>Total de Despesa</strong>: R$ ' .number_format($model->matc_totalGeral, 2, ',', '.').'</p>

            <p><strong>Responsável pela Aprovação</strong>: '.$model->matc_ResponsavelAut.'</p>

            <p><strong>Data/Hora da Autorização</strong>: '.date('d/m/Y H:i', strtotime($model->matc_dataAut)).'</p>

            <p>Por favor, não responda esse e-mail. Acesse https://portalsenac.am.senac.br</p>

            <p>Atenciosamente,</p>

            <p>Divisão de Educação Profissional - DEP</p>')
               ->send();
        } 
    }

    public function actionEnviarEmailEncaminhamentoReprografia($id)
    {
        $model = $this->findModel($id);
        //ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE O ENCAMINHAMENTO....
        $sql_email = "SELECT DISTINCT emus_email FROM `db_base`.emailusuario_emus, `db_base`.colaborador_col WHERE col_codusuario = emus_codusuario AND col_codcolaborador = '".$model->matc_solicitante."'";
        $email_solicitacao = Emailusuario::findBySql($sql_email)->all(); 
        foreach ($email_solicitacao as $email) 
        {
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'REPROGRAFIA - INFORMA'])
            ->setTo($email["emus_email"])
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

    public function actionEnviarEmailEncaminhamentoTerceirizada($id)
    {
        $model = $this->findModel($id);
            Yii::$app->mailer->compose()
            ->setFrom(['dep.suporte@am.senac.br' => 'REPROGRAFIA - INFORMA'])
            ->setTo('producao@poliprintam.com.br')
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

    /**
     * Finds the MaterialCopias model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MaterialCopias the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MaterialCopias::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
