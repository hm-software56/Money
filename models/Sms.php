<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property integer $id
 * @property string $title
 * @property string $details
 * @property string $date
 * @property string $user_id
 * @property integer $by_user
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'details', 'by_user'], 'required'],
            [['details'], 'string'],
            [['date'], 'safe'],
            [['by_user'], 'integer'],
            [['title', 'user_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'details' => 'Details',
            'date' => 'Date',
            'user_id' => 'User ID',
            'by_user' => 'By User',
        ];
    }
}
