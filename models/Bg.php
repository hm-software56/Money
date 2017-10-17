<?php namespace app\models;

use Yii;

/**
 * This is the model class for table "bg".
 *
 * @property integer $id
 * @property string $bg_menu
 * @property string $bg_footer
 * @property string $bg_button
 */
class Bg extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bg';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bg_menu', 'bg_footer', 'bg_button', 'bg_button_over'], 'required', 'message' => 'ທ່ານ​ຕ້ອງ​ປ້ອນ​ {attribute}'],
            [['bg_menu', 'bg_footer', 'bg_button'], 'string', 'max' => 40],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bg_menu' => 'ສີ​ພື້ນ​ເມ​ນູ',
            'bg_footer' => 'ສີ​ພື້ນ​ລູ່ມ',
            'bg_button' => 'ສີ​ພື້ນ​ປຸ່ມ',
            'bg_button_over' => 'ສີ​ພື້ນ​ກົດປຸ່ມ',
        ];
    }
}
