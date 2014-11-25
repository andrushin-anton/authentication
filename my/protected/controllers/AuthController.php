<?php

class AuthController extends Controller
{
    public function actions()
    {
        return array(
            'forget'    => 'my.controllers.auth.ForgetAction',
            'login'     => 'my.controllers.auth.LoginAction',
            'logon'     => 'my.controllers.auth.LogonAction',
            'logout'    => 'my.controllers.auth.LogoutAction',
            'signin'    => 'my.controllers.auth.SigninAction',
            'icpt'      => 'my.controllers.auth.CaptchaAction',
            'vcpt'      => 'my.controllers.auth.ValidateCaptchaAction',
        );
    }

	public function filters()
	{
		return array(
			'ajaxOnly + forget, login, vcpt',
            'httpsOnly'
		);
	}

    public function filterHttpsOnly($filterChain)
   	{
   		if(Yii::app()->getRequest()->getIsSecureConnection())
   			$filterChain->run();
   		else
   			throw new CHttpException(400,Yii::t('yii','Your request is invalid.'));
   	}
}