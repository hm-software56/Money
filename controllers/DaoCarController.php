<?php namespace app\controllers;

use Yii;
use app\models\DaoCar;
use app\models\DaoCarSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use linslin\yii2\curl;

/**
 * DaoCarController implements the CRUD actions for DaoCar model.
 */
class DaoCarController extends Controller
{

    public function beforeAction($action)
    {

        if (empty(\Yii::$app->session['user'])) {
            if (Yii::$app->controller->action->id != "login") {
                $this->redirect(['site/login']);
            }
        } elseif (Yii::$app->session['timeout'] < date('dHi')) {
            unset(\Yii::$app->session['user']);
            $this->redirect(['site/login']);
        } else {
            Yii::$app->session['timeout'] = Yii::$app->params['timeout'];
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all DaoCar models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DaoCarSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 80;
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DaoCar model.
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
     * Creates a new DaoCar model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new DaoCar();
        $model->refer_id = Yii::$app->params['refer_id'];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // send to API
            $curl = new curl\Curl();
            $value = array(
                'save_daocar' => true,
                'amount' => $model->amount,
                'refer_id' => $model->refer_id,
                'status' => $model->status,
                'date' => $model->date,
                'remark' => $model->remark,
            );
            if ($model->status == 'Paid') {
                $model->status = 'ຈ່າຍ​ແລ​້ວ';
            } elseif ($model->status == 'Saving') {
                $model->status = 'ເກັບ​ໄວ້';
            } else {
                $model->status = 'ເອົາ​ໃຊ້​ແນວ​ອື່ນ';
            }
            $sms = 'ຈຳ​ນວນ​ເງີນ​:' . number_format($model->amount) . 'ກີບ,' . 'ສະ​ຖາ​ນະ:' . $model->status . ', ວັ​ນ​ທີ:' . $model->date;
            $payment_notification = \app\models\Payment::onesignalnotification($sms);

            $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
            // end send API

            \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ລາຍ​ຈ່າຍ​ຖືກ​ເກັບ​ໄວ້​ໃນ​ລະ​ບົບ​ແລ້ວ'));
            \Yii::$app->getSession()->setFlash('action', \Yii::t('app', ''));
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing DaoCar model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // send to API
            $curl = new curl\Curl();
            $value = array(
                'update_daocar' => true,
                'amount' => $model->amount,
                'refer_id' => $model->refer_id,
                'status' => $model->status,
                'date' => $model->date,
                'remark' => $model->remark,
            );
            $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
            // end send API

            \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ທ່ານ​ສຳ​ເລັດ​ການ​ແກ້​ໄຂ​ແລ້ວ'));
            \Yii::$app->getSession()->setFlash('action', \Yii::t('app', 'ແກ້​ໄຂ'));
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing DaoCar model.
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
     * Finds the DaoCar model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DaoCar the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DaoCar::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
