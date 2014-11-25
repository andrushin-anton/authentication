<?php

class CPartnerIdentity extends AUserIdentity
{
    /**
     * (non-PHPdoc)
     * @see AUserIdentity::getModel()
     * @return PARTNER
     */
    public function getModel($criteria = null)
    {
        if (null === $this->model) {
            $model = PARTNER::model()->with('USER', 'ACCOUNT')->findByAttributes(array(
                'EMAIL' => $this->username,
            ));
            $this->model = $model ? $model : false;
        }
        if (false === $this->isAuthPassed) {
            throw new CException('Aborted. You trying to get model of unauthorized user.');
        }
        return $this->model;
    }

    /**
     * (non-PHPdoc)
     * @see AUserIdentity::setStateParams()
     */
    public function setStateParams()
    {
        parent::setStateParams();
        if ($this->isAuthenticated) {
            $model = $this->getModel();

            AuthManager::getInstance()->getSessionComponent()->add('pid', md5($model->ID));
            $this->setState('withdraw_from_client_allowed', $model->WITHDRAW_FROM_CLIENT_ALLOWED);
            $this->setState('approved_partner', $model->APPROVED);

            $isRegEnabled = null != ($acc = $model->ACCOUNT)
                && (in_array($acc->ID, array(923830, 313283, 923023, 920225)) || $acc->isMyKBPartner($acc->ID));
            $this->setState('IsRegEnabled', $isRegEnabled);
        }

        return $this;
    }
}