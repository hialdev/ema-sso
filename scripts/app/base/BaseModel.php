<?php

class BaseModel extends \Phalcon\Mvc\Model
{
	protected $tablename;
	protected $connection;
	protected $dbprofile;
	protected $db; // db instance
	protected $config;

	protected $keys = [];

    public function onConstruct()
    {
		$this->db = $this->getDi()->getShared('db');
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
		$this->config = $this->getDi()->getConfig();

    	if ($this->connection)
        	$this->setConnectionService($this->connection);

		if ($this->dbprofile)
		{
			if (isset($this->config->database->db->{$this->dbprofile})){
				$this->setSchema($this->config->database->db->{$this->dbprofile});
			}
		}

        if ($this->tablename)
        	$this->setSource($this->tablename);
    }
	
	protected function assignField ($record, $excludeKeys = false)
	{
        $setSql = '';
        foreach ($record as $field => $value)
        {
			if ($excludeKeys && in_array($field, $this->keys))
				continue;

			if ($setSql) $setSql .= ", ";
			if (is_null($value)) $setSql .=	"`$field`=NULL";
			else {
				$value = trim($value);
				$setSql .= "`$field`=".$this->db->escapeString($value)."";
			}
		}
		return $setSql;
	}

	protected function setConditionByKey ($record)
	{
		$condition = '';
		foreach ($this->keys as $key)
		{
			$value = property_exists($record, $key) ?
			$record->{$key} : FALSE;

			if ($value !== FALSE)
			{
				if ($condition) $condition .= " AND ";
				$condition .=  "`$key`='$value'";
			}
		}

		return $condition?:'0';
	}

	protected function setCondition ($aCondition = [])
	{
		return implode(' AND ', $aCondition);
	}

	public function getAllRecords()
	{
        $result = $this->db->fetchAll("SELECT
                *
            FROM {$this->tablename}",
            Phalcon\Db::FETCH_ASSOC
        );

        if ( !$result ) {
            return false;
        }

        return $result;
    }

	public function getRecordBy ($aCondition, $fetchOne = false, $aOrder = [], $limit = null)
	{
		$condition = $this->setCondition($aCondition);
		$orderSql = $aOrder ? "ORDER BY ".implode (", ", $aOrder) : '';

		if ($fetchOne)
		{
			$result = $this->db->fetchOne(
				"SELECT * FROM {$this->tablename} WHERE $condition $orderSql",
				Phalcon\Db::FETCH_ASSOC
			);
		}
		else
		{
			$limitSql = $limit ? 'LIMIT '.intval($limit) : '';
			$result = $this->db->fetchAll(
				"SELECT * FROM {$this->tablename} WHERE $condition $orderSql $limitSql",
				Phalcon\Db::FETCH_ASSOC
			);
		}

        return $result?:[];
	}

	public function getById ($id)
	{
		return $this->getRecordBy(["id='$id'"], true);
	}

    public static function findById ($id)
    {
        $parameters = ["id = '$id'"];
        return parent::findFirst($parameters);
	}

	public static function getAll()
	{
		return parent::find();
	}

	public function addRecord ($record, $ignoreError = false)
	{
		$setSql = $this->assignField ($record);
		$ignore = $ignoreError?'IGNORE':'';
        $sql = sprintf("INSERT %s INTO %s SET %s", $ignore, $this->tablename, $setSql);

        $this->db->execute($sql);

        if ($this->db->affectedRows() == 0) {
            return false;
        }
        return true;
	}

	public function updateRecord ($record, $excludeKeys = true)
	{
		$setSql = $this->assignField ($record, $excludeKeys);
		$whereSql = $this->setConditionByKey ($record);
		$sql = sprintf("UPDATE %s SET %s WHERE %s", $this->tablename, $setSql, $whereSql);

        return $this->db->execute($sql);
	}

	public function updateRecordBy ($record, $aCondition, $excludeKeys =true)
	{
		$setSql = $this->assignField ($record, $excludeKeys);
		$sql = sprintf("UPDATE %s SET %s WHERE %s", $this->tablename, $setSql, $this->setCondition($aCondition));

        return $this->db->execute($sql);
	}

	public function deleteRecord($record)
	{
		$whereSql = $this->setConditionByKey ($record);
		$sql = sprintf("DELETE FROM %s WHERE %s", $this->tablename, $whereSql);

        $this->db->execute($sql);

        if ($this->db->affectedRows() == 0) {
            return false;
        }
        return true;
    }

	public function deleteRecordBy($aCondition)
	{
		$sql = sprintf("DELETE FROM %s WHERE %s", $this->tablename, $this->setCondition($aCondition));

        $this->db->execute($sql);

        if ($this->db->affectedRows() == 0) {
            return false;
        }
        return true;
    }

	public function deleteRecordById($id)
	{
		return $this->deleteRecordBy(["id='$id'"]);
    }

	public function getInsertId ()
	{
		return $this->db->lastInsertId();
	}

	public static function generateId ()
    {
        $microtime = microtime(true) * 10000;
        return date('ymdh').substr($microtime, -6);
    }

	public function escapeString ($string)
	{
		return $this->db->escapeString($string);
	}

	public function getErrorMessage ()
	{
		return implode(" ", $this->getMessages());
	}

    public function normalizeToArray ()
    {
        return self::normalize($this);
    }

    public static function normalize ($data)
    {
        if (is_object($data))
            $data = $data->toArray();

        return $data;
    }

	static function dbConnect ( $dbConfig, $profile = 'main')
	{
        $class = 'Phalcon\Db\Adapter\Pdo\\' . $dbConfig->adapter;
        $params = [
            'host'     => $dbConfig->host,
            'username' => $dbConfig->username,
            'password' => $dbConfig->password,
            'dbname'   => $dbConfig->db->$profile,
            'charset'  => $dbConfig->charset,
            'options' => array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_SILENT
           )
        ];

        if ($dbConfig->adapter == 'Postgresql') {
            unset($params['charset']);
        }

		try {
            $db = new $class($params);
		}
		catch (PDOException $e) {
			self::fatal(
				"An error occurred while connecting to the database. ".
				"The error reported by the server was: ".$e->getMessage()
			);
		}

		return $db;
	}

	public function toTanggal($data)
    {
        return Utils::formatTanggal($data, true, true, true);
    }

    public static function getAsOptionList ($id = 'id', $name = 'name', $params = [])
    {
        $list = $params ? parent::find($params) : parent::find();
        $data = [];

        if ($list)
        {
            foreach ($list as $record)
            {
                $data[] = [
                    'id'    	=> isset($record->$id) ? $record->$id : null,
					'name'  	=> isset($record->$name) ? $record->$name : null,
					'text'  	=> isset($record->$name) ? $record->$name : null,
					'disable'	=> false,
					'data'		=> $record
                ];
            }
        }

        return $data;
    }
	
}
