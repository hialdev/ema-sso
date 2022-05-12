<?php

class Utils extends  Phalcon\Di\Injectable
{
    static $bulan = [
        "Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September", "Oktober", "Nopember", "Desember"
    ];

    static $hari = [
        'Ahad','Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", "Sabtu"
    ];

    public static function today ()
    {
        return date('Y-m-d');
    }

    public static function now ()
    {
        return date('Y-m-d H:i:s');
    }

    public static function formatTanggal ($timestamp, $short = true, $dateOnly = false, $showHour = false, $showYear = true)
    {
        if (empty($timestamp) || $timestamp == '0000-00-00')
            return '-';

        if (!is_numeric($timestamp))
            $timestamp = strtotime($timestamp);

		//$bulan = array ("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September", "Oktober", "Nopember", "Desember");
		//$hari = ['Ahad','Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", "Sabtu"];

        $mon = date("n", $timestamp) -1 ;
        $day = date("d", $timestamp);
		$year= date("Y", $timestamp);
		$hr= date("w", $timestamp);

        $namabulan = self::$bulan[$mon];
		if ($short) $namabulan = substr($namabulan,0, 3);

        $tanggal = $day.' '.$namabulan;

        if ($showYear) $tanggal .= ' '.$year;

        if (!$dateOnly) $tanggal = self::$hari[$hr].", ".$tanggal;

        if ($showHour)
            $tanggal .= ' '.date('H:i', $timestamp);

        return $tanggal;
    }

    public static function formatBulan ($timestamp, $short = true)
    {
        if (empty($timestamp) || $timestamp == '0000-00-00')
            return '-';

        if (!is_numeric($timestamp))
            $timestamp = strtotime($timestamp);

        $mon = date("n", $timestamp) -1 ;
		$year= date("Y", $timestamp);

        $namabulan = self::$bulan[$mon];
        if ($short) $namabulan = substr($namabulan,0, 3);

        return $namabulan.' '.$year;
    }

    public static function formatTime ($timestamp)
    {
        if (empty($timestamp))
            return '';

        if (!is_numeric($timestamp))
            $timestamp = strtotime($timestamp);

        return date('H:i', $timestamp);
    }

    public static function formatDifference ( $time1, $time2, $precision = 2 )
     {
        // If not numeric then convert timestamps
        if( !is_int( $time1 ) ) {
            $time1 = strtotime( $time1 );
        }
        if( !is_int( $time2 ) ) {
            $time2 = strtotime( $time2 );
        }
        // If time1 > time2 then swap the 2 values
        if( $time1 > $time2 ) {
            list( $time1, $time2 ) = array( $time2, $time1 );
        }
        // Set up intervals and diffs arrays
        $intervals = array( 'year', 'month', 'day', 'hour', 'minute', 'second' );
        $intervalNames = array( 'thn', 'bln', 'hari', 'jam', 'menit', 'detik' );
        $diffs = array();
        foreach( $intervals as $idx => $interval ) {
            // Create temp time from time1 and interval
            $ttime = strtotime( '+1 ' . $interval, $time1 );
            // Set initial values
            $add = 1;
            $looped = 0;
            // Loop until temp time is smaller than time2
            while ( $time2 >= $ttime ) {
                // Create new temp time from time1 and interval
                $add++;
                $ttime = strtotime( "+" . $add . " " . $interval, $time1 );
                $looped++;
            }
            $time1 = strtotime( "+" . $looped . " " . $interval, $time1 );
            $diffs[ $intervalNames[$idx] ] = $looped;
        }
        $count = 0;
        $times = array();
        foreach( $diffs as $interval => $value ) {
            // Break if we have needed precission
            if( $count >= $precision ) {
                break;
            }
            // Add value and interval if value is bigger than 0
            if( $value > 0 ) {
                if( $value != 1 ){
                    //$interval .= "s";
                }
                // Add value and interval to times array
                $times[] = $value . " " . $interval;
                $count++;
            }
        }
        // Return string with times
        return implode( ", ", $times );
    }

    public static function slugify ($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);

        if (empty($text)) {
	        return 'n-a';
        }

        return $text;
    }

	static function getArrayValue ($arrayList, $arrayKey, $default = "")
	{
		return isset($arrayList[$arrayKey]) ?
			$arrayList[$arrayKey] : $default;
	}

	static function getOptionList ($arrayList)
	{
        $options = [];
        foreach ($arrayList as $key => $val)
        {
            $options[] = [
                'id'    => $key,
                'name'  => $val
            ];
        }
		return $options;
	}

	static function getListOrValue ($list, $as_option)
	{
		if ($as_option)
		{
			return self::getOptionList ($list);
		}
		else
		{
			return $list;
		}
    }

    static function objectToArray ($object, $arrayField = [])
    {
        $array = [];
        foreach ($arrayField as $field)
        {
            if (isset($object->$field)) $array[$field] = $object->$field;
            else $array[$field] = '';
        }
        return $array;
    }

    static function array2object($array)
    {
        $obj = new stdClass;
        foreach($array as $k => $v)
        {
            if(strlen($k))
            {
                if(is_array($v))
                {
                    $obj->{$k} = self::array2object($v); //RECURSION
                }
                else
                {
                    $obj->{$k} = $v;
                }
            }
        }
        return $obj;
    }

    static function genderText ($gender)
    {
        return self::getArrayValue ([
            'L' => 'Laki-laki',
            'P' => 'Perempuan'
        ], $gender);
    }

    static function randomString ($length = 6)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function randomNumber ($length = 6)
    {
        $numbers = '0123456789';
        $characters = str_shuffle($numbers).str_shuffle($numbers);
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    static function asCurrency ($number, $decimal = 0, $cur_sign = 'Rp')
    {
        $number = (float) $number;
        $value = ($cur_sign) ? $cur_sign.' ' : '';
        return $value.number_format($number, $decimal, ",", ".");
    }

    static function asNumber ($number, $decimal = 0)
    {
        $number = (float) $number;
        return number_format($number, $decimal, ",", ".");
    }

    static function dayToHari ($day)
    {
        $hari = [
            'sunday'    => 'Ahad',
            'monday'    => 'Senin',
            'tuesday'   => 'Selasa',
            'wednesday' => 'Rabu',
            'thursday'  => 'Kamis',
            'friday'    => "Jum'at",
            'saturday'  => "Sabtu"];

        return self::getArrayValue ($hari, $day);
    }

    static function dayToNum ($day)
    {
        $hari = [
            'sunday'    => '0',
            'monday'    => '1',
            'tuesday'   => '2',
            'wednesday' => '3',
            'thursday'  => '4',
            'friday'    => "5",
            'saturday'  => "6"];

        return self::getArrayValue ($hari, $day);
    }


    static function  namaBulan ($short = false, $timestamp = null)
    {
        if (empty($timestamp)) $timestamp = time();

        if (!is_numeric($timestamp))
            $timestamp = strtotime($timestamp);

        $mon = date("n", $timestamp) -1 ;
        $namaBulan = self::$bulan[$mon];
        if ($short)
            $namaBulan = substr($namaBulan,0, 3);

        return $namaBulan;
    }

    static function uuid()
    {
        $data = random_bytes(16);

        assert(strlen($data) == 16);

        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    static function time_elapsed($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'tahun',
            'm' => 'bulan',
            'w' => 'minggu',
            'd' => 'hari',
            'h' => 'jam',
            'i' => 'menit',
            's' => 'detik',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v ; //. ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' lalu' : 'baru saja';
    }

    static function bulan ($bulan)
    {
        $bulan = (int) $bulan - 1;
        return self::getArrayValue(self::$bulan, $bulan);
    }

    static function tahunAsList ($from = null, $to = null)
    {
        if (!is_numeric($from) || strlen($from) != 4)
            $from = date('Y', strtotime("-5 years"));

        if (!is_numeric($to) || strlen($to) != 4)
            $to = date('Y', strtotime("+5 years"));


        $list = [];
        for ($tahun=$from; $tahun<=$to; $tahun++)
        {
            $list[$tahun] = $tahun;
        }

        return self::getOptionList($list);
    }


    static function bulanAsList ()
    {
        $list = [];
        foreach (self::$bulan as $index => $bulan)
        {
            $list[$index+1] = $bulan;
        }

        return self::getOptionList($list);
    }

    static function cleanUrl ($url)
    {
        $url = trim($url);
        if (substr($url,-1) == '/') $url = substr($url, 0, -1);

        return $url;
    }

    static function normalizeMsisdn ($nomor, $countryCode = '62', $prefix = ['8'])
    {
        $nomor = trim($nomor);
        if ($nomor == '') return $nomor;

        $len = strlen($countryCode);

        if ($nomor{0} == '+') $nomor = substr($nomor,1);

        if ($nomor{0} == '0') $nomor = $countryCode.substr($nomor,1);
        else if (in_array($nomor{0}, $prefix)) $nomor = $countryCode.$nomor;
        else if (!empty($countryCode) && substr($nomor,0,$len) != $countryCode) {
            $pref  = $prefix ? array_shift($prefix): '';
            $nomor = $countryCode.$pref.$nomor;
        }

        return $nomor;
    }

    static function readableBytes($bytes) {
        $i = floor(log($bytes, 1024));
        return round($bytes / pow(1024, $i), [0,0,2,2,3][$i]).['B','kB','MB','GB','TB'][$i];
    }

    static function secondsToWords($seconds) {
        /*** number of days ***/
        $days = (int)($seconds / 86400);
        /*** if more than one day ***/
        /*** number of hours ***/
        $hours = (int)(($seconds - ($days * 86400)) / 3600);
        /*** number of mins ***/
        $mins = (int)(($seconds - $days * 86400 - $hours * 3600) / 60);
        /*** number of seconds ***/
        $secs = (int)($seconds - ($days * 86400) - ($hours * 3600) - ($mins * 60));
        /*** return the string ***/
        return sprintf("%d hari, %d jam, %d menit, %d detik", $days, $hours, $mins, $secs);
    }

    static function secondsToHour($seconds) {
        $hours = (int)($seconds / 3600);
        $mins = (int)(($seconds - $hours * 3600) / 60);

        return sprintf("%dh %dm", $hours, $mins);
    }

    static function jamAsList ()
    {
        $list = [];
        foreach (range("00","23") as $jam)
        {
            foreach (["00", "15", "30", "45"] as $menit)
            {
                $time = sprintf("%02d:%s", $jam, $menit);
                $list[$time] = $time;
            }
        }

        return self::getOptionList($list);
    }

	public static function timeid ()
    {
        $microtime = microtime(true) * 10000;
        return date('ymdh').substr($microtime, -6);
    }

    static function extractArray ($array, $keys = [])
    {
        $result = [];
        foreach ($keys as $key)
        {
            $result[$key] = isset($array[$key]) ? $array[$key] : null;
        }

        return $result;
    }

    static function hourAsList($interval = 60)
    {
        $start = new \DateTime('00:00');
        $times = 24 * (60/$interval); // 24 hours * 30 mins in an hour

        $list["00:00"] = "00:00";
        for ($i = 0; $i < $times-1; $i++) {
            $waktu = $start->add(new \DateInterval('PT'.$interval.'M'))->format('H:i');
            $list[$waktu] = $waktu;
        }

        return self::getOptionList($list);
    }

    static function inArrayKey ($array, $search)
    {
        return array_key_exists($search, $array);
    }

    static function tokenTruncate($string, $your_desired_width)
    {
        if (strlen($string) > $your_desired_width)
        {
            $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
            $parts_count = count($parts);

            $length = 0;
            $last_part = 0;
            for (; $last_part < $parts_count; ++$last_part) {
                $length += strlen($parts[$last_part]);
                if ($length > $your_desired_width) { break; }
            }

            return implode(array_slice($parts, 0, $last_part))." ...";
        }
        return $string;
    }

    static function toDatabaseDate ($string)
    {
        if (empty($string)) return '';
        return substr($string, 6, 4).'-'.substr($string, 3, 2).'-'.substr($string, 0, 2);
    }

    static function toLocalDate ($string)
    {
        if (empty($string)) return '';
        return substr($string, 8, 2).'/'.substr($string, 5, 2).'/'.substr($string, 0, 4);
    }

    static function excelColumnRange($lower, $upper) {
        ++$upper;
        for ($i = $lower; $i !== $upper; ++$i) {
            yield $i;
        }
    }

    static function normalizeUri ($url, $ref = "")
    {
        if ($ref && substr($url,-1) == "/")
        {
            if (substr($ref,0,1) == "/") $ref = substr($ref,1);
        }

        return $url.$ref;
    }

    static function is_html($text)
    {
        $processed = htmlentities($text);
        if($processed == $text) return false;
        return true;
    }

    static function asset_path($filename = null, $manifestFile = 'rev-manifest.json')
    {
        $manifest_path = BASE_PATH.'/'.$manifestFile;

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), TRUE);
        } else {
            $manifest = [];
        }

        if ($filename)
        {
            if (array_key_exists($filename, $manifest)) {
                return $manifest[$filename];
            }

            return $filename;
        }
        else
        {
            return $manifest;
        }
    }

    static function greeting ($timestamp = null, $language = "id")
    {
        if (empty($timestamp)) $timestamp = time();
        $hours = date("G", $timestamp);

        if (!in_array($language, ['id','en'])) $language = 'id';

        $greetings = [
            'id'    => ['Selamat Pagi', 'Selamat Siang', 'Selamat Sore', 'Selamat Malam'],
            'en'    => ['Good Morning', 'Good Afternoon', 'Good Evening', 'Good Night']
        ];

        $greetId = 3;

        if ($hours >= 0 && $hours <= 12)
        {
            $greetId = 0;
        }
        else
        {
            if ($hours > 12 && $hours <= 15)
            {
                $greetId = 1;
            }
            else
            {
                if ($hours > 15 && $hours <= 19)
                {
                    $greetId = 2;
                }
            }
        }

        return $greetings[$language][$greetId];
    }
}