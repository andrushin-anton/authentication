<?php

class LoginAction extends CAction
{
    const MIGRATION_START_DATE = '2014-06-30';

    /**
     * Action login
     */
    public function run()
    {
        /* @var $request CHttpRequest */
        $request = Yii::app()->getRequest();
        if (isset($_POST['LoginForm'])) {
            $formData = $_POST['LoginForm'];
            $username = isset($formData['username']) ? filter_var($formData['username'], FILTER_SANITIZE_EMAIL) : '';
            $password = isset($formData['password']) ? filter_var($formData['password'], FILTER_SANITIZE_STRING) : '';
            $token = isset($formData['token']) ? filter_var($formData['token'], FILTER_SANITIZE_STRING) : '';

            $auth = AuthManager::getInstance();
            if ($auth->getCSRFToken() != $token) {
                $this->sendError(TFException::ERROR_BAD_CSRF);
            } else if ($auth->login($username, $password)) {
                if ($this->isMigrationEnabled()) {
                    $identity = $auth->getCurrentIdentity();
                    $identityModel = $identity->getModel();
                    $sql = 'SELECT * FROM `tradefort`.`TFMIGRATION` WHERE '
                        . ($identity instanceof CPartnerIdentity
                            ? 'PARTNER_ID='. $identity->id . ($identityModel->USER_ID ? ' OR USER_ID='. $identityModel->USER_ID : '')
                            : 'USER_ID='. $identity->id);
                    $migrationModel = ACCOUNT::model()->getDbConnection()->createCommand($sql)->queryRow(true);

                    if ($identityModel->CREATED < strtotime(self::MIGRATION_START_DATE) && (!$migrationModel || $migrationModel['STATE'] != 1)) {
                        $msg = Utils::getLang() == 'ru'
                            ? 'Чтобы войти в Кабинет FortFS, Вам нужно подтвердить переход в Кабинете ТрейдФорт'
                            : 'In order to access FortFS cabinet, you have to confirm your transition in TradeFort cabinet';
                        $auth->logout();
                        throw new TFException($msg);
                    }
                }


                // successfully authenticated
                echo CJSON::encode(array('token' => $auth->getSessionToken()));
                Yii::app()->end();
            } else if ($auth::ERROR_LOGIN_ATTEMPTS_FAIL_NUMBER == $auth->getError()) {
                $auth->setFailedLoginUsername($username);
                $this->sendError(TFException::ERROR_ATTEMPTS_NUMBER);
            }
        }
        $this->sendError(TFException::HTTP_BAD_REQUEST);
    }

    /**
     * @param integer $errorCode [optional]
     * @throws TFException
     */
    protected function sendError($errorCode = null)
    {
        null == $errorCode && $errorCode = TFException::STDERROR;
        $message = Utils::isSite() ? SiteUtils::t('auth', 'incorrect_data') : Utils::t('auth', 'incorrect_data');
        throw new TFException($message, $errorCode, TFException::HTTP_FORBIDDEN);
    }


    /**
     * @return boolean
     */
    protected function isMigrationEnabled()
    {
        return self::MIGRATION_START_DATE <= date('Y-m-d')
            || 'superDuperTester' == Yii::app()->request->userAgent;
    }

}