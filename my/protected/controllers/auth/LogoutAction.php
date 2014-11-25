<?php

class LogoutAction extends CAction
{
    /**
     * Runs action
     */
    public function run()
    {
        AuthManager::getInstance()->logout();
        $this->getController()->redirect(Yii::app()->params['siteSSL']);
    }
}