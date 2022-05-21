<?php

class SMSHelper extends  Phalcon\Di\Injectable
{
    const OP_TELKOMSEL  = 'tsel';
    const OP_XL         = 'xl';
    const OP_INDOSAT    = 'isat';
    const OP_SMARTFREN  = 'smartfren';
    const OP_THREE      = '3';
    const OP_AXIS       = 'axis';
    const OP_CERIA      = 'ceria';

    static $prefixes = [
        '0811'  => SMSHelper::OP_TELKOMSEL,
        '0812'  => SMSHelper::OP_TELKOMSEL,
        '0813'  => SMSHelper::OP_TELKOMSEL,
        '0851'  => SMSHelper::OP_TELKOMSEL,
        '0852'  => SMSHelper::OP_TELKOMSEL,
        '0853'  => SMSHelper::OP_TELKOMSEL,
        '0821'  => SMSHelper::OP_TELKOMSEL,
        '0822'  => SMSHelper::OP_TELKOMSEL,
        '0823'  => SMSHelper::OP_TELKOMSEL,

        '0814'  => SMSHelper::OP_INDOSAT,
        '0815'  => SMSHelper::OP_INDOSAT,
        '0816'  => SMSHelper::OP_INDOSAT,
        '0855'  => SMSHelper::OP_INDOSAT,
        '0856'  => SMSHelper::OP_INDOSAT,
        '0857'  => SMSHelper::OP_INDOSAT,
        '0858'  => SMSHelper::OP_INDOSAT,

        '0817'  => SMSHelper::OP_XL,
        '0818'  => SMSHelper::OP_XL,
        '0819'  => SMSHelper::OP_XL,
        '0859'  => SMSHelper::OP_XL,
        '0877'  => SMSHelper::OP_XL,
        '0878'  => SMSHelper::OP_XL,

        '0831'  => SMSHelper::OP_AXIS,
        '0832'  => SMSHelper::OP_AXIS,
        '0833'  => SMSHelper::OP_AXIS,
        '0838'  => SMSHelper::OP_AXIS,

        '0895'  => SMSHelper::OP_THREE,
        '0896'  => SMSHelper::OP_THREE,
        '0897'  => SMSHelper::OP_THREE,
        '0898'  => SMSHelper::OP_THREE,
        '0899'  => SMSHelper::OP_THREE,

        '0881'  => SMSHelper::OP_SMARTFREN,
        '0882'  => SMSHelper::OP_SMARTFREN,
        '0883'  => SMSHelper::OP_SMARTFREN,
        '0884'  => SMSHelper::OP_SMARTFREN,
        '0885'  => SMSHelper::OP_SMARTFREN,
        '0886'  => SMSHelper::OP_SMARTFREN,
        '0887'  => SMSHelper::OP_SMARTFREN,
        '0888'  => SMSHelper::OP_SMARTFREN,
        '0889'  => SMSHelper::OP_SMARTFREN,

        '0827'  => SMSHelper::OP_CERIA,
        '0828'  => SMSHelper::OP_CERIA,
    ];

    static $operators = [
        self::OP_TELKOMSEL  => 'Telkomsel',
        self::OP_XL         => 'XL',
        self::OP_INDOSAT    => 'Indosat',
        self::OP_SMARTFREN  => 'Smartfren',
        self::OP_THREE      => 'Three',
        self::OP_AXIS       => 'Axis',
        self::OP_CERIA      => 'Ceria'
    ];

    public static function normalizeMsisdn ($nomor, $countryCode = '62', $prefix = ['8'])
    {
        $nomor = trim($nomor);
        if ($nomor == '') return $nomor;

        $len = strlen($countryCode);

        if ($nomor{0} == '+') $nomor = substr($nomor,1);

        if ($nomor{0} == '0') $nomor = $countryCode.substr($nomor,1);
        else if (in_array($nomor{0}, $prefix)) $nomor = $countryCode.$nomor;
        else if (substr($nomor,0,$len) != $countryCode) {
            $pref  = $prefix ? array_shift($prefix): '';
            $nomor = $countryCode.$pref.$nomor;
        }

        return $nomor;
    }

    public static function parseMsisdn ($stringRecipients)
    {
        $recipients = explode(",", $stringRecipients);
        $msisdn = [];

        if ($recipients)
        {
            foreach ($recipients as $nomor){
                $nomor = trim($nomor);
                $len = strlen($nomor);
                if ($nomor && $len>= 10 && $len<=15)
                {
                    $msisdn[] = [ 'msisdn' => self::normalizeMsisdn ($nomor) ];
                }
            }
        }

        return $msisdn;
    }


    public static function getOperator ($msisdn)
    {
        // 62812
        $prefix = '0'.substr(self::normalizeMsisdn($msisdn), 2,3);

        if (isset(self::$prefixes[$prefix]))
            return self::$prefixes[$prefix];

        return "";
    }
}