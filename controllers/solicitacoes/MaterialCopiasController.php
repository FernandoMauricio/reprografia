<?php

namespace app\controllers\solicitacoes;

use Yii;

use app\models\MultipleModel as Model;
use app\models\planos\Planodeacao;
use app\models\cadastros\Segmento;
use app\models\base\Emailusuario;
use app\models\cadastros\Centrocusto;
use app\models\repositorio\Repositorio;
use app\models\solicitacoes\Acabamento;
use app\models\solicitacoes\MaterialCopiasItens;
use app\models\solicitacoes\MaterialCopias;
use app\models\solicitacoes\MaterialCopiasSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * MaterialCopiasController implements the CRUD actions for MaterialCopias model.
 */
class MaterialCopiasController extends Controller
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
     * Lists all MaterialCopias models.
     * @return mixed
     */
    public function actionIndex()
    {
        $this->layout = 'main-full';
        $searchModel = new MaterialCopiasSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = ['defaultOrder' => ['matc_id'=>SORT_DESC]];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionGerarRequisicao()
    {
        $model = new MaterialCopias();
 
        if ($model->load(Yii::$app->request->post())) {
            return $this->redirect(['create', 'matc_tipo' => $model->matc_tipo]);
        }
        return $this->renderAjax('gerar-requisicao', [
            'model' => $model,
        ]);
    }

    public function actionReceberRequisicao($id) 
    {
        $session = Yii::$app->session;
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_responsavelReceb` = "'.$session['sess_nomeusuario'].'", `matc_dataReceb` = "'.date('Y-m-d H:i:s').'", `situacao_id` = 9 WHERE `matc_id` = '.$model->matc_id.'')
         ->execute();
        Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> Recebido!');
            return $this->redirect(['index']);
        }
        return $this->renderAjax('receber-requisicao', [
            'model' => $model,
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
            //ENVIANDO EMAIL PARA A TERCEIRIZADA....
            Yii::$app->runAction('email/enviar-email-encaminhamento-terceirizada', ['id' => $model->matc_id]);
        }

        Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia de código:  <strong> '.$model->matc_id.'</strong> '.$model->situacao->sitmat_descricao.'!');
     
        return $this->redirect(['index']);
    }
    
    /**
     * Displays a single MaterialCopias model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    //Localiza os dados de quantidade de originais de materiais didático cadastrados no repositorio
    public function actionGetRepositorio($repId){

        $getRepositorio = Repositorio::find()->where(['rep_titulo' => $repId])->one();

        echo Json::encode($getRepositorio);
    }

    //Localiza os dados de tipos de material cadastrados no repositorio
    public function actionCentrocusto(){
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
        $parents = $_POST['depdrop_parents'];

        if ($parents != null) {
                $cat_id = $parents[0];
                $subcat_id = $parents[1];
                $out = MaterialCopias::getCentroCustoSubCat($cat_id, $subcat_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
                }
             }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    //Localiza os cursos onde foram selecionados o segmento e tipo de ação
    public function actionCursos() {
            $out = [];
            if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];

            if ($parents != null) {
                    $cat_id = $parents[0];
                    $subcat_id = $parents[1];
                    $out = MaterialCopias::getPlanodeacaoSubCat($cat_id, $subcat_id);
                    echo Json::encode(['output'=>$out, 'selected'=>'']);
                    return;
                    }
                 }
            echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    /**
     * Creates a new MaterialCopias model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $session = Yii::$app->session;
        $model = new MaterialCopias();
        $modelsItens  = [new MaterialCopiasItens];

        $model->matc_tipo = $_GET['matc_tipo']; //tipo de requisição de cópias
        if($model->matc_tipo != 1 && $model->matc_tipo != 2) {
            throw new NotFoundHttpException('A página solicitada não existe.');
        }

        $acabamento  = Acabamento::find()->all();
        $segmento    = Segmento::find()->all();
        $centrocusto = Centrocusto::find()->where(['cen_codsituacao' => 1])->orderBy('cen_codano')->all();
        $repositorio = Repositorio::find()->where(['rep_status' => 1])->andWhere(['not', ['rep_arquivo' => null]])->orderBy('rep_titulo')->all();

        $model->matc_data        = date('Y-m-d');
        $model->matc_solicitante = $session['sess_codcolaborador'];
        $model->matc_unidade     = $session['sess_codunidade'];
        $model->situacao_id      = 1;
  
        if ($model->load(Yii::$app->request->post())) {

            //Inserir vários itens na solicitação
            $modelsItens = Model::createMultiple(MaterialCopiasItens::classname());
            Model::loadMultiple($modelsItens, Yii::$app->request->post());

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsItens) && $valid;

             if ($valid ) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        foreach ($modelsItens as $i => $modelItens) {
                            $modelItens->materialcopias_id = $model->matc_id;
                            //UPLOAD DE ARQUIVOS SE FOR ESCOLHIDO O TIPO DE SERVIÇO -> 2 - IMPRESSÕES
                            if($model->matc_tipo == 2) {
                                $modelItens->file = UploadedFile::getInstance($modelItens, "[{$i}]file");
                                if (!is_null($modelItens->file)) {
                                   //criação da pasta que constará o arquivo 
                                    $path = Yii::$app->basePath . '/web/uploads/impressoes/' . $modelItens->materialcopias_id;
                                    if (!file_exists($path)) {
                                        mkdir($path, 0777);
                                    }

                                    $modelItens->item_arquivo = $modelItens->file->baseName . '.' . $modelItens->file->extension;
                                    $modelItens->item_arquivo_descricao = md5($modelItens->file->baseName) . '.' . $modelItens->file->extension;

                                   //salva o arquivo no caminho da criação da pasta
                                   Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/impressoes/' . $modelItens->materialcopias_id .'/';

                                   $path = Yii::$app->params['uploadPath'] . $modelItens->item_arquivo_descricao;
                                   $modelItens->file->saveAs($path);
                               }
                            }
                            if (! ($flag = $modelItens->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    //-------Atualiza os valores em reais de mono, color e total geral
                    //-------Se houver serviços de acabamento somará 4 reais em cada exemplar
                    $sql = 'SELECT 
                        ((item_qtexemplares*item_mono) * 0.12) AS matc_totalValorMono,
                        ((item_qtexemplares*item_color) * 0.95) AS matc_totalValorColor
                        FROM materialcopias_item 
                        INNER JOIN `materialcopias_matc` ON `materialcopias_item`.`materialcopias_id` = `materialcopias_matc`.`matc_id`
                        WHERE `materialcopias_matc`.`matc_id`='.$model->matc_id.'';
                    $total = MaterialCopias::findBySql($sql)->one();
                    Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_totalValorMono` = '.$total->matc_totalValorMono.', `matc_totalValorColor` = '.$total->matc_totalValorColor.' WHERE `matc_id` = '.$model->matc_id.'')
                    ->execute();

                    $transaction->commit();

                    //Total Mono
                    $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
                    $sumSubtotalMono = $query->sum('((item_qtexemplares*item_mono) * 0.12)');

                    //Total Color
                    $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
                    $sumSubtotalColor = $query->sum('((item_qtexemplares*item_color) * 0.95)');

                    //Total Encadernação
                    $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
                    $sumEncadernacao = $query->sum('item_qtexemplares*4');

                    //Verifica se está marca a Encadernação para somar 4 reais por exemplar
                    $sqlAcabamentos = 'SELECT DISTINCT GROUP_CONCAT(acab_descricao) AS acab_descricao
                        FROM acabamento_acab
                        INNER JOIN `copiasacabamento_copac` ON `copiasacabamento_copac`.`acabamento_id` = `acabamento_acab`.`id`
                        WHERE `materialcopias_id`='.$model->matc_id.'';
                    $encadernacaoFind = Acabamento::findBySql($sqlAcabamentos)->one();

                    if(strpos($encadernacaoFind['acab_descricao'], 'Encadernação') !== false){
                        $model->matc_totalGeral = $sumSubtotalMono + $sumSubtotalColor + $sumEncadernacao;
                        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_totalGeral` = '.$model->matc_totalGeral.' WHERE `matc_id` = '.$model->matc_id.'')
                        ->execute();

                    }else{
                        $model->matc_totalGeral = $sumSubtotalMono + $sumSubtotalColor;
                        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_totalGeral` = '.$model->matc_totalGeral.' WHERE `matc_id` = '.$model->matc_id.'')
                        ->execute();
                    }

                    $model->matc_dataGer     = date('Y-m-d H:i:s');
                    $model->matc_dataAut     = date('Y-m-d H:i:s');
                    $model->matc_ResponsavelGer = $session['sess_nomeusuario'];
                    $model->matc_ResponsavelAut = $session['sess_nomeusuario'];

                    if($model->matc_tipo == 2 && $session['sess_responsavelsetor'] == 1) { //Se for Impressão Avulsa, será aprovado também a DEP automaticamente
                      //-------atualiza a situação pra aprovado pela gerência do setor e pela DEP
                       Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 2 , `matc_autorizadoGer` = 1, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" , `matc_autorizado` = 1, `matc_ResponsavelAut` = "'.$model->matc_ResponsavelAut.'" , `matc_dataAut` = "'.$model->matc_dataAut.'" WHERE `matc_id` = '.$model->matc_id.'')
                       ->execute();
                    }else{
                        //ENVIANDO EMAIL PARA O GERENTE DO SETOR INFORMANDO SOBRE A SOLICITAÇÃO PENDENTE DE AUTORIZAÇÃO
                        Yii::$app->runAction('email/enviar-email-autorizacao-gerencia', ['id' => $model->matc_id]);
                    }
                    //ENVIANDO EMAIL PARA O GERENTE DO SETOR INFORMANDO SOBRE A SOLICITAÇÃO PENDENTE DE AUTORIZAÇÃO
                    Yii::$app->runAction('email/enviar-email-autorizacao-gerencia', ['id' => $model->matc_id]);
                    Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia cadastrada!</strong>');

                return $this->redirect(['index']);

                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        
        return $this->render('create', [
            'model'       => $model,
            'segmento'    => $segmento,
            'repositorio' => $repositorio,
            'acabamento'  => $acabamento,
            'centrocusto' => $centrocusto,
            'modelsItens' => (empty($modelsItens)) ? [new MaterialCopiasItens] : $modelsItens,
        ]);
    }

    public function actionObservacoes($id) 
    {
        $model = MaterialCopias::findOne($id);
        $session = Yii::$app->session;
        $session->set('sess_materialcopias', $model->matc_id);

        return $this->redirect(['solicitacoes/material-copias-justificativas/observacoes', 'id' => $model->matc_id]);
    }

    /**
     * Updates an existing MaterialCopias model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $session = Yii::$app->session;

        $model = $this->findModel($id);
        $modelsItens = $model->materialCopiasItens;

        $centrocusto = Centrocusto::find()->where(['cen_codsituacao' => 1])->orderBy('cen_codano')->all();
        $repositorio = Repositorio::find()->where(['rep_status' => 1])->andWhere(['not', ['rep_arquivo' => null]])->orderBy('rep_titulo')->all();
        $acabamento = Acabamento::find()->where(['acab_status' => 1])->all();
        //Retrieve the stored checkboxes
        $model->listAcabamento = \yii\helpers\ArrayHelper::getColumn(
            $model->getCopiasAcabamento()->asArray()->all(),
            'acabamento_id'
        );

        $model->matc_data        = date('Y-m-d');
        $model->matc_solicitante = $session['sess_codcolaborador'];
        $model->matc_unidade     = $session['sess_codunidade'];
        $model->situacao_id      = 1;
        $model->matc_ResponsavelAut = NULL;
        $model->matc_dataAut = NULL;
        
        $model->matc_totalGeral = $model->matc_totalValorMono + $model->matc_totalValorColor;

        if ($model->load(Yii::$app->request->post())) {

            //Inserir vários itens na solicitação
            $oldIDs = ArrayHelper::map($modelsItens, 'id', 'id');
            $modelsItens = Model::createMultiple(MaterialCopiasItens::classname(), $modelsItens);
            Model::loadMultiple($modelsItens, Yii::$app->request->post());
            $deletedIDs = array_diff($oldIDs, array_filter(ArrayHelper::map($modelsItens, 'id', 'id')));

            // validate all models
            $valid = $model->validate();
            $valid = Model::validateMultiple($modelsItens) && $valid;

             if ($valid ) {
                $transaction = \Yii::$app->db->beginTransaction();
                try {
                    if ($flag = $model->save(false)) {
                        if (!empty($deletedIDs)) {
                            MaterialCopiasItens::deleteAll(['id' => $deletedIDs]);
                        }
                        foreach ($modelsItens as $i => $modelItens) {
                            $modelItens->materialcopias_id = $model->matc_id;
                            //UPLOAD DE ARQUIVOS SE FOR ESCOLHIDO O TIPO DE SERVIÇO -> 2 - IMPRESSÕES
                            if($model->matc_tipo == 2) {
                                $modelItens->file = UploadedFile::getInstance($modelItens, "[{$i}]file");
                                if (!is_null($modelItens->file)) {
                                   //criação da pasta que constará o arquivo 
                                    $path = Yii::$app->basePath . '/web/uploads/impressoes/' . $modelItens->materialcopias_id;
                                    if (!file_exists($path)) {
                                        mkdir($path, 0777);
                                    }

                                    $modelItens->item_arquivo = $modelItens->file->baseName . '.' . $modelItens->file->extension;
                                    $modelItens->item_arquivo_descricao = md5($modelItens->file->baseName) . '.' . $modelItens->file->extension;

                                   //salva o arquivo no caminho da criação da pasta
                                   Yii::$app->params['uploadPath'] = Yii::$app->basePath . '/web/uploads/impressoes/' . $modelItens->materialcopias_id .'/';

                                   $path = Yii::$app->params['uploadPath'] . $modelItens->item_arquivo_descricao;
                                   $modelItens->file->saveAs($path);
                               }
                            }
                            if (! ($flag = $modelItens->save(false))) {
                                $transaction->rollBack();
                                break;
                            }
                        }
                    }
                    //-------Atualiza os valores em reais de mono, color e total geral
                    //-------Se houver serviços de acabamento somará 4 reais em cada exemplar
                    $sql = 'SELECT 
                        ((item_qtexemplares*item_mono) * 0.12) AS matc_totalValorMono,
                        ((item_qtexemplares*item_color) * 0.95) AS matc_totalValorColor
                        FROM materialcopias_item 
                        INNER JOIN `materialcopias_matc` ON `materialcopias_item`.`materialcopias_id` = `materialcopias_matc`.`matc_id`
                        WHERE `materialcopias_matc`.`matc_id`='.$model->matc_id.'';
                    $total = MaterialCopias::findBySql($sql)->one();
                    Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_totalValorMono` = '.$total->matc_totalValorMono.', `matc_totalValorColor` = '.$total->matc_totalValorColor.' WHERE `matc_id` = '.$model->matc_id.'')
                    ->execute();

                    //Total Mono
                    $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
                    $sumSubtotalMono = $query->sum('((item_qtexemplares*item_mono) * 0.12)');

                    //Total Color
                    $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
                    $sumSubtotalColor = $query->sum('((item_qtexemplares*item_color) * 0.95)');

                    //Total Encadernação
                    $query = (new \yii\db\Query())->from('materialcopias_item')->where(['materialcopias_id' => $model->matc_id]);
                    $sumEncadernacao = $query->sum('item_qtexemplares*4');

                    //Verifica se está marca a Encadernação para somar 4 reais por exemplar
                    $sqlAcabamentos = 'SELECT DISTINCT GROUP_CONCAT(acab_descricao) AS acab_descricao
                        FROM acabamento_acab
                        INNER JOIN `copiasacabamento_copac` ON `copiasacabamento_copac`.`acabamento_id` = `acabamento_acab`.`id`
                        WHERE `materialcopias_id`='.$model->matc_id.'';
                    $encadernacaoFind = Acabamento::findBySql($sqlAcabamentos)->one();

                    if(strpos($encadernacaoFind['acab_descricao'], 'Encadernação') !== false){
                        $model->matc_totalGeral = $sumSubtotalMono + $sumSubtotalColor + $sumEncadernacao;
                        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_totalGeral` = '.$model->matc_totalGeral.' WHERE `matc_id` = '.$model->matc_id.'')
                        ->execute();

                    }else{
                        $model->matc_totalGeral = $sumSubtotalMono + $sumSubtotalColor;
                        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `matc_totalGeral` = '.$model->matc_totalGeral.' WHERE `matc_id` = '.$model->matc_id.'')
                        ->execute();
                    }

                    if ($flag && $session['sess_responsavelsetor'] == 0) {
                        $transaction->commit();
                        //ENVIANDO EMAIL PARA O GERENTE DO SETOR INFORMANDO SOBRE A SOLICITAÇÃO PENDENTE DE AUTORIZAÇÃO
                        Yii::$app->runAction('email/enviar-email-autorizacao-gerencia', ['id' => $model->matc_id]);
                        Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia cadastrada!</strong>');
                    }
                return $this->redirect(['index']);

                    if ($flag && $session['sess_responsavelsetor'] == 1) {
                        //SE FOR GERENTE ENVIA DIRETAMENTE PARA A DEP COM A AUTORIZAÇÃO DO SETOR
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia cadastrada!</strong>');

                        $model->matc_dataGer     = date('Y-m-d H:i:s');
                        $model->matc_ResponsavelGer = $session['sess_nomeusuario'];

                        //-------atualiza a situação pra aprovado pela gerência do setor
                        Yii::$app->db->createCommand('UPDATE `materialcopias_matc` SET `situacao_id` = 7 , `matc_autorizadoGer` = 1, `matc_ResponsavelGer` = "'.$model->matc_ResponsavelGer.'" , `matc_dataGer` = "'.$model->matc_dataGer.'" WHERE `matc_id` = '.$model->matc_id.'')
                        ->execute();

                            $model->situacao_id = 7;
                            if($model->situacao_id == 7){
                                //ENVIANDO EMAIL PARA OS RESPONSÁVEIS DO GABINETE TÉCNICO INFORMANDO SOBRE O RECEBIMENTO DE UMA NOVA SOLICITAÇÃO DE CÓPIA 
                                Yii::$app->runAction('email/enviar-email-gabinete-tecnico', ['id' => $model->matc_id]);
                            }
                        Yii::$app->session->setFlash('success', '<strong>SUCESSO! </strong> Solicitação de Cópia cadastrada!</strong>');
                return $this->redirect(['index']);
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                }
            }
        }
        
        return $this->render('update', [
            'model'       => $model,
            'repositorio' => $repositorio,
            'acabamento'  => $acabamento,
            'centrocusto' => $centrocusto,
            'modelsItens' => (empty($modelsItens)) ? [new MaterialCopiasItens] : $modelsItens,
        ]);
    }


    /**
     * Deletes an existing MaterialCopias model.
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
