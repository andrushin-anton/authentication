<?php

/**
 * This is the model class for table "PARTNER".
 *
 * The followings are the available columns in table 'PARTNER':
 * @property integer $ID
 * @property string $PWD
 * @property integer $TYPE
 * @property integer $LEVEL
 * @property integer $PERCENT
 * @property integer $USER_ID
 * @property string $PASSWORD
 * @property string $LAST_ACTIVITY
 * @property string $PHONEPWD
 * @property string $EMAIL
 * @property string $PHONE
 * @property integer $PHONE_VERIFICATED
 * @property integer $PHONE_CALLED
 * @property integer $DEPOSIT_ALLOWED
 * @property integer $CLIENTBONUS_ALLOWED
 * @property integer $WITHDRAW_FROM_CLIENT_ALLOWED
 * @property integer $EXTERNAL_TRANSFERS_ALLOWED
 * @property string $FIRSTNAME
 * @property string $SECONDNAME
 * @property string $LASTNAME
 * @property integer $STATUS
 * @property string $ZIPCODE
 * @property string $COUNTRY
 * @property string $ADDRESS
 * @property string $IP
 * @property string $STATE
 * @property string $LOCALITY
 * @property string $REGION
 * @property integer $CREATED
 * @property integer $CHANGED
 * @property string $PROJECT
 * @property string $PARTNER_STATUS
 * @property string $REFERER
 * @property string $REFERER_HOST
 * @property integer $TRANSFER_WITHOUT_CONFIRM
 * @property integer $APPROVED
 * @property integer $REQUEST_CHANGE_LEVEL_SENDED
 * @property integer $EMAIL_VIEW_ALLOWED
 * @property integer $OLD_PARTNER_SCHEME
 * @property integer $SUBSCRIBE
 * @property string $EVERCOOKIE
 * @property integer $SIMILAR_TO
 * @property string $LANGUAGE
 * @property int $CHILDREN_SAFE_VIEW
 *
 *
 *
 * @property ACCOUNT $ACCOUNT
 * @property USER $USER
 * @property ROLE[] $ROLE
 * @property PRIVILEGE[] $PRIVILEGE
 * @property MTP[] $MTPS
 */
class PARTNER extends TradefortDbActiveRecord
{

    public $AFF_CODES = '';

	public $PROGRAM_IDS = '';

	public $PROGRAM_NAMES = '';

	private $_updateMT = true;

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
		"t.EVERCOOKIE",
		"ACCOUNT.ID",
        "t.LOCALITY",
        "t.REGION",
        "CONCAT(t.FIRSTNAME,' ',t.LASTNAME)",
		"(
			SELECT  GROUP_CONCAT(ACQUIRE_CODE)
		    FROM ACCOUNT_PARTNER_PROGRAM app
		    WHERE ACCOUNT.ID = app.ACCOUNT_ID AND ATTRACTED_ACCOUNT_ID = 0 AND ATTRACTED_PARTNER_ACCOUNT_ID = 0)"
	);

	/**
	 * Returns the static model of the specified AR class.
	 * @return PARTNER the static model class
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
		return 'PARTNER';
	}

	/**
	 * (non-PHPdoc)
	 * @see CModel::rules()
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('FIRSTNAME, LASTNAME, COUNTRY', 'required'),
			array('TYPE, LEVEL, PERCENT, USER_ID, PHONE_VERIFICATED, PHONE_CALLED, DEPOSIT_ALLOWED, CLIENTBONUS_ALLOWED, WITHDRAW_FROM_CLIENT_ALLOWED, EXTERNAL_TRANSFERS_ALLOWED, STATUS, CREATED, CHANGED, TRANSFER_WITHOUT_CONFIRM, APPROVED, REQUEST_CHANGE_LEVEL_SENDED, EMAIL_VIEW_ALLOWED, OLD_PARTNER_SCHEME, SUBSCRIBE, SIMILAR_TO', 'numerical', 'integerOnly'=>true),
			array('PHONEPWD', 'length', 'max'=>16),
			array('PASSWORD, ZIPCODE', 'length', 'max'=>32),
			array('PWD', 'length', 'max'=>128),
			array('PROJECT', 'length', 'max'=>2),
            array('EMAIL, PHONE, FIRSTNAME, SECONDNAME, LASTNAME, ADDRESS, IP, STATE, LOCALITY, REGION, PARTNER_STATUS', 'length', 'max'=>128),
			array('COUNTRY, LANGUAGE', 'length', 'max'=>5),
			array('REFERER', 'length', 'max'=>2048),
			array('REFERER_HOST', 'length', 'max'=>128),
			array('EVERCOOKIE', 'length', 'max'=>255),
			array('ID, PWD, TYPE, LEVEL, PERCENT, USER_ID, PASSWORD, LAST_ACTIVITY, EMAIL, PHONE, PHONE_VERIFICATED, PHONE_CALLED, DEPOSIT_ALLOWED, CLIENTBONUS_ALLOWED, EXTERNAL_TRANSFERS_ALLOWED, FIRSTNAME, SECONDNAME, LASTNAME, STATUS, ZIPCODE, COUNTRY, ADDRESS, IP, STATE, LOCALITY, REGION, CREATED, CHANGED, PROJECT, PARTNER_STATUS, REFERER, REFERER_HOST, TRANSFER_WITHOUT_CONFIRM, APPROVED, SIMILAR_TO, LANGUAGE', 'safe', 'on'=>'search'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::relations()
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'MTPS'=>array(self::MANY_MANY, 'MTP', 'MTP(PARTNER_ID, MTP_ID)'),
			'ACCOUNT'=>array(self::HAS_ONE, 'ACCOUNT', 'PARTNER_ID'),
			'ACCOUNT_WITH_AFF_CODES' => array(self::HAS_ONE, 'ACCOUNT', 'PARTNER_ID', 'with'=>'APP_RELATION_AFF'),
			'PARTNERPROFIT'=>array(self::HAS_MANY, 'PARTNERPROFIT', 'ACQUIRED_PARTNER_ID',
				'condition'=>'PARTNERPROFIT.TICKET_ID = 0',
				'order'=>'PARTNERPROFIT.LEVEL'),
			'PRIVILEGE' => array(self::MANY_MANY, 'PRIVILEGE', 'PRIVILEGE_PARTNER(PRIVILEGE_ID, PARTNER_ID)'),
			'ROLE' => array(self::MANY_MANY, 'ROLE', 'ROLE_PARTNER(ROLE_ID, PARTNER_ID)'),
		    'USER' => array(self::BELONGS_TO, 'USER', 'USER_ID'),
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see CModel::attributeLabels()
	 */
	public function attributeLabels()
	{
		return array(
			'ID' => 'ID',
            'PWD' => 'Password',
			'TYPE' => 'Type',
			'LEVEL' => 'Level',
			'PERCENT' => 'Percent',
			'USER_ID' => 'User',
			'PASSWORD' => 'Password',
			'LAST_ACTIVITY' => 'Last Activity',
			'EMAIL' => 'Email',
			'PHONE' => 'Phone',
			'PHONEPWD' => 'PhonePwd',
			'PHONE_VERIFICATED' => 'Phone Verificated',
			'PHONE_CALLED' => 'Phone Called',
			'DEPOSIT_ALLOWED' => 'Deposit is allowed',
			'CLIENTBONUS_ALLOWED' => 'Client(deposit) bonus is allowd',
			'WITHDRAW_FROM_CLIENT_ALLOWED' => 'Withdraw from client allowed',
			'EXTERNAL_TRANSFERS_ALLOWED' => 'External transfers allowed',
			'FIRSTNAME' => 'Firstname',
			'SECONDNAME' => 'Secondname',
			'LASTNAME' => 'Lastname',
			'STATUS' => 'Status',
			'ZIPCODE' => 'Zipcode',
			'COUNTRY' => 'Country',
			'ADDRESS' => 'Address',
			'IP' => 'Ip',
			'STATE' => 'State',
			'LOCALITY' => 'Locality',
			'REGION' => 'Region',
			'CREATED' => 'Created',
			'CHANGED' => 'Changed',
			'PROJECT' => 'Project',
			'PARTNER_STATUS' => 'Partner status',
			'REFERER' => 'Referer',
			'REFERER_HOST' => 'Referer host',
			'TRANSFER_WITHOUT_CONFIRM' => 'Transfer without confirm',
			'APPROVED' => 'Approved',
			'REQUEST_CHANGE_LEVEL_SENDED' => 'Request change level sended',
			'EMAIL_VIEW_ALLOWED' => 'Email view allowed',
			'OLD_PARTNER_SCHEME' => 'Old partner scheme',
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
		$criteria->compare('TYPE',$this->TYPE);
		$criteria->compare('LEVEL',$this->LEVEL);
		$criteria->compare('PERCENT',$this->PERCENT);
		$criteria->compare('USER_ID',$this->USER_ID);
		$criteria->compare('PASSWORD',$this->PASSWORD,true);
		$criteria->compare('LAST_ACTIVITY',$this->LAST_ACTIVITY,true);
		$criteria->compare('EMAIL',$this->EMAIL,true);
		$criteria->compare('PHONE',$this->PHONE,true);
		$criteria->compare('PHONEPWD',$this->PHONEPWD,true);
		$criteria->compare('PHONE_VERIFICATED',$this->PHONE_VERIFICATED);
		$criteria->compare('PHONE_CALLED',$this->PHONE_CALLED);
		$criteria->compare('DEPOSIT_ALLOWED',$this->DEPOSIT_ALLOWED);
		$criteria->compare('CLIENTBONUS_ALLOWED',$this->CLIENTBONUS_ALLOWED);
		$criteria->compare('WITHDRAW_FROM_CLIENT_ALLOWED',$this->WITHDRAW_FROM_CLIENT_ALLOWED);
		$criteria->compare('EXTERNAL_TRANSFERS_ALLOWED',$this->EXTERNAL_TRANSFERS_ALLOWED);
		$criteria->compare('FIRSTNAME',$this->FIRSTNAME,true);
		$criteria->compare('SECONDNAME',$this->SECONDNAME,true);
		$criteria->compare('LASTNAME',$this->LASTNAME,true);
		$criteria->compare('STATUS',$this->STATUS);
		$criteria->compare('ZIPCODE',$this->ZIPCODE,true);
		$criteria->compare('COUNTRY',$this->COUNTRY,true);
		$criteria->compare('ADDRESS',$this->ADDRESS,true);
		$criteria->compare('IP',$this->IP,true);
		$criteria->compare('STATE',$this->STATE,true);
		$criteria->compare('LOCALITY',$this->LOCALITY,true);
		$criteria->compare('REGION',$this->REGION,true);
		$criteria->compare('CREATED',$this->CREATED);
		$criteria->compare('CHANGED',$this->CHANGED);
		$criteria->compare('PROJECT',$this->PROJECT,true);
		$criteria->compare('PARTNER_STATUS',$this->PARTNER_STATUS,true);
		$criteria->compare('REFERER',$this->REFERER,true);
		$criteria->compare('REFERER_HOST',$this->REFERER_HOST,true);
		$criteria->compare('TRANSFER_WITHOUT_CONFIRM',$this->TRANSFER_WITHOUT_CONFIRM,true);
		$criteria->compare('APPROVED',$this->APPROVED,true);
		$criteria->compare('REQUEST_CHANGE_LEVEL_SENDED',$this->REQUEST_CHANGE_LEVEL_SENDED,true);
		$criteria->compare('EMAIL_VIEW_ALLOWED',$this->EMAIL_VIEW_ALLOWED,true);
		$criteria->compare('OLD_PARTNER_SCHEME',$this->OLD_PARTNER_SCHEME,true);
		$criteria->compare('SUBSCRIBE',$this->SUBSCRIBE, true);
        $criteria->compare('SIMILAR_TO',$this->SIMILAR_TO, true);
        $criteria->compare('LANGUAGE',$this->LANGUAGE, true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function selectAll($criteria='', $locale = NULL)
	{
		$records = array();

		/*
		 *
		 * (
			SELECT GROUP_CONCAT(ACQUIRE_CODE)
		    FROM ACCOUNT a, ACCOUNT_PARTNER_PROGRAM app
		    WHERE t.ID = a.PARTNER_ID AND a.ID = app.ACCOUNT_ID AND ATTRACTED_ACCOUNT_ID = 0 AND ATTRACTED_PARTNER_ACCOUNT_ID = 0)
		 * */

		$criteria->select = "t.*, (
			SELECT GROUP_CONCAT(ACQUIRE_CODE)
		    FROM ACCOUNT
		    INNER JOIN ACCOUNT_PARTNER_PROGRAM ON ACCOUNT.ID = ACCOUNT_PARTNER_PROGRAM.ACCOUNT_ID
		    WHERE t.ID = ACCOUNT.PARTNER_ID
		       	AND ACCOUNT_PARTNER_PROGRAM.ATTRACTED_ACCOUNT_ID = 0
		    	AND ACCOUNT_PARTNER_PROGRAM.ATTRACTED_PARTNER_ACCOUNT_ID = 0) AS AFF_CODES,
		    (
			SELECT CONVERT(GROUP_CONCAT(PARTNER_PROGRAM_ID) USING cp1251)
		    FROM ACCOUNT
		    INNER JOIN ACCOUNT_PARTNER_PROGRAM ON ACCOUNT.ID = ACCOUNT_PARTNER_PROGRAM.ACCOUNT_ID
		    WHERE t.ID = ACCOUNT.PARTNER_ID
		       	AND ACCOUNT_PARTNER_PROGRAM.ATTRACTED_ACCOUNT_ID = 0
		    	AND ACCOUNT_PARTNER_PROGRAM.ATTRACTED_PARTNER_ACCOUNT_ID = 0) AS PROGRAM_IDS,
            (
            SELECT GROUP_CONCAT(PARTNER_PROGRAM.NAME)
		    FROM ACCOUNT
		    INNER JOIN ACCOUNT_PARTNER_PROGRAM ON ACCOUNT.ID = ACCOUNT_PARTNER_PROGRAM.ACCOUNT_ID
            INNER JOIN PARTNER_PROGRAM ON ACCOUNT_PARTNER_PROGRAM.PARTNER_PROGRAM_ID = PARTNER_PROGRAM.ID
		    WHERE t.ID = ACCOUNT.PARTNER_ID
		       	AND ACCOUNT_PARTNER_PROGRAM.ATTRACTED_ACCOUNT_ID = 0
		    	AND ACCOUNT_PARTNER_PROGRAM.ATTRACTED_PARTNER_ACCOUNT_ID = 0) AS PROGRAM_NAMES";

		$results = $this->findAll($criteria);

		$k = 0;
		foreach ($results as $result) {
			$records[$k] = $result->getAttributes();
			$records[$k]['PROGRAM_CODES'] = $result->AFF_CODES;
			$records[$k]['ACCOUNT_ID'] = ($result->ACCOUNT) ? $result->ACCOUNT->ID : 0;
			$records[$k]['PROGRAM_IDS'] = $result->PROGRAM_IDS;
			$records[$k]['PROGRAM_NAMES'] = $result->PROGRAM_NAMES;//implode(", ", $pr);
			//$records[$k]['PROGRAM_CODES'] = '';//$result->ACCOUNT_WITH_AFF_CODES;//$result->ACCOUNT->APP_RELATION_AFF->AFF_CODES;
			$records[$k]['PASSWORD'] = '';
			$records[$k]['PWD'] = '';
			$k++;
		}
		return $records;
	}

	/**
	 * (non-PHPdoc)
	 * @see CActiveRecord::beforeSave()
	 */
	protected function beforeSave()
	{
		if ($this->PROJECT == 'my' || $this->COUNTRY == 'MY') {
			$this->EXTERNAL_TRANSFERS_ALLOWED = 0;
		}
		if (isset($this->ACCOUNT) && $this->getUpdateMT()) {
			return $this->_updateMTAccount();
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
	            // update USER data
	            if (isset($this->USER)) {
	                $this->USER->saveAttributes($this->getAttributes($attrNames));
	            }
	        }
	    }

	    // update account group
	    if (!$this->isNewRecord && $this->isAttributeChanged('PROJECT') && isset($this->ACCOUNT)) {
	        if (null != ($affid = $this->getAffid())) {
	            $this->ACCOUNT->partnerAccount = $affid;
	        }
            $this->ACCOUNT->updateGroupByProject($this->PROJECT);
	    }
	}

	/**
	 * @return boolean
	 */
	private function _updateMTAccount()
	{
		$result = MT::ACCOUNTMANAGEMENT()->update(array(
			'LOGINS' => $this->ACCOUNT->ID,
			'PHONE_PASSWORD' => Utils::translit($this->PHONEPWD),
			'NAME' => Utils::translit($this->FIRSTNAME.' '.$this->LASTNAME),
			'EMAIL' => $this->EMAIL,
			'COUNTRY' => Utils::getCountryByCode($this->COUNTRY),
			'PHONE' => $this->PHONE,
			'ZIPCODE' => $this->ZIPCODE,
			'CITY' => Utils::translit($this->LOCALITY),
			'ADDRESS' => Utils::translit($this->ADDRESS),
		))->run();
		return $result ? true : false;
	}

	/**
	 * @return integer|null
	 */
	public function getAffid()
	{
	    if (isset($this->ACCOUNT)) {
	        return $this->ACCOUNT->AGENT_ACCOUNT;
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
            'online'=>array(
                'condition'=>'TYPE=1',
        		'order' => 'CREATED ASC'
            ),
        );
    }

    /**
     * @param $id
     * @return PARTNER
     */
    public function findSimilar($id)
    {
        $finder = new SimilarFinder;
        $finder->findPartners($id);

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

	public function setSearchAllFields($fields)
	{
		$this->_searchAllFields = $fields;
	}

	public function getSearchAllFields()
	{
		return $this->_searchAllFields;
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

	public static function getByEmail($email)
	{
		return self::model()->findByAttributes(array('EMAIL' => $email));
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

    public static function getById($id)
    {
        return self::model()->findByPk($id);
    }

    public function getDepositBlockOrderVar()
    {
        return $this->getVar('DEPOSIT_PAYMENT_BLOCKS_ORDER');
    }

    /**
     * @param string $varName
     * @return USERVARS
     */
    public function getVar($varName)
    {
        return USERVARS::model()->findByAttributes(array('PARTNER_ID' => $this->ID, 'NAME' => $varName));
    }

	/**
	 * @return ACCOUNT[]
	 */
	public function getDepositActiveAccounts()
	{
		return $this->_getAccountModel()->findAllByAttributes(array('PARTNER_ID' => $this->ID));
	}

	/**
	 * @param $accountId
	 * @return ACCOUNT
	 */
	public function getDepositActiveAccount($accountId)
	{
		return ACCOUNT::model()->with('CURRENCY_TYPE')->together()->active()->findByPk($accountId);
	}

	/**
	 * @return ACCOUNT[]
	 */
	public function getWithdrawalActiveAccounts()
	{
		return $this->_getAccountModel()->findAllByAttributes(array('PARTNER_ID' => $this->ID));
	}

	private function _getAccountModel()
	{
		return ACCOUNT::model()->with('ACCOUNT_TYPE', 'CURRENCY_TYPE')->together()->active()->lasts();
	}

    public function getAffiliateByAccAndAttrAcc($accountId, $attractedAccountId)
    {
        return Yii::app()->db->createCommand()
            ->select("P.ID, P.EMAIL, APP.ACCOUNT_ID, APP.PARTNER_PROGRAM_ID")
            ->from("ACCOUNT_PARTNER_PROGRAM APP")
            ->leftJoin("ACCOUNT A", "A.ID = APP.ACCOUNT_ID")
            ->leftJoin("PARTNER P", "P.ID = A.PARTNER_ID")
            ->where("APP.ACCOUNT_ID = :ACCOUNT_ID AND APP.ATTRACTED_ACCOUNT_ID = :ATTRACTED_ACCOUNT_ID")
            ->queryRow(true, array(
                ':ACCOUNT_ID' => $accountId,
                ':ATTRACTED_ACCOUNT_ID' => $attractedAccountId,
            ));
    }

    /**
     * @param string $acquireCode
     * @return array [ID, EMAIL, ACCOUNT_ID, PARTNER_PROGRAM_ID]
     */
    public function getAffiliateByAcquireCode($acquireCode)
    {
        $sql = 'SELECT p.`ID`, p.`EMAIL`, app.`ACCOUNT_ID`, app.`PARTNER_PROGRAM_ID`
FROM `tradefort`.`ACCOUNT_PARTNER_PROGRAM` app
INNER JOIN `tradefort`.`ACCOUNT` acc ON (acc.`ID` = app.`ACCOUNT_ID`)
INNER JOIN `tradefort`.`PARTNER` p ON (p.`ID` = acc.`PARTNER_ID`)
WHERE app.`ACQUIRE_CODE` = UPPER(:CODE)';
        return ACCOUNT::model()->getDbConnection()->createCommand($sql)->queryRow(true, array(
        	':CODE' => (string) $acquireCode,
        ));
    }

    public function getAffiliateByAttrAccounts($accounts = array())
    {
        return Yii::app()->db->createCommand()
            ->selectDistinct('p.ID, p.EMAIL, app.ACCOUNT_ID, app.PARTNER_PROGRAM_ID')
            ->from('ACCOUNT_PARTNER_PROGRAM app')
            ->leftJoin('ACCOUNT a', 'a.ID = app.ACCOUNT_ID')
            ->leftJoin('PARTNER p', 'p.ID = a.PARTNER_ID')
            ->where(array('in', 'app.ATTRACTED_ACCOUNT_ID', $accounts))
            ->queryAll();
    }

	public function getDepositCurrencyPrices()
	{
		return Utils::getCurrencyPrices(3);
	}

	public function getWithdrawCurrencyPrices()
	{
		return Utils::getCurrencyPrices(4);
	}

	/**
	 * @param $wallet PAYMENTACCOUNT.
	 * @return bool
	 */
	public function isWalletOwner($wallet)
	{
		if (!($wallet instanceof PAYMENTACCOUNT))
			$wallet = PAYMENTACCOUNT::model()->findByPk($wallet);

		if ($wallet->PARTNER_ID == $this->ID || $wallet->USER_ID == $this->USER_ID)
			return true;
		return false;
	}

	/**
	 * @return PAYMENTACCOUNT[]
	 */
	public function getActivePaymentAccounts()
	{
		return $this->getPaymentAccounts(array(
			'HIDDEN' => 0
		));
	}

	/**
	 * @param null $activeOnly
	 * @return PAYMENTACCOUNT[]
	 */
	public function getPaymentAccounts($attributes = array())
	{
		$condition = 'PARTNER_ID = :PARTNER_ID';
		$params = array(
			':PARTNER_ID' => $this->ID,
		);
		if ($this->USER_ID)
		{
			$condition .= ' OR USER_ID = :USER_ID';
			$params[':USER_ID'] = $this->USER_ID;
		}

		$items = PAYMENTACCOUNT::model()->with(array('PAYMENTSYSTEM', 'PAYMENTACCOUNTDATA'))->findAllByAttributes($attributes, $condition, $params);

		return $items;
	}

	/**
	 * @param $account
	 * @param $paymentSystemId
	 * @return PAYMENTACCOUNT
	 */
	public function getExistedPaymentAccount($account, $paymentSystemId, $hidden = null)
	{
		$condition = 'ACCOUNT LIKE :ACCOUNT AND PAYMENT_SYSTEM_ID = :PAYMENT_SYSTEM_ID';
		if ($hidden)
			$condition .= ' AND HIDDEN = 1';
		elseif ($hidden === 0)
			$condition .= 'AND HIDDEN = 0';
		$criteria = new CDbCriteria(array(
			'condition' => $condition,
			'params' => array(
				':ACCOUNT' => '%' . trim($account) . '%',
				':PAYMENT_SYSTEM_ID' => $paymentSystemId,
			)
		));


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
	 * @param CDbCriteria $criteria
	 * @return PAYMENTACCOUNT
	 */
	public function getPaymentAccount($criteria)
	{
		$condition = ' PARTNER_ID = :PARTNER_ID ';
		$params[':PARTNER_ID'] = $this->ID;

		if ($this->USER_ID)
		{
			$condition = '(' . $condition . ' OR USER_ID = :USER_ID)';
			$params[':USER_ID'] = $this->USER_ID;
		}

		$criteria->addCondition($condition);
		$criteria->params = array_merge($criteria->params, $params);

		return PAYMENTACCOUNT::model()->findByAttributes(array(), $criteria);
	}

	/**
	 * @param null $active
	 * @return string
	 */
	public function getPaymentAccountCacheId($active = null)
	{
		return PAYMENTACCOUNT::generateCacheId($this->USER_ID, $this->ID, $active);
	}

	/**
	 * @return PAYMENTACCOUNT
	 */
	public function createPaymentAccount()
	{
		$paymentAccount = new PAYMENTACCOUNT();
		$paymentAccount->PARTNER_ID = $this->ID;

		return $paymentAccount;
	}

	/**
	 * @param USER|PARTNER $user
	 * @return bool
	 */
	public function checkIsChildUser($user)
	{
		$partnerAccount = $this->getAccount();
		return $user->checkAccountNetwork($partnerAccount);
	}

	/**
	 * @return ACCOUNT.
	 */
	public function getAccount()
	{
		return ACCOUNT::model()->findByAttributes(array('PARTNER_ID' => $this->ID));
	}

	/**
	 * @param ACCOUNT $netAccount
	 * @return bool
	 */
	public function checkAccountNetwork($netAccount)
	{
		$account = $this->getAccount();
		return $account->checkIfPartnerBelongsToAccountNetwork($netAccount);
	}

	/**
	 * @param USER $user
	 */
	public function getUserAccount($user)
	{
		$attributes = array('USER_ID' => $user->ID);
		$condition = '';
		$params = array();
		if (!$this->CHILDREN_SAFE_VIEW)
		{
			$condition = 'ACCOUNT_TYPE_ID <> :ACCOUNT_TYPE_ID ';
			$params = array(':ACCOUNT_TYPE_ID' => ACCOUNTTYPE::SAFE);
		}

		$accounts = ACCOUNT::model()->together('ACCOUNT_TYPE')->findAllByAttributes($attributes, $condition, $params);
		if (!$accounts)
			return array();

		return $accounts;
	}

	/**
	 * @param $cardId
	 * @return PAYMENTACCOUNT
	 */
	public function getNewSorexCard($cardId)
	{
		$condition = 'PARTNER_ID = :PARTNER_ID';
		$params = array(':PARTNER_ID' => $this->ID);
		if ($this->USER_ID)
		{
			$condition .= ' OR USER_ID = :USER_ID';
			$params[':USER_ID'] = $this->USER_ID;
		}
		return PAYMENTACCOUNT::model()->with(array(
			'PAYMENTACCOUNTDATA' => array(
				'condition' => 'VALUE = :VALUE AND STATUS = :STATUS',
				'params' => array(
					':VALUE' => $cardId,
					':STATUS' => PAYMENTACCOUNTDATA::STATUS_SOREX_CARD_NEW,
				)
			)))->findByAttributes(array(), $condition, $params);
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
		$condition = 'PARTNER_ID = :PARTNER_ID';
		$params = array(':PARTNER_ID' => $this->ID);
		if ($this->USER_ID)
		{
			$condition .= ' OR USER_ID = :USER_ID';
			$params[':USER_ID'] = $this->USER_ID;
		}
		return PAYMENTACCOUNT::model()->with(array(
			'PAYMENTACCOUNTDATA' => array(
				'condition' => 'PAYMENT_ACCOUNT_ID = :PAYMENT_ACCOUNT_ID AND STATUS = :STATUS',
				'params' => array(
					':PAYMENT_ACCOUNT_ID' => $paymentAccountId,
					':STATUS' => $status,
				)
			)))->findByAttributes(array(), $condition, $params);
	}

	/**
	 * @param $cardId
	 * @param $status
	 * @return PAYMENTACCOUNT[]
	 */
	public function getSorexCards($status)
	{
		$condition = 'PARTNER_ID = :PARTNER_ID';
		$params = array(':PARTNER_ID' => $this->ID);
		if ($this->USER_ID)
		{
			$condition .= ' OR USER_ID = :USER_ID';
			$params[':USER_ID'] = $this->USER_ID;
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
		$model->PARTNER_ID = $this->ID;
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
		$model->PARTNER_ID = $this->ID;
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
	 * @return bool
	 */
	public function step2Saved()
	{
		if ($this->ZIPCODE && /*$this->REGION &&*/ $this->LOCALITY && $this->ADDRESS && $this->PHONEPWD)
			return true;

		return false;
	}

	/**
	 * @return bool
	 */
	public function isNewPartner() {
		if (in_array($this->ID, array(1864, 1044, 929, 1951, 853))) {
			// 923976, 923140, 923025, 924092, 922938
			// fonkong@126.com, taojinke@126.com, abcwaihui@163.com, linjinba@126.com, xo618@sina.com
			return true;
		}

		if ($this->CREATED > strtotime('2012-04-19 12:00:00'))
			return true;

		return false;

//		return Yii::app()->db->createCommand("SELECT if(FROM_UNIXTIME(CREATED) > '2012-04-19 12:00:00', 1, 0) FROM PARTNER WHERE ID = {$partnerId}")->queryScalar();
	}

	/**
	 * @return bool
	 */
	public function isChinaPartner() {
		if ($this->PROJECT == 'cn')
			return true;
		return false;
//		return Yii::app()->db->createCommand("SELECT if(PROJECT = 'cn', 1, 0) FROM PARTNER WHERE ID = {$partnerId}")->queryScalar();
	}

	public function isOldPartnerScheme($account = false) {

		if($this->OLD_PARTNER_SCHEME) {
			return true;
		}

		if(!$account) {
			$account = ACCOUNT::model()->findByAttributes(array('PARTNER_ID' => $this->ID));
		}

		$parents = Partnership::getParents($account->ID);

		foreach($parents as $parent) {
			if($parent['OLD_PARTNER_SCHEME']) {
				return true;
			}
		}

		return false;
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

	public function isOwnerOfAccount($account)
	{
		if (!($account instanceof ACCOUNT))
			$account = ACCOUNT::model()->findByPk($account);

		if ($account->PARTNER_ID == $this->ID)
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
				'condition' => 'PARTNER_ID = :PARTNER_ID',
				'params' => array(
					':PARTNER_ID' => $this->ID,
				)
			)
		))->countByAttributes(array(
				'PAYMENT_SYSTEM_ID' => PaymentSystemBase::PS_DEPOSIT_DIRECT,
				'REQUEST_STATUS_ID' => REQUESTSTATUS::STATE_COMPLETED,
			));
	}
}
