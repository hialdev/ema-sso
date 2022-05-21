<?php

class Application extends BaseModel
{
	const STATUS_ACTIVE        = 1;
    const STATUS_DISABLED      = 0;

	const APPLICATION_MOBILE   = "app";
    const APPLICATION_WEB      = "web";
    const APPLICATION_HYBRID   = "app_web";

	protected $tablename = 'applications';
    protected $dbprofile = 'sso';
    protected $keys = ["id"];

    static $statusText = [
        self::STATUS_ACTIVE    => 'Aktif',
        self::STATUS_DISABLED  => 'Tidak Aktif',
    ];

    static $typeText = [
        self::APPLICATION_MOBILE	=> 'Aplikasi Mobile',
        self::APPLICATION_WEB  		=> 'Aplikasi Web',
        self::APPLICATION_HYBRID    => 'Aplikasi Mobile dan Web',
    ];

	public static function findByAppId($appid)
	{
		$parameters = ["appid= '$appid'"];
		return parent::findfirst($parameters);
	}

	public static function findByTag($tag)
	{
		$parameters = ["tag= '$tag'"];
		return parent::findfirst($parameters);
	}

    public static function getAsOption ()
    {
        $list = parent::find();
        $data = [];

        if ($list)
        {
            foreach ($list as $app)
            {
                $data[] = [
                    'id'    => $app->id,
                    'name'  => 'Aplikasi '.$app->name,
                    'icon'  => ApplicationHelper::$typeIcons[$app->type]
                ];
            }
        }

        return $data;
    }

    public static function getTypeAsOption ()
    {
        $data = [];
        foreach (self::$typeText as $type => $text)
        {
            $data[] = [
                'id'    => $type,
                'name'  => $text,
                'icon'  => ApplicationHelper::$typeIcons[$type]
            ];
        }

        return $data;
    }

	public function normalizeToArray ()
    {
        return Application::normalize($this);
    }

    public static function normalize ($application)
    {
        if ($application instanceof Application)
			$application = $application->toArray();

		$application['status_txt'] = Utils::getArrayValue(Application::$statusText, $application['status']);
		$application['type_txt'] = Utils::getArrayValue(Application::$typeText, $application['type']);

        return $application;
    }
}