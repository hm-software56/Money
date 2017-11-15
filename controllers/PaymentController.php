<?php 
namespace app\controllers;

use Yii;
use app\models\Payment;
use app\models\PaymentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use linslin\yii2\curl;

/**
 * PaymentController implements the CRUD actions for Payment model.
 */
class PaymentController extends Controller
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
     * Lists all Payment models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PaymentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSearch()
    {
        return $this->renderPartial('search');
    }

    public function actionReport()
    {
        if (isset($_POST['type'])) {
            $type = $_POST['type'];
        } else {
            $type = NULL;
        }
        if (Yii::$app->session['user']->user_type == "Admin") {
            $model = Payment::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['between', 'payment.date', date("Y-m-d", strtotime('monday this week')), date("Y-m-d", strtotime('sunday this week'))])->orderBy('payment.date DESC')->all();
            $model_pre = Payment::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['between', 'payment.date', date("Y-m-d", strtotime('monday last week')), date("Y-m-d", strtotime('sunday last week'))])->orderBy('payment.date DESC')->all();
            $model_m = Payment::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['month(payment.date)' => date('m')])->orderBy('payment.date DESC')->all();
            $model_m_pre = Payment::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['month(payment.date)' => date('m', strtotime("-1 month"))])->orderBy('payment.date DESC')->all();
            $model_y = Payment::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['year(payment.date)' => date('Y')])->orderBy('payment.date DESC')->all();
        } else {
            $model = Payment::find()->where(['user_id' => \Yii::$app->session['user']->id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['between', 'date', date("Y-m-d", strtotime('monday this week')), date("Y-m-d", strtotime('sunday this week'))])->orderBy('date DESC')->all();
            $model_pre = Payment::find()->where(['user_id' => \Yii::$app->session['user']->id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['between', 'date', date("Y-m-d", strtotime('monday last week')), date("Y-m-d", strtotime('sunday last week'))])->orderBy('date DESC')->all();
            $model_m_pre = Payment::find()->where(['user_id' => \Yii::$app->session['user']->id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['month(date)' => date('m', strtotime("-1 month"))])->orderBy('date DESC')->all();
            $model_m = Payment::find()->where(['user_id' => \Yii::$app->session['user']->id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['month(date)' => date('m')])->orderBy('date DESC')->all();
            $model_y = Payment::find()->where(['user_id' => \Yii::$app->session['user']->id])->andFilterWhere(['like', 'type_pay_id', $type])->andWhere(['year(date)' => date('Y')])->orderBy('date DESC')->all();
        }
        return $this->render('report', ['model' => $model, 'model_pre' => $model_pre, 'model_m' => $model_m, 'model_m_pre' => $model_m_pre, 'model_y' => $model_y]);
    }

    /** 
     * Displays a single Payment model.
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
     * Creates a new Payment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Payment();
        $model->refer_id = Yii::$app->params['refer_id'];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // send to API
            $curl = new curl\Curl();
            $value = array(
                'save_payment' => true,
                'amount' => $model->amount,
                'refer_id' => $model->refer_id,
                'description' => $model->description,
                'date' => $model->date,
                'type_pay_id' => $model->type_pay_id,
                'user_id' => $model->user_id,
            );
            $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
            // end send API
            \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ລາຍ​ຈ່າຍ​ຖືກ​ເກັບ​ໄວ້​ໃນ​ລະ​ບົບ​ແລ້ວ'));
            \Yii::$app->getSession()->setFlash('action', \Yii::t('app', ''));
            $to = "daxionginfo@gmail.com";
            $subject = "ປ້ອນ​ລາຍ​ຈ່າຍ (" . $model->user->first_name . ")";
            $tilte = "ຈ່າຍໂດຍ: (" . $model->user->first_name . ")<br/>";
            $body = "ປະ​ເພດ​ລາຍ​ຈ່າຍ: " . $model->typePay->name . "<br/>";
            if (!empty($model->description)) {
                $body.=$model->description . '<br/>';
            }
            $body.="ຈຳ​ນວນ​ເງີນ​ຈ່າຍ: " . number_format($model->amount) . "ກີບ<br/>";
            $body.="ວັ​ນ​ທີຈ່າຍ: " . $model->date;

            $sms = 'ຈ່າຍໂດຍ:' . $model->user->first_name . ", ປະ​ເພດ​ລາຍ​ຈ່າຍ:" . $model->typePay->name . ', ຈຳ​ນວນ​ເງີນ​ຈ່າຍ:' . number_format($model->amount) . 'ກີບ, ວັ​ນ​ທີຈ່າຍ:' . $model->date;
            $payment_notification = Payment::onesignalnotification($sms);

            $sms = new \app\models\Sms();
            $sms->details = $body;
            $sms->title = $tilte;
            $sms->by_user = $model->user_id;
            $sms->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Payment model.
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
                'update_payment' => true,
                'id' => $model->id,
                'refer_id' => $model->refer_id,
                'amount' => $model->amount,
                'description' => $model->description,
                'date' => $model->date,
                'type_pay_id' => $model->type_pay_id,
                'user_id' => $model->user_id,
            );
            $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
            // end send API

            \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ທ່ານ​ສຳ​ເລັດ​ການ​ແກ້​ໄຂ​ແລ້ວ'));
            \Yii::$app->getSession()->setFlash('action', \Yii::t('app', 'ແກ້​ໄຂ'));
            $to = "daxionginfo@gmail.com";
            $subject = "ແກ້​ໄຂລາຍ​ຈ່າຍ (" . $model->user->first_name . ")";
            $title = "ແກ້​ໄຂໂດຍ: (" . $model->user->first_name . ")<br/>";
            $body = "ປະ​ເພດ​ລາຍ​ຈ່າຍ: " . $model->typePay->name . "<br/>";
            if (!empty($model->description)) {
                $body.=$model->description . '<br/>';
            }
            $body.="ຈຳ​ນວນ​ເງີນ​ຈ່າຍ: " . number_format($model->amount) . "ກີບ<br/>";
            $body.="ວັ​ນ​ທີຈ່າຍ: " . $model->date;
            /* $headers = "MIME-Version: 1.0" . "\r\n";
              $headers.="Content-Type: text/html; charset=utf-8" . "\r\n";
              $headers.="From: {$to}\r\nReply-To: {$to}";
              mail($to, $subject, $body, $headers); */

            $sms = new \app\models\Sms();
            $sms->details = $body;
            $sms->title = $title;
            $sms->by_user = $model->user_id;
            $sms->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Payment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $to = "daxionginfo@gmail.com";
        $subject = "ລືບລາຍ​ຈ່າຍ (" . $model->user->first_name . ")";
        $title = "ລືບລາຍ​ຈ່າຍໂດຍ: (" . $model->user->first_name . ")<br/>";
        $body = "ປະ​ເພດ​ລາຍ​ຈ່າຍ: " . $model->typePay->name . "<br/>";
        $body.="ຈຳ​ນວນ​ເງີນ​ຈ່າຍ: " . number_format($model->amount) . "ກີບ<br/>";
        $body.="ວັ​ນ​ທີຈ່າຍ: " . $model->date;
        /* $headers = "MIME-Version: 1.0" . "\r\n";
          $headers.="Content-Type: text/html; charset=utf-8" . "\r\n";
          $headers.="From: {$to}\r\nReply-To: {$to}";
          mail($to, $subject, $body, $headers); */
        $sms = new \app\models\Sms();
        $sms->details = $body;
        $sms->title = $title;
        $sms->by_user = $model->user_id;
        $sms->save();

        $this->findModel($id)->delete();

        // send to API
        $curl = new curl\Curl();
        $value = array(
            'delete_payment' => true,
            'refer_id' => $model->refer_id
        );
        $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
        // end send API
        return $this->redirect(['index']);
    }

    /**
     * Finds the Payment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Payment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Payment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReportchart()
    {
        return $this->render('reportchart');
    }
}
