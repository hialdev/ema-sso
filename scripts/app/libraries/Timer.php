<?php
/**
 *
 *
 *
 * @author
 **/


Class Timer
{
    private static $startTimes = array();
    private static $times = array(
                              'hour' => 3600000,
                              'minute' => 60000,
                              'second' => 1000,
                            );
    public static $requestTime;

    public static function start()
    {
        array_push(self::$startTimes, microtime(TRUE));
    }

    public static function stop()
    {
        return microtime(TRUE) - array_pop(self::$startTimes);
    }

    public static function elapsedTime ($short = true, $decimal = 3)
    {
        $_elapsed = self::stop();
        return ($short) ? self::microtimeFormat($_elapsed, $decimal) : self::secondsToTimeString($_elapsed);
    }

    public static function secondsToTimeString($time)
    {
        $ms = round($time * 1000);
        foreach (self::$times as $unit => $value) {
            if ($ms >= $value) {
                $time = floor($ms / $value * 100.0) / 100.0;
                return $time . ' ' . ($time == 1 ? $unit : $unit . 's');
            }
        }
        return $ms . ' ms';
    }

    public static function microtimeFormat ($time, $decimal = 3)
    {
        return sprintf ("%.{$decimal}f", $time);
    }

    public static function timeSinceStartOfRequest()
    {
        return self::secondsToTimeString(microtime(TRUE) - self::$requestTime);
    }

    public static function resourceUsage()
    {
        return sprintf('Time: %s, Memory: %4.2fMb', self::timeSinceStartOfRequest(), memory_get_peak_usage(TRUE) / 1048576);
    }

}
