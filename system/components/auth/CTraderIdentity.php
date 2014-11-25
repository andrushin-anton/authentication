<?php
class CTraderIdentity extends AUserIdentity
{
    /**
     * (non-PHPdoc)
     * @see AUserIdentity::getModel()
     * @return USER
     */
    public function getModel($criteria = null)
    {
        if (null === $this->model) {
            $model = USER::model()->with('PARTNER', 'VIP', 'CQGUSER')->findByAttributes(array(
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

            AuthManager::getInstance()->getSessionComponent()->add('uid', md5($model->ID));
            $this->setState('IsWelcomeBonusAllowed', $model->WELCOMEBONUS_ALLOWED);
            $this->setState('IsCreateAccountEnabled', $model->ACCOUNTCREATE_ALLOWED);
            $this->setState('IsTransfersAllowed', $model->TRANSFER_ALLOWED || $model->TRANSFER_MY_ALLOWED);

            $sql = 'SELECT IFNULL(SUM(acs.`WBC`), 0) FROM `tradefort`.`ACCOUNT` acc, `tradefort`.`ACCOUNT_STATUS` acs
            WHERE acc.`ACTIVE` = 1 AND acc.`USER_ID` = :USER_ID AND acs.`ACCOUNT_ID` = acc.`ID`';
            $wbSum = $this->getDbConnection()->createCommand($sql)->queryScalar(array(
            	':USER_ID' => $model->ID,
            ));
            $wbAvailable = (time() - $model->CREATED) < 10 * 24 * 3600; // only for 10 days
            $this->setState('IsWelcomeBonusEnabled', $wbSum > 0 || $wbAvailable);

            $this->setState('hasCQGAccount', $model->CQGUSER && $model->CQGUSER instanceof CQGUSER);
        }

        return $this;
    }


}