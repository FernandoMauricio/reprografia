<?php

namespace app\controllers\planos;

use Yii;
use app\models\planos\PlanoMaterial;
use app\models\planos\PlanoMaterialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PlanoMaterialController implements the CRUD actions for PlanoMaterial model.
 */
class PlanoMaterialController extends Controller
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
     * Lists all PlanoMaterial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PlanoMaterialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PlanoMaterial model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'planoMaterial' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PlanoMaterial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $planoMaterial = new PlanoMaterial();

        if ($planoMaterial->load(Yii::$app->request->post()) && $planoMaterial->save()) {
            return $this->redirect(['view', 'id' => $planoMaterial->plama_codplama]);
        } else {
            return $this->render('create', [
                'planoMaterial' => $planoMaterial,
            ]);
        }
    }

    /**
     * Updates an existing PlanoMaterial model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $planoMaterial = $this->findModel($id);

        if ($planoMaterial->load(Yii::$app->request->post()) && $planoMaterial->save()) {
            return $this->redirect(['view', 'id' => $planoMaterial->plama_codplama]);
        } else {
            return $this->render('update', [
                'planoMaterial' => $planoMaterial,
            ]);
        }
    }

    /**
     * Deletes an existing PlanoMaterial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PlanoMaterial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return PlanoMaterial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($planoMaterial = PlanoMaterial::findOne($id)) !== null) {
            return $planoMaterial;
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