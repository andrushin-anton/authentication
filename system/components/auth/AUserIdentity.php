<?php

abstract class AUserIdentity extends CUserIdentity
{
    const ERROR_USERNAME_PASSWORD_INVALID = 1;
    const ERROR_PROJECT_INVALID = 2;
    const MODE_PROJECT_IGNORE = 1001;

    protected $mid;
    protected $model;
    protected $fullName;
    protected $email;
    protected $lastActivityAt;

    protected $isAuthPassed;

    protected $enableGodMode = false;

    /**
     * @return CDbConnection
     */
    public function getDbConnection()
    {
        return Utils::isSite() ? Yii::app()->db_tradefort : Yii::app()->db;
    }

    /**
     * Resets all cached data
     */
    public function reset()
    {
        $this->errorCode = self::ERROR_UNKNOWN_IDENTITY;
        $this->mid = null;
        $this->model = null;
        $this->fullName = null;
        $this->email = null;
        $this->lastActivityAt = null;

        $this->isAuthPassed = null;
    }

    /**
     * @see CUserIdentity::authenticate()
     * @param boolean $validatePwd [optional, default=true] if false given the password won't be validated
     * @param boolean $enableGodMode [optional, default=false]
     * @return boolean
     */
    public function authenticate($validatePwd = true, $enableGodMode = false)
    {
        $this->reset();
        if (!$this->getModel()) {
            $this->errorCode = self::ERROR_USERNAME_PASSWORD_INVALID;
        } else if (!$validatePwd
            || $this->getModel()->PWD && CPasswordHelper::verifyPassword($this->password, $this->getModel()->PWD)
        ) {
            //FIXME remove after start fortfs.com
            if ($this->getModel()->PWD && 'changed' === $this->getModel()->PASSWORD) {
                $this->getModel()->PASSWORD = md5($this->password);
                $this->getModel()->save();
            }
            // success
            $this->errorCode = self::ERROR_NONE;
        } else if (md5($this->password) === $this->getModel()->PASSWORD && !$this->getModel()->PWD) {
            // save new password hash
            $this->getModel()->PWD = CPasswordHelper::hashPassword($this->password);
            $this->getModel()->save();
            $this->errorCode = self::ERROR_NONE;
        } else {
            $this->errorCode = self::ERROR_USERNAME_PASSWORD_INVALID;
        }

        $this->enableGodMode = $enableGodMode;

        // successfully authenticated
        $this->isAuthPassed = !$this->errorCode;
        return !$this->errorCode;
    }

    /**
     * @param CDbCriteria|array $criteria [optional]
     * @return USER|PARTNER
     */
    abstract public function getModel($criteria = null);

    /**
     * (non-PHPdoc)
     * @see CUserIdentity::getId()
     */
    public function getId()
    {
        if (null === $this->mid) {
            $this->mid = $this->getModel()->ID;
        }
        return $this->mid;
    }

    /**
     * Returns the full name
     * @return string
     */
    public function getFullName()
    {
        if (null === $this->fullName) {
            $this->fullName = $this->getModel()->FIRSTNAME .' '. $this->getModel()->LASTNAME
                .' '. $this->getModel()->SECONDNAME;
        }
        return $this->fullName;
    }

    /**
     * Returns email
     * @return string
     */
    public function getEmail()
    {
        if (null === $this->email) {
            $this->email = $this->getModel()->EMAIL;
        }
        return $this->email;
    }

    /**
     * Returns user last activity time in unixtime
     * @return number
     */
    public function getLastActivityTime()
    {
        if (null === $this->lastActivityAt) {
            $this->lastActivityAt = strtotime($this->getModel()->LAST_ACTIVITY);
        }
        return $this->lastActivityAt;
    }

    /**
     *
     * @return AUserIdentity
     */
    public function setStateParams()
    {
        if ($this->isAuthenticated) {
            $model = $this->getModel();

            $this->setState('username', $this->getFullName());
            $this->setState('email', $this->getEmail());
            $this->setState('groups', $model instanceof PARTNER ? Utils::getGroupsByPartnerId($this->id, false) : Utils::getGroups(false, $this->id));
            $this->setState('deposit_allowed', $model->DEPOSIT_ALLOWED);
            $this->setState('lang', $model->LANGUAGE);
            $this->setState('isVip', $model->hasRelated('VIP') && $model->VIP instanceof USERVIP && USERVIP::STATUS_ACTIVE == $model->VIP->STATUS);
            // set godmode
            $this->setState('godmode', $this->enableGodMode);

            $hasPartnerAccount = $model instanceof PARTNER || isset($model->PARTNER);
            $hasTraderAccount = $model instanceof USER || isset($model->USER);

            $this->setState('isPartner', $hasPartnerAccount);
            // set partner id
            $hasPartnerAccount && $this->setState('pid', $model instanceof PARTNER ? $model->ID : $model->PARTNER->ID);
            $this->setState('isTrader', $hasTraderAccount);
            // set trader id
            $hasTraderAccount && $this->setState('uid', $model instanceof USER ? $model->ID : $model->USER->ID);
        }

        return $this;
    }
}