<?php

namespace app\controllers\solicitacoes;

use Yii;

use app\models\base\Emailusuario;
use app\models\solicitacoes\MaterialCopiasAprovadas;
use app\models\solicitacoes\MaterialCopiasAprovadasSearch;
use app\models\solicitacoes\MaterialCopiasAprovadasDepSearch;
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
	   $this->layout = 'main-full';
	   $searchModel = new MaterialCopiasAprovadasSearch();
	   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	   $dataProvider->sort = ['defaultOrder' => ['matc_id'=>SORT_DESC]];

	   return $this->render('index', [
	       'searchModel' => $searchModel,
	       'dataProvider' => $dataProvider,
	   ]);
	}

	/**
	 * Lists all MaterialCopiasAprovadas models.
	 * @return mixed
	 */
	public function actionIndexGmt()
	{
	   $this->layout = 'main-full';
	   $searchModel = new MaterialCopiasAprovadasDepSearch();
	   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
	   $dataProvider->sort = ['defaultOrder' => ['matc_id'=>SORT_DESC]];
 	   return $this->render('index-gmt', [
	       'searchModel' => $searchModel,
	       'dataProvider' => $dataProvider,
	   ]);
	}
	
	public function actionEncaminharterceirizada($id)
	{
	    $session = Yii::$app->session;
	    $model = $this->findModel($id);

	    $model->matc_dataRepro = date('Y-m-d H:i:s');
	    $model->matc_ResponsavelRepro = $session['sess_nomeusuario'];

	   	 //-------atualiza a situação pra encaminhado a terceirizada
	   	 Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 4, `matc_encaminhadoRepro` = 1, `matc_ResponsavelRepro` = "'.$model->matc_ResponsavelRepro.'" , `matc_dataRepro` = "'.$model->matc_dataRepro.'" WHERE `matc_id` = '.$model->matc_id.'')
	   	 ->execute();

	    $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;
	    $model->situacao_id = 4;
	    
	    if($model->situacao_id == 4){
	        //ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE O ENCAMINHAMENTO....
			Yii::$app->runAction('email/enviar-email-encaminhamento-reprografia', ['id' => $model->matc_id]);
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
	        	//ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE O ENCAMINHAMENTO....
				Yii::$app->runAction('email/enviar-email-encaminhamento-reprografia', ['id' => $model->matc_id]);
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
	        //ENVIANDO EMAIL PARA O USUÁRIO INFORMANDO SOBRE O ENCAMINHAMENTO....
			Yii::$app->runAction('email/enviar-email-encaminhamento-reprografia', ['id' => $model->matc_id]);
			Yii::$app->runAction('email/enviar-email-reprografia', ['id' => $model->matc_id]);
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
