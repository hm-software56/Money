<?php namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{

    public function beforeAction($action)
    {
        if (empty(\Yii::$app->session['user'])) {
            if (Yii::$app->controller->action->id != "login") {
                if (Yii::$app->controller->action->id == "sms") {
                    $cookie = Yii::$app->request->cookies;
                    if ($cookie->has('check_sms'))
                        $cookieValue = $cookie->getValue('check_sms');
                    if (isset($cookieValue)) {
                        return $this->render('sms');
                    } else {
                        $this->redirect(['site/login']);
                    }
                } else {
                    $this->redirect(['site/login']);
                }
            }
        } elseif (Yii::$app->session['timeout'] < date('dHi')) {
            unset(\Yii::$app->session['user']);
            $this->redirect(['site/login']);
        } else {
            Yii::$app->session['timeout'] = Yii::$app->params['timeout'];
        }

        if (Yii::$app->controller->action->id == "index") {
            $this->layout = 'main_index'; //your layout name site index
        }
        if (Yii::$app->controller->action->id == "home") {
            if (isset(Yii::$app->session['rmsms'])) {
                $cookie = Yii::$app->request->cookies;
                $cookieValue = $cookie->getValue('check_sms');
                $sms = \app\models\Sms::find()->where('date>="' . date('Y-m-d H:i:s', strtotime('-10 days')) . '" and date<="' . date('Y-m-d H:i:s', strtotime('1 days')) . '"')->orderBy('id DESC')->all();
                foreach ($sms as $sms) {
                    if (!in_array($cookieValue, array($sms->user_id))) {
                        if (empty($sms->user_id)) {
                            $sms->user_id = "$cookieValue";
                        } else {
                            $sms->user_id = $sms->user_id . "," . $cookieValue;
                        }
                        $sms->save();
                        unset(Yii::$app->session['rmsms']);
                    }
                }
            }
        }
        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        if(!isset($_GET['index']))
        {
             unset(\Yii::$app->session['user']);
             return $this->redirect(['login']);
        }else{
        return $this->render('index');
        }
    }

    public function actionSms()
    {
        Yii::$app->session['rmsms'] = TRUE;
        if (isset($_GET['id'])) {
            $sms = \app\models\Sms::find()->where(['id' => $_GET['id']])->one();
            $cookie = Yii::$app->request->cookies;
            $cookieValue = $cookie->getValue('check_sms');
            if (!in_array($cookieValue, array($sms->user_id))) {
                if (empty($sms->user_id)) {
                    $sms->user_id = "$cookieValue";
                } else {
                    $sms->user_id = $sms->user_id . "," . $cookieValue;
                }
                $sms->save();
            }
            $a = 'ອ່ານ​ແລ້ວ...';
        } else {
            $a = NULL;
        }
        return $this->render('sms', ['a' => $a]);
    }

    public function actionPage()
    {
        return $this->render('page');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $login = new \app\models\User();
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post())) {
            $user = \app\models\User::findOne(['username' => $model->username, 'password' => $model->password]);
            if (!empty($user->id)) {
                $user->date=date('Y-m-d');
                $user->save();
                \Yii::$app->session['user'] = $user;
                \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ທ່ານ​ເຂົ້າ​ລະ​ບົບ​ຖືກ​ຕ້ອງກຳ​ລັງ​ເຂົ້າ​ຫາ​ຂໍ້​ມູນ​......'));
                \Yii::$app->getSession()->setFlash('action', \Yii::t('app', ''));
                \Yii::$app->session['timeout'] = Yii::$app->params['timeout'];
                if ($user->id == "15") {
                    return $this->redirect(['products/index']);
                } else {
                    $cookies = Yii::$app->response->cookies;
                    $cookies->add(new \yii\web\Cookie([
                        'name' => 'check_sms',
                        'value' => $user->id,
                        //   'expire' => time() + 86400 * 365,
                    ]));
                    return $this->redirect(['site/home']);
                }
            } else {
                \Yii::$app->getSession()->setFlash('su', \Yii::t('app', 'ທ່ານ​ປ້ອນ​ຊື່ຫຼື​ລະ​ຫັດ​ເຂົ້າ​ລະ​ບົບ​ບໍ່ຖືກ'));
                \Yii::$app->getSession()->setFlash('action', \Yii::t('app', ''));
            }
        }
        return $this->render('login', [
                'model' => $model,
                'login' => $login,
        ]);
    }
    public function actionCronjobchecklogin()
    { 
        $date=date('Y-m-d', strtotime(' -2 day'));
        $user=\app\models\User::find()->where("date<='".$date."' and user_type='User'")->all();
        foreach($user as $user)
        {
            $player_id=$user->player_id;
        $sms="ກະ​ລຸ​ນາ​ຕ້ອງ​ປ້ອນ​ລ​າຍ​ຈ່າຍ ຫຼື ລາຍ​ຮັບ​ຂອງ​ທ່ານ​ເຂົ້າ​ລະ​ບົບ. ຂ​ອບ​ໃຈ....";
        $payment_notification = \app\models\Payment::onesignalnotificationcrontab($sms,$player_id);
        }
        exit;
    }

    public function actionHome()
    {
        return $this->render('home');
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        unset(\Yii::$app->session['user']);
        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
                'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    public function actionReg()
    {
        $model = new \app\models\User();
        if ($model->load(Yii::$app->request->post())) {
            $model->photo = 'prof.png';
            if ($model->save()) {
                \Yii::$app->getSession()->setFlash('reg', \Yii::t('app', 'ທ່ານ​ລົງ​ທະ​ບຽນ​ສຳ​ເລັດ​ແລ້ວ​ລໍ​ຖ້າປະ​ມານ 30 ວິ​ນາ​ທີ​........'));
                \Yii::$app->getSession()->setFlash('action', \Yii::t('app', ''));
                return $this->redirect(['login']);
            } else {
                return $this->render('form', [
                        'model' => $model,
                ]);
            }
        } else {
            return $this->render('form', [
                    'model' => $model,
            ]);
        }
    }

    public function actionCompare()
    {
        return $this->render('compare');
    }
}
