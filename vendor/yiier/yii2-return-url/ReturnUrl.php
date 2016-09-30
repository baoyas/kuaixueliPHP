<?php

/**
 * author     : forecho <caizhenghai@gmail.com>
 * createTime : 2016/3/16 10:14
 * description:
 */
namespace yiier\ReturnUrl;

use Yii;
use yii\base\ActionFilter;

class ReturnUrl extends ActionFilter
{
    /**
     * @var array
     */
    public $uniqueIds = ['site/login'];

    /**
     * @param \yii\base\Action $action
     * @return bool
     */
    public function beforeAction($action)
    {
        if (Yii::$app->user->isGuest) {
            if (!(Yii::$app->request->getIsAjax() || $this->isValidate())) {
                Yii::$app->user->setReturnUrl(Yii::$app->request->getUrl());
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    private function isValidate()
    {
        if (in_array(Yii::$app->controller->action->uniqueId, $this->uniqueIds)) {
            return true;
        }
        return false;
    }
}