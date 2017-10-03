<?php namespace app\models;

use Yii;

/**
 * This is the model class for table "payment".
 *
 * @property integer $id
 * @property integer $amount
 * @property string $description
 * @property string $date
 * @property integer $type_pay_id
 * @property integer $user_id
 *
 * @property TypePay $typePay
 * @property User $user
 */
class Payment extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'payment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amount', 'date', 'type_pay_id', 'user_id'], 'required', 'message' => 'ທ່ານ​ຕ້ອງ​ປ້ອນ​ {attribute}'],
            [['type_pay_id', 'user_id'], 'integer'],
            [['description', 'amount', 'refer_id'], 'string'],
            [['date'], 'safe'],
            [['type_pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypePay::className(), 'targetAttribute' => ['type_pay_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'amount' => 'ຈຳ​ນວນ​ເງີນ',
            'description' => 'ລາຍ​ລະ​ອຽດ​ທີ່​ຈ່າຍ',
            'date' => 'ວັນ​ທີ',
            'type_pay_id' => 'ປະ​ເພດ​ໃຊ້​ຈ່າຍ',
            'user_id' => 'User ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypePay()
    {
        return $this->hasOne(TypePay::className(), ['id' => 'type_pay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        $this->amount = substr(preg_replace('/\D/', '', $this->amount), 0, -2);
        return parent::beforeSave($insert);
    }

    public static function onesignalnotification($sms)
    {
        $content = array(
            "en" => $sms
        );

        $fields = array(
            'app_id' => "8611a545-6f5f-4e15-9e3a-b992ae4c6cac",
            'included_segments' => array('All'),
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'small_icon' => "ic_stat_onesignal_default.png",
            'large_icon' => "ic_stat_onesignal_default.png",
        );

        $fields = json_encode($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'Authorization: Basic ZjZjZjdmYjAtZTY1MC00NGQ4LWFlNDItNTQ4NzIwMGMyM2U0'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}
