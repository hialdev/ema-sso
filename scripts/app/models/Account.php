<?php

class Account extends BaseModel
{
    const ACCOUNT_ACTIVE        = 1;
    const ACCOUNT_DISABLED      = 0;
    const ACCOUNT_BANNED        = -1;

    const OTP_NONE              = 'none';
    const OTP_EMAIL             = 'email';
    const OTP_TELEGRAM          = 'telegram';
    const OTP_OAUTH             = 'oauth';

    protected $tablename = 'accounts';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    static $statusText = [
        self::ACCOUNT_ACTIVE    => 'Aktif',
        self::ACCOUNT_DISABLED  => 'Non Aktif',
        self::ACCOUNT_BANNED    => 'Di Suspend',
    ];

    static $otpText = [
        self::OTP_NONE          => 'Tidak Menggunakan OTP',
        self::OTP_TELEGRAM      => 'Telegram',
        self::OTP_EMAIL         => 'Email',
        self::OTP_OAUTH         => 'Aplikasi Authenticator',
    ];

    public function initialize()
    {
        parent::initialize();

        $this->hasManyToMany(
            'id',
            AccountRole::class,
            'account_id',
            'role_id',
            Role::class,
            'id',
            [
                'reusable' => true,
                'alias'    => 'Roles',
            ]
        );

        $this->hasMany(
            'id',
            AccountRole::class,
            'account_id',
            [
                'reusable' => true,
                'alias'    => 'AccountRole'
            ]
        );

    }

    public static function findByUsername ($username)
    {
        $parameters = ["(username = '$username' OR email = '$username')"];
        return parent::findFirst($parameters);
    }

    public static function findByEmail ($email)
    {
        $parameters = ["(email = '$email')"];
        return parent::findFirst($parameters);
    }

    public static function findByUID ($uid)
    {
        $parameters = ["(uid = '$uid')"];
        return parent::findFirst($parameters);
    }

    public static function isUsernameAvailable ($username, $excludeId = null)
    {
        $parameters = "username = '$username'";
        if ($excludeId) $parameters .= " AND id != '$excludeId'";

        return parent::findFirst($parameters)?false:true;
    }

    public static function isEmailAvailable ($email, $excludeId = null)
    {
        $parameters = "email = '$email'";
        if ($excludeId) $parameters .= " AND id != '$excludeId'";

        return parent::findFirst($parameters)?false:true;
    }

    public static function isPhoneAvailable ($phone, $excludeId = null)
    {
        $parameters = "phone = '$phone'";
        if ($excludeId) $parameters .= " AND id != '$excludeId'";

        return parent::findFirst($parameters)?false:true;
    }

    public static function isGoogleAccountExist ($google_id)
    {
        $parameters = "google_id = '$google_id'";
        return parent::findFirst($parameters)?false:true;
    }

    public static function findByGoogleId ($google_id)
    {
        $parameters = "google_id = '$google_id'";
        return parent::findFirst($parameters);
    }

    public function hasRole ($role)
    {
        return AccountRole::accountHasRole($this->id, $role);
    }

    public function getAccountRole ($role)
    {
        return AccountRole::getRoleBy($this->id, $role);
    }

    public static function addAccount ()
    {

    }

    public static function getStatistic ()
    {
        return Account::query()
            ->columns([
                "SUM(IF(status=1,1,0)) as user_aktif",
                "COUNT(1) as user_total",
            ])
            ->where("child_account=0")
            ->execute()
            ->getFirst();
    }

    public static function numActive ()
    {
        return Account::count(["status=1"]);
    }

    public static function findByRoleData($roleSlug, $objectId)
	{
        return AccountRole::query()
            ->columns([
                "account.*"
            ])
            ->join("Account", "account.id = AccountRole.account_id", "account", "LEFT")
            ->join("Role", "AccountRole.role_id = role.id", "role", "LEFT")
            ->where("object_id = '$objectId'")
            ->andWhere("role.slug = '$roleSlug'")
            ->execute()
            ->getfirst();
    }

    public function setFamilyAccount ($nama = "")
    {
        if ($family = Family::findByAccountId ($this->id))
        {
            return $family;
        }
        else
        {
            $familyName = $nama ?: $this->name;
            $family = new Family;
            $family->id = Family::generateId();
            $family->slug = Utils::slugify($family->id." ".$familyName);
            $family->name = $familyName;
            $family->account_id = $this->id;
            $family->created = Utils::now();

            if ($family->save())
                return $family;
        }
        return FALSE;
    }

    public function normalizeToArray ()
    {
        return Account::normalize($this);
    }

    public static function normalize ($account)
    {
        if ($account instanceof Account)
            $account = $account->toArray();

        $account['date_joined_txt'] = Utils::formatTanggal($account['date_joined'], true, true, true);
        $account['dob_txt'] = Utils::formatTanggal($account['dob'], true, true, false);
        $account['status_txt'] = Utils::getArrayValue(Account::$statusText, $account['status']);
        $account['gender_txt'] = Utils::genderText($account['gender']);

        return $account;
    }

    public function isOTPvalid ()
    {
        if ($this->use_otp == 1)
        {
            if ($this->otp_media == Account::OTP_EMAIL)
            {
                return !empty($this->email);
            }
            else if ($this->otp_media == Account::OTP_TELEGRAM)
            {
                return !empty($this->telegram_id) || !empty($this->phone);
            }
            else if ($this->otp_media == Account::OTP_OAUTH)
            {
                return true;
            }
        }

        return false;
    }

    public function getOTPInfo ()
    {
        if ($this->otp_media == Account::OTP_EMAIL)
        {
            return "alamat email ".$this->email;
        }
        else if ($this->otp_media == Account::OTP_TELEGRAM)
        {
            return "akun Telegram ".$this->phone;
        }
        else if ($this->otp_media == Account::OTP_OAUTH)
        {
            return "Google Authenticator";
        }

        return '';
    }

    public function isOTPUnverified ()
    {
        if ($this->use_otp == 1)
        {
            if ($this->otp_media == Account::OTP_EMAIL)
                return !empty(AccountContact::getEmail ($this->id));

            else if ($this->otp_media == Account::OTP_TELEGRAM)
                return !empty(AccountContact::getPhone ($this->id));

            else if ($this->otp_media == Account::OTP_OAUTH)
                return true;
        }

        return false;
    }

    public function getSecret ()
    {
        if ($this->secret) return $this->secret;

        $secret = Base32::encode(md5($this->id.$this->uid));
        return str_replace("=","", $secret);
    }

    public function arrayInfo ()
    {
        $infos = ['id', 'uid', 'username', 'name', 'email'];
        $data = [];
        foreach ($infos as $field)
        {
            $data[$field] = $this->$field;
        }

        return $data;
    }

    public function getRoles ($asSlug = false)
    {
        return $asSlug ?
            AccountRole::roleAsArray($this->id) :
            AccountRole::findRoles ($this->id);
    }

    public function setAccess ($applicationid)
    {
        if ($access = Access::findAccess($applicationid, $this->id)){}
        else
        {
            $access = new Access;
            $access->application_id = $applicationid;
            $access->account_id = $this->id;
            $access->created = Utils::now();
            $access->save();
        }
        return $access;
    }

    public function removeAccess ($applicationid)
    {
        if ($access = Access::findAccess($applicationid, $this->id))
        {
            return $access->delete();
        }
        return true;
    }

    public function isAllowedLogin ()
    {
        return $this->status == self::ACCOUNT_ACTIVE;
    }

    public function getAvatarUrl ()
    {
        if (empty($this->config)) $this->config = $this->getDi()->getConfig();
        return $this->config->application->accountUrl.'pic/acc/'.$this->uid;
    }

    public function normalizeInfo ()
    {
        $fields = ['id', 'uid', 'username', 'name', 'email', 'phone', 'dob', 'gender', 'google_id', 'google_account', 'status', 'date_joined'];
        $accountInfo = [];

        foreach ($fields as $field)
        {
            $accountInfo[$field] = $this->$field;
        }

        $accountInfo['avatar'] = $this->getAvatarUrl();
        return $accountInfo;
    }
}
