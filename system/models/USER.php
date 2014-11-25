<?php

/**
 * This is the model class for table "USER".
 *
 * The followings are the available columns in table 'USER':
 * @property integer $ID
 * @property string $PWD
 * @property string $PASSWORD
 * @property string $LAST_ACTIVITY
 * @property string $FIRSTNAME
 * @property string $EMAIL
 * @property integer $STATUS
 * @property string $LASTNAME
 * @property string $PHONE
 * @property string $COUNTRY
 * @property string $ZIPCODE
 * @property string $PHONEPWD
 * @property string $ADDRESS
 * @property string $IP
 * @property string $STATE
 * @property string $LOCALITY
 * @property integer $CREATED
 * @property integer $CHANGED
 * @property integer $PARTNER_ID
 * @property integer $PHONE_VERIFICATED
 * @property integer $PHONE_CALLED
 * @property string $SECONDNAME
 * @property string $REGION
 * @property integer $STEP2SAVED
 * @property string $PROJECT
 * @property integer $DEPOSIT_ALLOWED
 * @property integer $TRANSFER_ALLOWED
 * @property integer $TRANSFER_MY_ALLOWED
 * @property integer $DEPOSITBONUS_ALLOWED
 * @property integer $WELCOMEBONUS_ALLOWED
 * @property integer $ACCOUNTCREATE_ALLOWED
 * @property integer $NEWBIE_ACCOUNT_ALLOWED
 * @property integer $WITHDRAW_TO_PARTNER
 * @property integer $REFERER
 * @property integer $REFERER_HOST
 * @property integer $INACTIVE_EMAIL_SENDED
 * @property integer $PARTNER_NETWORK_CHECKED
 * @property integer $HOWTO_TERMINAL_EMAIL_SENDED
 * @property integer $BONUS_ACCEPTED
 * @property integer $VERIFIED_NOTIFIED
 * @property integer $TRANSFER_WITHOUT_CONFIRM
 * @property integer $DEPOSIT_ONLY_PRO_ACCOUNTS
 * @property integer $SUBSCRIBE
 * @property string $EVERCOOKIE
 * @property integer $SIMILAR_TO
 * @property string $LANGUAGE
 *
 *
 *
 * @property ACCOUNT[] $ACCOUNT
 * @property PARTNER $PARTNER
 * @property USERVIP $VIP
 * @property CQGUSER $CQGUSER
 * @property ACCOUNT[] $SAFEACCOUNTS
 *
 */
class USER extends TradefortDbActiveRecord
{

    private $_updateMT = true;
    private $_selfPartner = false;

	/**
	 *
	 */
	private $_searchAllFields = array(
		"t.ID",
		"t.FIRSTNAME",
		"t.LASTNAME",
		"t.EMAIL",
        "REPLACE(t.PHONE,' ','')",
        "t.PHONE",
		"t.IP",
		"t.EVERCOOKIE",
		"CONCAT(t.FIRSTNAME,' ',t.LASTNAME)",
        "t.LOCALITY",
        "t.REGION",
	);
	/**
	 *
	 * If true than update accounts
	 * @var boolean
	 */
	public $updateMTAccounts = true;

	/**
	 * Returns the static model of the specified AR class.
	 * @return USER the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'USER';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('PASSWORD, FIRSTNAME, EMAIL, LASTNAME, PHONE, COUNTRY', 'required'),
			array('STATUS, CREATED, CHANGED, PARTNER_ID, PHONE_VERIFICATED, PHONE_CALLED, STEP2SAVED, DEPOSIT_ALLOWED, TRANSFER_ALLOWED, TRANSFER_MY_ALLOWED, DEPOSITBONUS_ALLOWED, WELCOMEBONUS_ALLOWED, ACCOUNTCREATE_ALLOWED, NEWBIE_ACCOUNT_ALLOWED, WITHDRAW_TO_PARTNER, INACTIVE_EMAIL_SENDED, HOWTO_TERMINAL_EMAIL_SENDED, BONUS_ACCEPTED, VERIFIED_NOTIFIED, TRANSFER_WITHOUT_CONFIRM, DEPOSIT_ONLY_PRO_ACCOUNTS, SUBSCRIBE, SIMILAR_TO, PARTNER_NETWORK_CHECKED', 'numerical', 'integerOnly'=>true),
			array('PASSWORD, ZIPCODE', 'length', 'max'=>32),
			array('PWD', 'length', 'max'=>128),
			array('PROJECT', 'length', 'max'=>2),
			array('FIRSTNAME, EMAIL, LASTNAME, PHONE, ADDRESS, IP, STATE, LOCALITY, SECONDNAME, REGION', 'length', 'max'=>128),
			array('COUNTRY, LANGUAGE', 'length', 'max'=>5),
			array('PHONEPWD', 'length', 'max'=>16),
			array('REFERER', 'length', 'max'=>2048),
			array('REFERER_HOST', 'length', 'max'=>128),
			array('CREATED', 'default', 'value'=>new CDbExpression('UNIX_TIMESTAMP()'), 'setOnEmpty'=>false, 'on'=>'insert'),
			array('CHANGED', 'default', 'value'=>new CDbExpression('UNIX_TIMESTAMP()'), 'setOnEmpty'=>true, 'on'=>'update'),
			array('EVERCOOKIE', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ID, PWD, PASSWORD, LAST_ACTIVITY, FIRSTNAME, EMAIL, STATUS, LASTNAME, PHONE, COUNTRY, ZIPCODE, PHONEPWD, ADDRESS, IP, STATE, LOCALITY, CREATED, CHANGED, PARTNER_ID, PHONE_VERIFICATED, PHONE_CALLED, SECONDNAME, REGION, STEP2SAVED, PROJECT, DEPOSIT_ALLOWED, TRANSFER_ALLOWED, TRANSFER_MY_ALLOWED, DEPOSITBONUS_ALLOWED, WELCOMEBONUS_ALLOWED, ACCOUNTCREATE_ALLOWED, NEWBIE_ACCOUNT_ALLOWED, WITHDRAW_TO_PARTNER, REFERER, REFERER_HOST, INACTIVE_EMAIL_SENDED, HOWTO_TERMINAL_EMAIL_SENDED, BONUS_ACCEPTED, VERIFIED_NOTIFIED, TRANSFER_WITHOUT_CONFIRM, DEPOSIT_ONLY_PRO_ACCOUNTS, SUBSCRIBE, EVERCOOKIE, SIMILAR_TO, PARTNER_NETWORK_CHECKED, LANGUAGE', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'ACCOUNT'=>array(self::HAS_MANY, 'ACCOUNT', 'USER_ID', 'condition'=>'ACCOUNT.ACTIVE = 1 OR ACCOUNT.ACTIVE IS NULL', 'joinType'=>'LEFT JOIN'),
			'USERLOG'=>array(self::HAS_MANY, 'USERLOG', 'USER_ID'),
			'PARTNERPROFIT'=>array(self::HAS_MANY, 'PARTNERPROFIT', 'USER_ID',
				'condition'=>'PARTNERPROFIT.TICKET_ID = 0',
				'order'=>'PARTNERPROFIT.LEVEL'),
			'PRIVILEGE' => array(self::MANY_MANY, 'PRIVILEGE', 'PRIVILEGE_USER(PRIVILEGE_ID, USER_ID)'),
			'ROLE' => array(self::MANY_MANY, 'ROLE', 'ROLE_USER(ROLE_ID, USER_ID)'),
			//'ACC' => array(self::HAS_MANY, 'ACCOUNT', 'USER_ID', 'select' => 'ID, GROUP_CONCAT(ACCOUNT.ID) AS ACC'),
			'SAFEACCOUNTS' => array(self::HAS_MANY, 'ACCOUNT', 'USER_ID',
                'joinType' => 'LEFT JOIN',
			    'on' => 'SAFEACCOUNTS.ACCOUNT_TYPE_ID = '. ACCOUNTTYPE::SAFE,
			),
		    'PARTNER' => array(self::HAS_ONE, 'PARTNER', 'USER_ID'),
		    'VIP' => array(self::HAS_ONE, 'USERVIP', 'USER_ID'),
		    'CQGUSER' => array(self::HAS_ONE, 'CQGUSER', 'USER_ID'),
			'REQUESTVERIFICATION' => array(self::HAS_MANY, 'REQUESTVERIFICATION', 'USER_ID')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
			'PWD' => 'Password',
			'PASSWORD' => 'Password',
			'LAST_ACTIVITY' => 'Last Activity',
			'FIRSTNAME' => 'Firstname',
			'EMAIL' => 'Email',
			'STATUS' => 'Status',
			'LASTNAME' => 'Lastname',
			'PHONE' => 'Phone',
			'COUNTRY' => 'Country',
			'ZIPCODE' => 'Zipcode',
			'PHONEPWD' => 'Phonepwd',
			'ADDRESS' => 'Address',
			'IP' => 'IP',
			'STATE' => 'State',
			'LOCALITY' => 'Locality',
			'CREATED' => 'Created',
			'CHANGED' => 'Changed',
			'PARTNER_ID' => 'Partner',
			'PHONE_VERIFICATED' => 'Phone Verificated',
			'PHONE_CALLED' => 'Phone Called',
			'SECONDNAME' => 'Secondname',
			'REGION' => 'Region',
			'STEP2SAVED' => 'Step 2 is saved',
			'PROJECT' => 'Project',
            'DEPOSIT_ALLOWED' => 'Deposit is allowed',
			'TRANSFER_ALLOWED' => 'Transfer is allowed',
			'TRANSFER_MY_ALLOWED' => 'Transfer (main accounts) is allowed',
			'DEPOSITBONUS_ALLOWED' => 'Depositbonus is allowed',
            'WELCOMEBONUS_ALLOWED' => 'Welcome bonus is allowed',
			'ACCOUNTCREATE_ALLOWED' => 'Account creation is allowed',
            'NEWBIE_ACCOUNT_ALLOWED' => 'Newbie account allowed',
			'WITHDRAW_TO_PARTNER' => 'Enable withdrawal to partner mode',
			'REFERER' => 'Referer',
			'REFERER_HOST' => 'Referer host',
			'INACTIVE_EMAIL_SENDED' => 'Inactive email sended',
            'PARTNER_NETWORK_CHECKED' => 'Partner network checked',
			'HOWTO_TERMINAL_EMAIL_SENDED' => 'How to terminal email sended',
			'BONUS_ACCEPTED' => 'Bonus accepted',
			'VERIFIED_NOTIFIED' => 'Verified and notified',
			'TRANSFER_WITHOUT_CONFIRM' => 'Transfer without confirm',
			'DEPOSIT_ONLY_PRO_ACCOUNTS' => 'Deposit only Pro accounts',
			'SUBSCRIBE' => 'Subscribe',
			'EVERCOOKIE' => 'Evercookie',
            'SIMILAR_TO' => 'Similar to',
            'LANGUAGE' => 'Language'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('ID',$this->ID);
		$criteria->compare('PWD',$this->PWD,true);
		$criteria->compare('PASSWORD',$this->PASSWORD,true);
		$criteria->compare('LAST_ACTIVITY',$this->LAST_ACTIVITY,true);
		$criteria->compare('FIRSTNAME',$this->FIRSTNAME,true);
		$criteria->compare('EMAIL',$this->EMAIL,true);
		$criteria->compare('STATUS',$this->STATUS);
		$criteria->compare('LASTNAME',$this->LASTNAME,true);
		$criteria->compare('PHONE',$this->PHONE,true);
		$criteria->compare('COUNTRY',$this->COUNTRY,true);
		$criteria->compare('ZIPCODE',$this->ZIPCODE,true);
		$criteria->compare('PHONEPWD',$this->PHONEPWD,true);
		$criteria->compare('ADDRESS',$this->ADDRESS,true);
		$criteria->compare('IP',$this->IP,true);
		$criteria->compare('STATE',$this->STATE,true);
		$criteria->compare('LOCALITY',$this->LOCALITY,true);
		$criteria->compare('CREATED',$this->CREATED);
		$criteria->compare('CHANGED',$this->CHANGED);
		$criteria->compare('PARTNER_ID',$this->PARTNER_ID);
		$criteria->compare('PHONE_VERIFICATED',$this->PHONE_VERIFICATED);
		$criteria->compare('PHONE_CALLED',$this->PHONE_CALLED);
		$criteria->compare('SECONDNAME',$this->SECONDNAME,true);
		$criteria->compare('REGION',$this->REGION,true);
		$criteria->compare('STEP2SAVED',$this->STEP2SAVED,true);
		$criteria->compare('PROJECT',$this->PROJECT,true);
        $criteria->compare('DEPOSIT_ALLOWED',$this->DEPOSIT_ALLOWED);
		$criteria->compare('TRANSFER_ALLOWED',$this->TRANSFER_ALLOWED);
		$criteria->compare('TRANSFER_MY_ALLOWED',$this->TRANSFER_MY_ALLOWED);
		$criteria->compare('DEPOSITBONUS_ALLOWED',$this->DEPOSITBONUS_ALLOWED);
        $criteria->compare('WELCOMEBONUS_ALLOWED',$this->WELCOMEBONUS_ALLOWED);
		$criteria->compare('ACCOUNTCREATE_ALLOWED',$this->ACCOUNTCREATE_ALLOWED);
        $criteria->compare('NEWBIE_ACCOUNT_ALLOWED',$this->NEWBIE_ACCOUNT_ALLOWED);
		$criteria->compare('WITHDRAW_TO_PARTNER',$this->WITHDRAW_TO_PARTNER);
		$criteria->compare('REFERER',$this->REFERER);
		$criteria->compare('REFERER_HOST',$this->REFERER_HOST);
        $criteria->compare('PARTNER_NETWORK_CHECKED',$this->PARTNER_NETWORK_CHECKED);
		$criteria->compare('INACTIVE_EMAIL_SENDED',$this->INACTIVE_EMAIL_SENDED);
		$criteria->compare('HOWTO_TERMINAL_EMAIL_SENDED',$this->HOWTO_TERMINAL_EMAIL_SENDED);
		$criteria->compare('BONUS_ACCEPTED',$this->BONUS_ACCEPTED);
		$criteria->compare('VERIFIED_NOTIFIED',$this->VERIFIED_NOTIFIED);
		$criteria->compare('TRANSFER_WITHOUT_CONFIRM',$this->TRANSFER_WITHOUT_CONFIRM);
		$criteria->compare('DEPOSIT_ONLY_PRO_ACCOUNTS',$this->DEPOSIT_ONLY_PRO_ACCOUNTS);
		$criteria->compare('SUBSCRIBE',$this->SUBSCRIBE);
        $criteria->compare('SIMILAR_TO',$this->SIMILAR_TO);
        $criteria->compare('LANGUAGE',$this->LANGUAGE);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::beforeSave()
	 */
	protected function beforeSave()
	{
	    if ('CN' == $this->COUNTRY || 'cn' == $this->PROJECT) {
			$this->WITHDRAW_TO_PARTNER = 1;
	    }
		if (isset($this->ACCOUNT) && $this->updateMTAccounts && $this->getUpdateMT()) {
			return $this->_updateMTAccounts();
		}
		return true;
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::afterSave()
	 */
	protected function afterSave()
	{
	    if (!$this->isNewRecord) {
	        $attrNames = array(
	            'FIRSTNAME', 'LASTNAME', 'SECONDNAME', 'EMAIL',
	            'COUNTRY', 'LOCALITY', 'ADDRESS', 'REGION', 'PHONE', 'ZIPCODE',
	            'LANGUAGE', 'PHONEPWD', 'PWD', 'PASSWORD',
	        );
	        $modelHasChanges = false;
	        foreach ($attrNames as $attr) {
	            if ($this->isAttributeChanged($attr)) {
	                $modelHasChanges = true;
	                break;
	            }
	        }
	        if ($modelHasChanges) {
    	        if ($this->isAttributeChanged('EMAIL')) {
    	            /* @var $supportDb CDbConnection */
    	            $supportDb = Yii::app()->dbsupport;
    	            $supportDb->createCommand()->update('swtickets', array(
    	                'email' => $this->EMAIL,
    	                'replyto' => $this->EMAIL,
    	            ), 'email = :EMAIL', array(
    	                ':EMAIL' => $this->EMAIL,
    	            ));
    	        }
    	        // update PARTNER data
    	        if (isset($this->PARTNER)) {
    	            $this->PARTNER->saveAttributes($this->getAttributes($attrNames));
    	        }
	        }
	    }

        // update accounts group
	    if (!$this->isNewRecord && $this->isAttributeChanged('PROJECT')) {
	        $accounts = ACCOUNT::model()->with('USER', 'PARTNER')->findAllByAttributes(array('USER_ID' => $this->ID));
	        $affid = $this->getAffid();
	        foreach ($accounts as $account) {
	            /* @var $account ACCOUNT */
	            if ($affid) {
	                $account->partnerAccount = $affid;
	            }
	            $account->updateGroupByProject($this->PROJECT);
	        }
	    }
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::afterDelete()
	 */
    protected function afterDelete()
    {
        parent::afterDelete();
    	//REQUESTVERIFICATION::model()->deleteAllByAttributes(array('USER_ID' => $this->ID));
    }

    /**
     * @return boolean
     */
	private function _updateMTAccounts()
	{
		$accounts = $this->ACCOUNT;
		$aIds = array(); $k = 0; $l = 0;
		foreach ($accounts as $account) {
			if (!$account->ACTIVE) {
				continue;
			}
			$k++;
			if ($k == 120) {
				$k = 0; $l++;
			}
			$aIds[$l][] = $account->ID;
		}
		foreach($aIds as $aId) {
			$result = MT::ACCOUNTMANAGEMENT()->update(array(
				'LOGINS' => implode(":", $aId),
				'PHONE_PASSWORD' => Utils::translit($this->PHONEPWD),
				'NAME' => Utils::translit($this->FIRSTNAME.' '.$this->LASTNAME),
				'EMAIL' => $this->EMAIL,
				'COUNTRY' => Utils::getCountryByCode($this->COUNTRY),
				'PHONE' => $this->PHONE,
				'ZIPCODE' => $this->ZIPCODE,
				'CITY' => Utils::translit($this->LOCALITY),
				'ADDRESS' => Utils::translit($this->ADDRESS),
			))->run();
			if (!$result) {
				return false;
			}
		}
		return true;
	}


	public function selectAll($criteria='')
	{
		$records = array();


		if ($criteria) {
			//$criteria->select = "*, (SELECT CONVERT(GROUP_CONCAT(a.ID) USING cp1251) AS ACCOUNTS FROM ACCOUNT a WHERE a.USER_ID = t.ID) AS ACCOUNTS";
		}

		$results = $this->findAll($criteria);

		//print_r($results); exit;

		$k = 0;
		foreach ($results as $result) {
			$records[] = $result->getAttributes(false);
			//$records[$k]['COUNTRY'] = Utils::getCountryByCode($result->COUNTRY);
			//$records[$k]['ACCOUNTS'] = $result->ACCOUNTS;
			$records[$k]['PASSWORD'] = '';
			$records[$k]['PWD'] = '';
			$k++;
		}
		return $records;
	}

	public function setSearchAllFields($fields)
	{
		$this->_searchAllFields = $fields;
	}

	public function getSearchAllFields()
	{
		return $this->_searchAllFields;
	}

	/**
	 * @return integer|null
	 */
	public function getAffid()
	{
	    if (isset($this->PARTNER) && $this->PARTNER->ACCOUNT) {
	        return $this->PARTNER->ACCOUNT->AGENT_ACCOUNT;
	    }
	    return null;
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::scopes()
	 */
	public function scopes()
	{
        return array(
            'lasts'=>array(
                'order'=>'t.CREATED DESC',
            )
        );
	}

    /**
     * @param $id
     * @return $this
     */
    public function findSimilar($id)
    {
        $finder = new SimilarFinder;
        $finder->findUsers($id);

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "SIMILAR_TO = '$id'",
        ));
        return $this;
    }

    /**
     * @param $id
     * @return $this
     */
    public function checkEvercookie($id)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "EVERCOOKIE = '$id'",
        ));
        return $this;
    }

    public function byEmail($email)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "EMAIL = '$email'",
        ));
        return $this;
    }

	public function getUserByProperty($field, $value)
	{
		$user = $this->find($field . '=:VALUE', array(':VALUE' => $value));
		if (isset($user->ID)) {
			return $user;
		}

		return null;
	}

	/**
	 * @param boolean $flag
	 */
	public function setUpdateMT($flag)
	{
		$this->_updateMT = (boolean) $flag;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getUpdateMT()
	{
		return $this->_updateMT;
	}

    public function depositExists() {
        return Yii::app()->db->createCommand("
            SELECT COUNT(1)
            FROM REQUEST_PAYMENT_TRANSFER rpt, ACCOUNT a
            WHERE rpt.ACCOUNT_ID = a.ID AND a.USER_ID = :USER_ID")
            ->queryScalar(array(
                ':USER_ID' => $this->ID
            ));
    }

	public static function getByEmail($email)
	{
		return self::model()->findByAttributes(array('EMAIL' => $email));
	}

    public static function getById($id)
    {
        return self::model()->findByPk($id);
    }

    /**
     * @param string $password
     * @return boolean
     */
	public function changePassword($password)
	{
	    $tmpVal = $this->getUpdateMT();
		$this->setUpdateMT(false);
		$this->PWD = CPasswordHelper::hashPassword((string) $password);
		$result = $this->save();
		$this->setUpdateMT($tmpVal);
		return $result;
	}

    public function getDepositActiveAccounts()
    {
		$accountModel = $this->_getAccountModel();
		if ($this->DEPOSIT_ONLY_PRO_ACCOUNTS)
			$accountModel->saveAndPro();

		$items = $accountModel->findAllByAttributes(array('USER_ID' => $this->ID));
		usort($items, array(__CLASS__, 'safeInBottom'));

		return $items;
    }

	public function getActiveAccounts()
	{
		$accountModel = $this->_getAccountModel();
		$items = $accountModel->findAllByAttributes(array('USER_ID' => $this->ID));
		usort($items, array(__CLASS__, 'safeInBottom'));
		return $items;
	}

	public function getDepositActiveAccount($accountId)
	{
		$attributes = array(
			'ID' => $accountId,
			'USER_ID' => $this->ID,
		);
		$model = ACCOUNT::model()->with('CURRENCY_TYPE')->together()->active();
		if ($this->DEPOSIT_ONLY_PRO_ACCOUNTS)
			$model->onlyPro();

		return $model->findByAttributes($attributes);
	}

	public function getWithdrawalActiveAccounts()
	{
		$accountModel = $this->_getAccountModel()->with('ACCOUNTSTATUS');

		$items = $accountModel->findAllByAttributes(array('USER_ID' => $this->ID));
		usort($items, array(__CLASS__, 'safeInBottom'));

		return $items;
	}

	protected function safeInTop($item)
	{
		if ($item->ACCOUNT_TYPE_ID == ACCOUNTTYPE::SAFE)
			return 0;
		return 1;
	}

    protected function safeInBottom($item)
    {
        if ($item->ACCOUNT_TYPE_ID == ACCOUNTTYPE::SAFE)
            return 1;
        return 0;
    }

    public function getDepositBonuses()
    {
        $tb = new TFBonus;
        return $tb->getDepositBonuses($this->ID, true);
    }

    private function _getAccountModel()
    {
        return ACCOUNT::model()->with('ACCOUNT_TYPE', 'CURRENCY_TYPE')->together()->active()->lasts();
    }

    public function getDepositBlockOrderVar()
    {
        return $this->getVar('DEPOSIT_PAYMENT_BLOCKS_ORDER');
    }

    public function getVar($varName)
    {
        return USERVARS::model()->findByAttributes(array(
            'USER_ID'   => $this->ID,
            'NAME'      => $varName,
        ));
    }

	public function getDepositCurrencyPrices()
	{
		return Utils::getCurrencyPrices(1);
	}

	public function getWithdrawCurrencyPrices()
	{
		return Utils::getCurrencyPrices(2);
	}

	/**
	 * @param $wallet PAYMENTACCOUNT.
	 * @return bool
	 */
	public function isWalletOwner($wallet)
	{
		if (!($wallet instanceof PAYMENTACCOUNT))
			$wallet = PAYMENTACCOUNT::model()->findByPk($wallet);

		$partner = $this->getSelfPartner();
		if ($wallet->USER_ID == $this->ID || (isset($partner->ID) && $wallet->PARTNER_ID == $partner->ID))
			return true;
		return false;
	}

	/**
	 * @return PAYMENTACCOUNT[]
	 */
	public function getActivePaymentAccounts()
	{
		return $this->getPaymentAccounts(array(
			'HIDDEN' => 0,
		));
	}

	/**
	 * @return PAYMENTACCOUNT
	 */
	public function getActivePaymentAccount($account, $paymentSystemId)
	{
		return $this->getExistedPaymentAccount($account, $paymentSystemId, 1);
	}

	/**
	 * @param null $activeOnly
	 * @return PAYMENTACCOUNT[]
	 */
	public function getPaymentAccounts($attributes = array())
	{
		$condition = 'USER_ID = :USER_ID';
		$params = array(
			':USER_ID' => $this->ID,
		);

		$partner = $this->getSelfPartner();
		if ($partner)
		{
			$condition .= ' OR PARTNER_ID = :PARTNER_ID';
			$params['PARTNER_ID'] = $partner->ID;
		}

//		TODO implement with cache
//		$cacheId = $this->getPaymentAccountCacheId($activeOnly);
//		if (!($items = Yii::app()->cacheCommon->get($cacheId)))
//		{
			$items = PAYMENTACCOUNT::model()->with(array('PAYMENTSYSTEM', 'PAYMENTACCOUNTDATA'
//			=> array(
//				'condition' => 'STATUS IN (:STATUS1, :STATUS2)',
//				'params' => array(
//					':STATUS1' => PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_IN_PROCESS,
//					':STATUS2' => PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_SUCCESS,
//				)
//			)
			))->findAllByAttributes($attributes, $condition, $params);
//			Yii::app()->cacheCommon->set($cacheId, $items, time() + 3600 * 24 *30);
//		}

		return $items;
	}

	/**
	 * @param null $active
	 * @return string
	 */
	public function getPaymentAccountCacheId($active = null)
	{
		$partner = $this->getSelfPartner();
		$partnerId = (isset($partner->ID)) ? $partner->ID : '';

		return PAYMENTACCOUNT::generateCacheId($this->ID, $partnerId, $active);
	}

	/**
	 * @param $account
	 * @param $paymentSystemId
	 * @return PAYMENTACCOUNT
	 */
	public function getExistedPaymentAccount($account, $paymentSystemId, $hidden = null)
	{
		$condition = 'ACCOUNT LIKE :ACCOUNT AND PAYMENT_SYSTEM_ID = :PAYMENT_SYSTEM_ID';
		$params = array(
			':ACCOUNT' => '%' . trim($account) . '%',
			':PAYMENT_SYSTEM_ID' => $paymentSystemId,
		);

		if ($hidden)
			$condition .= ' AND HIDDEN = 1';
		elseif ($hidden === 0)
			$condition .= 'AND HIDDEN = 0';
		$criteria = new CDbCriteria(array(
			'condition' => $condition,
			'params' => $params,
		));

		return $this->getPaymentAccount($criteria);
	}

	/**
	 * @param $accountId
	 * @param $paymentSystemId
	 * @return PAYMENTACCOUNT
	 */
	public function getPsPaymentAccountById($accountId, $paymentSystemId)
	{
		$criteria = new CDbCriteria(array(
			'condition' => 'ID = :ID AND PAYMENT_SYSTEM_ID = :PAYMENT_SYSTEM_ID',
			'params' => array(
				':ID' => $accountId,
				':PAYMENT_SYSTEM_ID' => $paymentSystemId,
			)
		));

		return $this->getPaymentAccount($criteria);
	}

	/**
	 * @param $accountId
	 * @param $paymentSystemId
	 * @return PAYMENTACCOUNT
	 */
	public function getSorexOutAccountByHash($hash)
	{
		$criteria = new CDbCriteria(array(
			'condition' => 'VALUE = :HASH',
			'params' => array(
				':HASH' => $hash,

			)
		));
		$criteria->with = array('PAYMENTACCOUNTDATA');

		return $this->getPaymentAccount($criteria);
	}

	/**
	 * @param $account
	 * @param $paymentSystemId
	 * @return PAYMENTACCOUNT
	 */
	public function getHiddenPaymentAccount($account, $paymentSystemId)
	{
		return $this->getExistedPaymentAccount($account, $paymentSystemId, 1);
	}

	/**
	 * @param CDbCriteria $criteria
	 * @return PAYMENTACCOUNT
	 */
	public function getPaymentAccount($criteria)
	{
		$condition = ' USER_ID = :USER_ID ';
		$params[':USER_ID'] = $this->ID;

		$partner = $this->getSelfPartner();

		if ($partner)
		{
			$condition = '(' . $condition . ' OR PARTNER_ID = :PARTNER_ID)';
			$params[':PARTNER_ID'] = $partner->ID;
		}

		$criteria->addCondition($condition);
		$criteria->params = array_merge($criteria->params, $params);

		return PAYMENTACCOUNT::model()->findByAttributes(array(), $criteria);
	}

	/**
	 * @return bool|PARTNER
	 */
	public function getSelfPartner()
	{
		if ($this->_selfPartner === false)
			$this->_selfPartner = PARTNER::model()->findByAttributes(array('USER_ID' => $this->ID));
		return $this->_selfPartner;
	}

	/**
	 * @return PAYMENTACCOUNT
	 */
	public function createPaymentAccount()
	{
		$paymentAccount = new PAYMENTACCOUNT();
		$paymentAccount->USER_ID = $this->ID;

		return $paymentAccount;
	}

	/**
	 * @return ACCOUNT.
	 */
	public function getAccount()
	{
		return ACCOUNT::model()->findByAttributes(array('USER_ID' => $this->ID));
	}

	/**
	 * @param $netAccount
	 * @return bool
	 */
	public function checkAccountNetwork($netAccount)
	{
		$account = $this->getAccount();
		return $account->checkIfUserBelongsToAccountNetwork($netAccount);
	}

	/**
	 * Creates new safe accounts if needed
	 * @param boolean $force [optional, default=false]
	 * @return integer number of created accounts
	 */
	public function createSafeAccounts($force = false)
	{
	    if ($force || ($this->hasRelated('SAFEACCOUNTS') && !count($this->SAFEACCOUNTS))) {
	        $count = $this->getDbConnection()->createCommand()
	           ->select(array(
                    'SUM(CURRENCY="USD") AS USD',
                    'SUM(CURRENCY="EUR") AS EUR',
                    'SUM(CURRENCY="RUR") AS RUR',
	           ))->from('ACCOUNT')
	           ->where('USER_ID = :USER_ID AND ACCOUNT_TYPE_ID = :TYPE', array(
                   ':USER_ID' => $this->ID,
                   ':TYPE' => ACCOUNTTYPE::SAFE,
	        ))->queryRow();
	        $newAccounts = array();
	        $ar = new AccountRegistration();
	        $ar->setUser($this);
	        $ar->setType(ACCOUNTTYPE::SAFE);
	        if (!$count['USD']) {
                $ar->setCurrency('USD');
                $newAccounts[] = $ar->registerSafe();
	        }
	        if (!$count['EUR']) {
	            $ar->setCurrency('EUR');
	            $newAccounts[] = $ar->registerSafe();
	        }
	        if ('RU' == $this->COUNTRY && !$count['RUR']) {
	            $ar->setCurrency('RUR');
	            $newAccounts[] = $ar->registerSafe();
	        }
            $newAccountsCnt = count($newAccounts);
	        if ($newAccountsCnt) {
	            // send email notification
	            $htmlTable = '<table border="0" cellpadding="5">'
	                . '<tr><th>№</th><th>' . Utils::t('form', 'paymentPwd') . '</th></tr>';
	            foreach ($newAccounts as $item) {
	                /* @var $item ACCOUNT */
                    $htmlTable .= '<tr><td><b>'. $item->ID .'</b></td><td>'. $item->INVESTORPWD .'</td></tr>';
	            }
	            $htmlTable .= '</table>';

	            /* @var $mail Email */
	            $mail = Yii::app()->email;

	            $mail->to = $this->EMAIL;
	            $mail->subject = Utils::t('emails','email_safe_accounts_created_subject');
	            $mail->type = 'multipart/related; boundary="message"';
	            $mail->message = Yii::app()->getController()->renderPartial(Utils::getEmailTemplateView(), array(
	                'content' => Utils::t('emails', 'email_safe_accounts_created_body', array(
	                    '{accounts}' => $htmlTable,
	                )),
	            ), true);

	            if (!$mail->send()) {
	                Yii::log('Failed to send email notification with safe accounts list.', CLogger::LEVEL_WARNING, 'user.createSafeAccounts');
	            }
	        }
	        return $newAccountsCnt;

	    }
	    return 0;
	}

	/**
	 * @param $cardId
	 * @return PAYMENTACCOUNT
	 */
	public function getNewSorexCard($cardId)
	{
		$condition = 'USER_ID = :USER_ID';
		$params = array(':USER_ID' => $this->ID);
		$partner = $this->getSelfPartner();
		if ($partner)
		{
			$condition .= ' OR PARTNER_ID = :PARTNER_ID';
			$params[':PARTNER_ID'] = $partner->ID;
		}
		return PAYMENTACCOUNT::model()->with(array(
			'PAYMENTACCOUNTDATA' => array(
				'condition' => 'VALUE = :VALUE AND STATUS = :STATUS',
				'params' => array(
					':VALUE' => $cardId,
					':STATUS' => PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_NEW,
				)
			)))->together()->findByAttributes(array(), $condition, $params);
	}

	/**
	 * @param $cardId
	 * @return PAYMENTACCOUNT
	 */
	public function getInProcessSorexCard($paymentAccountId)
	{
		return $this->getSorexCard($paymentAccountId, PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_IN_PROCESS);
	}

	/**
	 * @param $cardId
	 * @return PAYMENTACCOUNT
	 */
	public function getSuccessSorexCard($paymentAccountId)
	{
	    return $this->getSorexCard($paymentAccountId, PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_SUCCESS);
	}

	/**
	 * @return PAYMENTACCOUNT[]
	 */
	public function getSuccessSorexCards()
	{
		return $this->getSorexCards(PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_SUCCESS);
	}

	/**
	 * @param $paymentAccountId
	 * @param $status
	 * @return PAYMENTACCOUNT
	 */
	public function getSorexCard($paymentAccountId, $status)
	{
	    $condition = 'USER_ID = :USER_ID';
	    $params = array(':USER_ID' => $this->ID);
	    $partner = $this->getSelfPartner();
	    if ($partner)
	    {
	        $condition .= ' OR PARTNER_ID = :PARTNER_ID';
	        $params[':PARTNER_ID'] = $partner->ID;
	    }
	    return PAYMENTACCOUNT::model()->with(array(
	        'PAYMENTACCOUNTDATA' => array(
	            'condition' => 'PAYMENT_ACCOUNT_ID = :PAYMENT_ACCOUNT_ID AND STATUS = :STATUS',
	            'params' => array(
	                ':PAYMENT_ACCOUNT_ID' => $paymentAccountId,
	                ':STATUS' => $status,
	            )
	        )))->together()->findByAttributes(array(), $condition, $params);
	}

	/**
	 * @param $cardId
	 * @param $status
	 * @return PAYMENTACCOUNT[]
	 */
	public function getSorexCards($status)
	{
	    $condition = 'USER_ID = :USER_ID';
	    $params = array(':USER_ID' => $this->ID);
	    $partner = $this->getSelfPartner();
	    if ($partner)
	    {
	        $condition .= ' OR PARTNER_ID = :PARTNER_ID';
	        $params[':PARTNER_ID'] = $partner->ID;
	    }
	    return PAYMENTACCOUNT::model()->with(array(
	        'PAYMENTACCOUNTDATA' => array(
	            'condition' => 'STATUS = :STATUS',
	            'params' => array(
	                ':STATUS' => $status,
	            )
	        )))->together()->findAllByAttributes(array('PAYMENT_SYSTEM_ID' => PaymentSystemBase::PS_SOREXPAY), $condition, $params);
	}

	/**
	 * @param $account
	 * @param $cardId
	 * @param $currency
	 */
	public function addSorexCardPaymentAccount($account, $cardId, $currency)
	{
	    $model = new PAYMENTACCOUNT(PAYMENTACCOUNT::SCENARIO_ADD_SOREX_ACCOUNT);
	    $model->PAYMENT_SYSTEM_ID = PaymentSystemBase::PS_SOREXPAY;
	    $model->USER_ID = $this->ID;
	    $model->ACCOUNT = $account;

	    $paymentAccountData = new PAYMENTACCOUNTDATA(PAYMENTACCOUNT::SCENARIO_ADD_SOREX_ACCOUNT);
	    $paymentAccountData->setAttributes(array(
	        'VALUE' => $cardId,
	        'CURRENCY' => $currency,
	    ));
	    $model->WALLETACCOUNTDATA = $paymentAccountData;
	    $model->save();
	}

	/**
	 * @param $cardNumber
	 * @param $cardHolder
	 * @param $cardExpire
	 */
	public function addSorexOutCardPaymentAccount($cardNumber, $cardHolder, $cardExpire)
	{
		$model = new PAYMENTACCOUNT(PAYMENTACCOUNT::SCENARIO_ADD_SOREX_OUT_ACCOUNT);
		$model->PAYMENT_SYSTEM_ID = PaymentSystemBase::PS_SOREXPAY_OUT;
		$model->USER_ID = $this->ID;
		$model->ACCOUNT = PAYMENTACCOUNT::hideCardNumber($cardNumber) . ' | ' . $cardExpire;

		$paymentAccountData = new PAYMENTACCOUNTDATA(PAYMENTACCOUNT::SCENARIO_ADD_SOREX_OUT_ACCOUNT);
		$paymentAccountData->setAttributes(array(
			'VALUE' => WithdrawalSorexpayOut::generateHash($cardNumber, $cardHolder, $cardExpire),
			'SECRET' => WithdrawalSorexpayOut::generateSecret($cardNumber, $cardHolder, $cardExpire),
			'STATUS' => 'success',
		));
		$model->WALLETACCOUNTDATA = $paymentAccountData;
		$model->save();
	}

	public function getPaymentAccountsGrouped()
	{
		$paymentAccounts = $this->getActivePaymentAccounts();
		$grouped = array();
		foreach ($paymentAccounts as $paymentAccount)
			$grouped[$paymentAccount->PAYMENT_SYSTEM_ID][$paymentAccount->ID] = $paymentAccount;

		/**
		 * if user have card payment accounts.
		 */
		if (isset($grouped[PaymentSystemBase::PS_SOREXPAY]))
		{
			$verifiedCards = PAYMENTACCOUNTDATA::getVerifiedByIds(array_keys($grouped[PaymentSystemBase::PS_SOREXPAY]));
			$verifiedAccounts = array();
			foreach ($verifiedCards as $verifiedCard)
			{
				$paymentAccount = $grouped[PaymentSystemBase::PS_SOREXPAY][$verifiedCard->PAYMENT_ACCOUNT_ID];
				$verifiedAccounts[$verifiedCard->PAYMENT_ACCOUNT_ID] = $paymentAccount;
			}
			if (count($verifiedAccounts))
				$grouped[PaymentSystemBase::PS_SOREXPAY] = $verifiedAccounts;
			else
				unset($grouped[PaymentSystemBase::PS_SOREXPAY]);
		}

		return $grouped;
	}

	/**
	 * @param $paymentSystemId
	 * @return PAYMENTACCOUNT[]
	 */
	public function getPaymentAccountsByPs($paymentSystemId)
	{
		return $this->getPaymentAccounts(array(
			'PAYMENT_SYSTEM_ID' => $paymentSystemId,
		));
	}

	/**
	 * @param $account ACCOUNT
	 * @return bool
	 */
	public function isBonusAllowed($account)
	{
		if (!$this->DEPOSITBONUS_ALLOWED || $account->LEVERAGE == 1000 || $account->ACCOUNT_TYPE_ID == ACCOUNTTYPE::FLEX_NEWBIE)
			return false;
		return true;
	}

	/**
	 * @return bool
	 */
	public function isWelcomeBonusWorkedOut()
	{
		$accountWelcomeBonus = $this->BONUS_ACCEPTED;
		if (!$accountWelcomeBonus)
			return false;
		/**
		 * @var $accounts ACCOUNT[]
		 */
		$account = ACCOUNT::model()->with(array('ACCOUNTSTATUS', 'CURRENCY_TYPE'))->findByAttributes(array(
			'USER_ID' => $this->ID,
			'ID' => $accountWelcomeBonus,
		));

		if (!$account || !$account->ACCOUNTSTATUS)
			return false;

		$welcomeBonusNeededVolume = 1.5;
		if ($account->ACCOUNTSTATUS->WVOLUME < $welcomeBonusNeededVolume)
			return false;

		return true;
	}

	public function isOwnerOfAccount($account)
	{
		if (!($account instanceof ACCOUNT))
			$account = ACCOUNT::model()->findByPk($account);

		if ($account->USER_ID == $this->ID)
			return true;

		return false;
	}

	/**
	 * @return bool
	 */
	public function isVerified()
	{
		$criteria = new CDbCriteria(array(
			'condition' => 't.ID = :ID',
			'join' => 'INNER JOIN REQUEST_VERIFICATION v1 ON (v1.USER_ID = t.ID AND v1.STATUS_ID = :VSTATUS AND v1.DOCUMENT_TYPE_ID = :DOCTYPE)'
				.'INNER JOIN REQUEST_VERIFICATION v2 ON (v2.USER_ID = t.ID AND v2.STATUS_ID = :VSTATUS AND v2.DOCUMENT_TYPE_ID <> :DOCTYPE)',
			'params' => array(
				':VSTATUS' => REQUESTVERIFICATION::STATUS_VERIFIED,
				':DOCTYPE' => DOCUMENT::TYPE_PASSPORT,
				':ID' => $this->ID,
			),
		));

		if (USER::model()->find($criteria))
			return true;

		return false;
	}

	/**
	 * @return string
	 */
	public function hasDirectDeposit()
	{
		return REQUESTPAYMENTTRANSFER::model()->with(array(
			'ACCOUNT' => array(
				'condition' => 'USER_ID = :USER_ID',
				'params' => array(
					':USER_ID' => $this->ID,
				)
			)
		))->countByAttributes(array(
			'PAYMENT_SYSTEM_ID' => PaymentSystemBase::PS_DEPOSIT_DIRECT,
			'REQUEST_STATUS_ID' => REQUESTSTATUS::STATE_COMPLETED,
		));
	}
}