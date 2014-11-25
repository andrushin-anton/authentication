<?php

class LogonAction extends CAction
{

    /**
     * Runs action
     */
    public function run()
    {
        /* @var $request CHttpRequest */
        $request = Yii::app()->getRequest();
        $auth = AuthManager::getInstance();

        if (isset($_POST['LoginForm'])) {
            $formData = $_POST['LoginForm'];
            $sessionToken = isset($formData['stoken']) ? filter_var($formData['stoken'], FILTER_SANITIZE_STRING) : '';
            $default = Yii::app()->params[(Utils::isTraderCabinet() ? 'accountSiteSSL' : 'partnerSiteSSL')];
            if ($sessionToken && $auth->logon($sessionToken)) {
                if (null != ($returnUrl = base64_decode($request->getParam('return'))) && $returnUrl != $default) {
                    $this->getController()->redirect($returnUrl);
                } else if ($auth->getRedirectUrl()) {
                    $this->getController()->redirect($auth->getRedirectUrl());
                } else if (Utils::isSite()) {
                    $this->getController()->redirect($default);
                }
            }
        }
//         throw new TFException(TFException::HTTP_BAD_REQUEST);
        $this->getController()->redirect(Yii::app()->params['siteSSL']);
    }
}