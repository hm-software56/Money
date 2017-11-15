<?php namespace app\controllers;

use Yii;
use app\models\RecieveMoney;
use app\models\RecieveMoneySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use linslin\yii2\curl;

/**
 * RecieveMoneyController implements the CRUD actions for RecieveMoney model.
 */
class RecieveMoneyController extends Controller
{

    /**
     * @inheritdoc
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

    public function beforeAction($action)
    {
        if (empty(\Yii::$app->session['user'])) {
            if (Yii::$app->controller->action->id != "login") {
                $this->redirect(['site/login']);
                 return FALSE;
            }
        } elseif (Yii::$app->session['timeout'] < date('dHi')) {
            unset(\Yii::$app->session['user']);
            $this->redirect(['site/login']);
             return FALSE;
        } else {
            Yii::$app->session['timeout'] = Yii::$app->params['timeout'];
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all RecieveMoney models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RecieveMoneySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 10;
        return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RecieveMoney model.
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
     * Creates a new RecieveMoney model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RecieveMoney();
        $model->refer_id = Yii::$app->params['refer_id'];
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            // send to API
            $curl = new curl\Curl();
            $value = array(
                'save_recieved' => true,
                'amount' => $model->amount,
                'refer_id' => $model->refer_id,
                'description' => $model->description,
                'date' => $model->date,
                'tye_receive_id' => $model->tye_receive_id,
                'user_id' => $model->user_id,
            );
            $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
            // end send API

            \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ລາຍ​ຮັບຖືກ​ເກັບ​ໄວ້​ໃນ​ລະ​ບົບ​ແລ້ວ.....'));
            \Yii::$app->getSession()->setFlash('action', \Yii::t('app', ''));
            $to = "daxionginfo@gmail.com";
            $subject = "ປ້ອນ​ລາຍ​ຮັບ (" . $model->user->first_name . ")";
            $title = "ຮັ​ບໂດຍ: (" . $model->user->first_name . ")<br/>";
            $body = "ປະ​ເພດ​ລາຍ​ຮັ​ບ: " . $model->tyeReceive->name . "<br/>";
            if (!empty($model->description)) {
                $body.=$model->description . '<br/>';
            }
            $body.="ຈຳ​ນວນ​ເງີນ​ຮັບ: " . number_format($model->amount) . "ກີບ<br/>";
            $body.="ວັ​ນ​ທີຮັບ: " . $model->date;

            $sms = 'ຮັ​ບໂດຍ:' . $model->user->first_name . ", ປະ​ເພດ​ລາຍ​ຮັ​ບ:" . $model->tyeReceive->name . ', ຈຳ​ນວນ​ເງີນ​ຮັບ:' . number_format($model->amount) . 'ກີບ, ວັ​ນ​ທີຮັບ:' . $model->date;
            $payment_notification = \app\models\Payment::onesignalnotification($sms);

            $sms = new \app\models\Sms();
            $sms->details = $body;
            $sms->title = $title;
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
     * Updates an existing RecieveMoney model.
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
                'update_recieved' => true,
                'id' => $model->id,
                'refer_id' => $model->refer_id,
                'amount' => $model->amount,
                'description' => $model->description,
                'date' => $model->date,
                'tye_receive_id' => $model->tye_receive_id,
                'user_id' => $model->user_id,
            );
            $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
            // end send API
            \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ທ່ານ​ສຳ​ເລັດ​ການ​ແກ້​ໄຂ​ແລ້ວລາຍ​ຮັບ​ນີ້​ແລ້ວ......'));
            \Yii::$app->getSession()->setFlash('action', \Yii::t('app', 'ແກ້​ໄຂ'));
            $to = "daxionginfo@gmail.com";
            $title = "ແກ້​ໄຂໂດຍ: (" . $model->user->first_name . ")<br/>";
            $body = "ປະ​ເພດ​ລາຍ​ຮັ​ບ: " . $model->tyeReceive->name . "<br/>";
            if (!empty($model->description)) {
                $body.=$model->description . '<br/>';
            }
            $body.="ຈຳ​ນວນ​ເງີນ​ຮັບ: " . number_format($model->amount) . "ກີບ<br/>";
            $body.="ວັ​ນ​ທີຮັບ: " . $model->date;
            /*  $headers = "MIME-Version: 1.0" . "\r\n";
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
     * Deletes an existing RecieveMoney model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $to = "daxionginfo@gmail.com";
        $subject = "ລືບລາຍ​ຮັບ (" . $model->user->first_name . ")";
        $title = "ລືບລາຍ​ຮັບໂດຍ: (" . $model->user->first_name . ")<br/>";
        $body = "ປະ​ເພດ​ລາຍ​ຮັ​ບ: " . $model->tyeReceive->name . "<br/>";
        $body.="ຈຳ​ນວນ​ເງີນ​ຮັບ: " . number_format($model->amount) . "ກີບ<br/>";
        $body.="ວັ​ນ​ທີຮັບ: " . $model->date;
        /*  $headers = "MIME-Version: 1.0" . "\r\n";
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
            'delete_recieved' => true,
            'refer_id' => $model->refer_id
        );
        $response = $curl->setOption(CURLOPT_POSTFIELDS, http_build_query($value))->post(Yii::$app->params['api_url']);
        // end send API

        return $this->redirect(['index']);
    }

    /**
     * Finds the RecieveMoney model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RecieveMoney the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RecieveMoney::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionReport()
    {
        if (Yii::$app->session['user']->user_type == "Admin") {
            $model = RecieveMoney::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andWhere(['between', 'recieve_money.date', date("Y-m-d", strtotime('monday this week')), date("Y-m-d", strtotime('sunday this week'))])->orderBy('recieve_money.date DESC')->all();
            $model_m = RecieveMoney::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andWhere(['month(recieve_money.date)' => date('m')])->orderBy('recieve_money.date DESC')->all();
            $model_y = RecieveMoney::find()->joinWith('user')->where(['user.user_role_id' => \Yii::$app->session['user']->user_role_id])->andWhere(['year(recieve_money.date)' => date('Y')])->orderBy('recieve_money.date DESC')->all();
        } else {
            $model = RecieveMoney::find()->where(['user_id' => \Yii::$app->session['user']->id])->andWhere(['between', 'date', date("Y-m-d", strtotime('monday this week')), date("Y-m-d", strtotime('sunday this week'))])->orderBy('date DESC')->all();
            $model_m = RecieveMoney::find()->where(['user_id' => \Yii::$app->session['user']->id])->andWhere(['month(date)' => date('m')])->orderBy('date DESC')->all();
            $model_y = RecieveMoney::find()->where(['user_id' => \Yii::$app->session['user']->id])->andWhere(['year(date)' => date('Y')])->orderBy('date DESC')->all();
        }
        return $this->render('report', ['model' => $model, 'model_m' => $model_m, 'model_y' => $model_y]);
    }
}
