 <?php
/**
 *
 *
 *
 * @author
 **/

Class DataTable
{
	/**
	 * Database connection
	 *
	 * Obtain an PHP PDO connection from a connection details array
	 *
	 *  @param  array $conn SQL connection details. The array should have
	 *    the following properties
	 *     * host - host name
	 *     * db   - database name
	 *     * user - user name
	 *     * pass - user password
	 *  @return resource PDO connection
	 */
	static function db ( $dbConfig, $profile = 'main')
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

	static function fatal ( $msg )
	{
		echo json_encode( array(
			"error" => $msg
		) );

		exit(0);
    }

    static function setCondition ($field, $value, $useExact)
    {
        if (is_array($field))
        {
            $_query = '';
            foreach ($field as $fieldname)
            {
                if ($_query) $_query .= ' OR ';
                $_query .= $useExact ?
                    "$fieldname = '".$value."'" :
                    "$fieldname like '%".$value."%'";
            }
            $_query = '('.$_query.')';
        }
        else
        {
            $_query = $useExact ?
                "$field = '".$value."'" :
                "$field like '%".$value."%'";
        }

        return $_query;
    }

    static function composeSelect ($aParams)
    {
        $column = isset($aParams['column']) ? implode (', ', $aParams['column']) : '*';
        $from   = isset($aParams['from']) ? $aParams['from'] : '';
        $join   = isset($aParams['join']) ? implode (' ', $aParams['join']) : '';
        $where  = isset($aParams['condition']) ? 'WHERE '.implode (' AND ', $aParams['condition']) : '';

        $order  = isset($aParams['order']) ? 'ORDER BY '.implode(', ', $aParams['order']) : '';
        $group  = isset($aParams['group']) ? 'GROUP BY '.implode(', ', $aParams['group']) : '';
        $having = isset($aParams['having']) ? 'HAVING '.implode(', ', $aParams['having']) : '';

        $limit  = "";
        if (isset($aParams['start'])) $limit = $aParams['start'];
        if (isset($aParams['offset'])) {
            $limit .= ($limit!="") ? ",".$aParams['offset'] : $aParams['offset'];
        }
        if ($limit!="") $limit = 'LIMIT '.$limit;

        return sprintf ("SELECT %s FROM %s %s %s %s %s %s %s", $column, $from, $join, $where, $group, $having, $order, $limit);
    }

    static function getRecord ($db, $aParams, $fetchOne = false)
    {
        $sql = self::composeSelect ($aParams);

        try {
            $result = $fetchOne ?
                $db->fetchOne ($sql) :
                $db->fetchAll($sql);
		}
		catch (PDOException $e) {
            self::fatal(
				"An error occurred while connecting to the database. ".
				"The error reported by the server was: ".$e->getMessage()
			);
			return [];
		}

        return $result;

        /* $stmt = $db->prepare( $sql );

		try {
			$stmt->execute();
		}
		catch (PDOException $e) {
			self::fatal( "An SQL error occurred: ".$e->getMessage() );
		}

		return $fetchOne ? $stmt->fetchOne() : $stmt->fetchAll(); */
    }

    static function getGroupRecordCount($db, $aParams)
    {
        if (isset($aParams['order'])) unset($aParams);
        if (isset($aParams['offset'])) unset($aParams);
        if (isset($aParams['limit'])) unset($aParams);

        $aParams['column'][] = "1";
        $sql = self::composeSelect ($aParams);
        $sql = "SELECT COUNT(1) as Total FROM ($sql) t";

        return $db->fetchOne($sql);

        /* $stmt = $db->prepare( $sql );

		try {
			$stmt->execute();
		}
		catch (PDOException $e) {
			self::fatal( "An SQL error occurred: ".$e->getMessage() );
		}

		return $stmt->fetchOne(); */
    }

    /**
     * Database Query for Datatable plugin purposes
     *
     * @param   String  $model      Model Name. Basically, can be any model, as long it has access to involved tables
     * @param   Array   $aParam     Query Parameters
     * @param   Array   $request  Datatable Post Parameters
     * @return  Object
     **/

    static function getData ($conn, $aParams, $request = [], $dbProfile = 'main', $returnParam = false)
    {
        if (is_array($aParams['from']))
        {
            return self::getDataMulti($conn, $aParams, $request,$dbProfile);
        }

        $db             = self::db( $conn ,$dbProfile);
        if (empty($request)) $request = $_POST;

        $oRequest = is_array($aParams)  ? (object) $aParams : $aParams;
        $aSearchColumn  = isset($oRequest->searchColumn) ? $oRequest->searchColumn : [];
        $aSortColumn    = isset($oRequest->sortColumn) ? $oRequest->sortColumn : [];
        $showFilter     = isset($oRequest->showFilter) ? $oRequest->showFilter : true;

        $aParams = array (
            'from'      => isset($oRequest->from) ? $oRequest->from : null,
            'column'    => isset($oRequest->column) ? $oRequest->column : null,
            'join'      => isset($oRequest->join) ? $oRequest->join : null,
            'condition' => isset($oRequest->condition) ? $oRequest->condition : null,
            'order'     => isset($oRequest->order) ? $oRequest->order : null,
            'group'     => isset($oRequest->group) ? $oRequest->group : null,
            'having'    => isset($oRequest->having) ? $oRequest->having : null,
            'start'     => isset($oRequest->start) ? $oRequest->start : null,
            'offset'    => isset($oRequest->offset) ? $oRequest->offset : null,
        );

        $_condition     = $aParams['condition'] ?: [];
        foreach ($aSearchColumn as $key => $value)
        {
            $exactSearch = false;
            $fieldName = null;
            $fieldValue = null;

            if (is_numeric($key))
            {
                // if key is numeric, means that only contains field name to be search
                // exactSearch is false
                if (isset($request[$value]) && $request[$value]!='')
                {
                    $fieldName = $value;
                    $fieldValue = $request[$value];
                }
            }
            else
            {
                // if key is not numeric, means that we have options
                if (isset($request[$key]) && $request[$key]!='')
                {
                    $fieldValue = $request[$key];

                    // if array, we might have several options,
                    if (is_array($value))
                    {
                        $fieldName = isset($value['field']) ? $value['field'] : $key;
                        if (isset($value['exact']))
                            $exactSearch = $value['exact'] == true;
                    }
                    // otherwise, $value is field name, and exactSearch is false
                    else
                    {
                        $fieldName = $value;
                    }
                }
            }

            if ($fieldName)
            {
                $_strCond = self::setCondition ($fieldName, $fieldValue, $exactSearch);
                array_push($_condition, $_strCond);
            }
        }

        if ($_condition)
        {
            $aParams['condition'] = $_condition;
        }


        if (isset($request['order'][0]['column']))
        {
            $_orderidx = $request['order'][0]['column'];
            $_dir = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'asc';
            $_order = isset($aSortColumn[$_orderidx]) ? $aSortColumn[$_orderidx] : $request['columns'][$_orderidx]['name'];

            if ($_order)
            {
                $aParams['order'][] = $_order.' '.$_dir;
                //$aParams['order'] = $aParams['order'] ? array_push($aParams['order'], $_order.' '.$_dir) : [$_order.' '.$_dir];
            }
        }

        if (isset($request['start']) && isset($request['length']))
        {
            if ($request['length'] != '-1')
            {
                if (is_null($aParams['start'])) $aParams['start'] = $request['start'];
                if (is_null($aParams['offset'])) $aParams['offset'] = $request['length'];
            }
        }


        if (isset($oRequest->filter))
        {
            $aParams['condition'] = (isset($aParams['condition'])) ? array_merge($oRequest->filter, $aParams['condition']) : $oRequest->filter;
        }

        $oriParam   = $aParams;
        $aData      = self::getRecord($db, $aParams);

        $nTotal     = 0;
        $nFiltered  = null;

        if (is_array($aData))
        {
            unset ($aParams['start']);
            unset ($aParams['offset']);

            unset ($aParams['order']);

            if (isset($request['length']) && $request['length'] != '-1')
            {
                if (isset($aParams['condition']) && $showFilter)
                {
                    if (isset($aParams['group']) || isset($aParams['having']))
                    {
                        if ($aResponse = self::getGroupRecordCount($db, $aParams))
                            $nFiltered = $aResponse['Total'];
                    }
                    else
                    {
                        $aParams['column'] = array ('count(1) as TotalFiltered');
                        if ($aResponse = self::getRecord($db, $aParams, true))
                            $nFiltered = $aResponse['TotalFiltered'];
                    }
                    unset ($aParams['condition']);
                }

                if (isset($oRequest->filter) && $showFilter)
                    $aParams['condition'] = $oRequest->filter;

                if (isset($aParams['group']) || isset($aParams['having']))
                {
                    if ($aResponse = self::getGroupRecordCount($db, $aParams))
                        $nTotal = $aResponse['Total'];
                }
                else
                {
                    unset ($aParams['having']);
                    $aParams['column'] = array ('count(1) as Total');
                    if ($aResponse = self::getRecord($db, $aParams, true))
                        $nTotal = $aResponse['Total'];
                }
            }
            else
            {
                $nTotal = count($aData);
            }

            if ($nFiltered === null) $nFiltered = $nTotal;

        }

        return array(
            "draw"            => intval( isset($request['draw']) ? $request['draw'] : 1),
            "recordsTotal"    => intval( $nTotal ),
            "recordsFiltered" => intval( $nFiltered ),
            "data"            => $aData,
            'params'          => $returnParam ? $oriParam : []
        );
    }


    static function getDataMulti ($conn, $aParams, $request = [],$dbProfile = 'main')
    {
        $db             = self::db( $conn,$dbProfile );
        if (empty($request)) $request = $_POST;

        $oRequest = is_array($aParams)  ? (object) $aParams : $aParams;
        $aSearchColumn  = isset($oRequest->searchColumn) ? $oRequest->searchColumn : [];
        $aSortColumn    = isset($oRequest->sortColumn) ? $oRequest->sortColumn : [];

        $aParams = array (
            'from'      => isset($oRequest->from) ? $oRequest->from : null,
            'column'    => isset($oRequest->column) ? $oRequest->column : null,
            'join'      => isset($oRequest->join) ? $oRequest->join : null,
            'condition' => isset($oRequest->condition) ? $oRequest->condition : null,
            'order'     => isset($oRequest->order) ? $oRequest->order : null,
            'group'     => isset($oRequest->group) ? $oRequest->group : null,
            'having'    => isset($oRequest->having) ? $oRequest->having : null,
            //'start'     => isset($oRequest->start) ? $oRequest->start : null,
            //'offset'    => isset($oRequest->offset) ? $oRequest->offset : null,
        );

        $_condition     = $aParams['condition'] ?: [];
        foreach ($aSearchColumn as $key => $value)
        {
            $exactSearch = false;
            $fieldName = null;
            $fieldValue = null;

            if (is_numeric($key))
            {
                // if key is numeric, means that only contains field name to be search
                // exactSearch is false
                if (isset($request[$value]) && $request[$value]!='')
                {
                    $fieldName = $value;
                    $fieldValue = $request[$value];
                }
            }
            else
            {
                // if key is not numeric, means that we have options
                if (isset($request[$key]) && $request[$key]!='')
                {
                    $fieldValue = $request[$key];

                    // if array, we might have several options,
                    if (is_array($value))
                    {
                        $fieldName = isset($value['field']) ? $value['field'] : $key;
                        if (isset($value['exact']))
                            $exactSearch = $value['exact'] == true;
                    }
                    // otherwise, $value is field name, and exactSearch is false
                    else
                    {
                        $fieldName = $value;
                    }
                }
            }

            if ($fieldName)
            {
                $_strCond = self::setCondition ($fieldName, $fieldValue, $exactSearch);
                array_push($_condition, $_strCond);
            }
        }

        if ($_condition)
        {
            $aParams['condition'] = $_condition;
        }


        if (isset($request['order'][0]['column']))
        {
            $_orderidx = $request['order'][0]['column'];
            $_dir = isset($request['order'][0]['dir']) ? $request['order'][0]['dir'] : 'asc';
            $_order = isset($aSortColumn[$_orderidx]) ? $aSortColumn[$_orderidx] : $request['columns'][$_orderidx]['name'];

            if ($_order)
            {
                $aParams['order'][] = $_order.' '.$_dir;
                //$aParams['order'] = $aParams['order'] ? array_push($aParams['order'], $_order.' '.$_dir) : [$_order.' '.$_dir];
            }
        }

        if (isset($oRequest->filter))
        {
            $aParams['condition'] = (isset($aParams['condition'])) ? array_merge($oRequest->filter, $aParams['condition']) : $oRequest->filter;
        }

        $aData = [];
        foreach ($oRequest->from as $from)
        {
            $aParams['from'] = $from;
            if ($_aData = self::getRecord($db, $aParams))
            {
                $aData = array_merge($aData, $_aData);
            }

        }

        $nTotal     = 0;
        $nFiltered  = 0;

        if (is_array($aData))
        {
            $_offset    = $oRequest->start;
            $_length    = $oRequest->offset;

            $nTotal = count($aData);
            $aData  = array_slice($aData, $_offset, $_length);
            $nFiltered = $nTotal;
        }

        return array(
            "draw"            => intval( $request['draw'] ),
            "recordsTotal"    => intval( $nTotal ),
            "recordsFiltered" => intval( $nFiltered ),
            "data"            => $aData
        );
    }

    static function getDataAsResponse ($conn, $aParams, $request = [])
    {
        $result = self::getData ($conn, $aParams, $request);
        echo json_encode($result);
    }

}
