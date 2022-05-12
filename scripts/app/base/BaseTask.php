<?php

class BaseTask extends \Phalcon\Cli\Task
{
    protected $argument;
    protected $parameters = [];

    protected function parseParameters ($arguments, $keys = [])
    {
        if (empty($keys)) $keys = $this->parameters;

        $this->argument = new stdclass;

        foreach ($keys as $idx => $value)
        {
            $this->argument->$value = isset($arguments[$idx]) ? $arguments[$idx] : null;
        }

        return $this->argument;
    }


    protected function isRunning ($task = null, $argumen = '')
    {
        $_cli = $_SERVER['argv'][0];
        $_task_argument = $task?:$this->router->getTaskName();
        //$_action = $this->router->getActionName();

        if ($argumen != '') $_task_argument .= ' '.$argumen;

        exec(
            sprintf ('ps aux | grep "[p]hp %s %s"', $_cli, $_task_argument),
            $output
        );

        /* Originally the number of process returned should be only 1.
           Since it was executed within the process, instead the process will returned 2 */

        return count($output) > 1;
    }

    private function _generateLog ($message)
    {
        return '['.$this->router->getTaskName().':'.$this->router->getActionName().'] '.$message;
    }

    protected function logDebug ($message)
    {
        $this->log->debug($this->_generateLog($message));
    }

    protected function logError ($message)
    {
        $this->log->error($this->_generateLog($message));
    }

    protected function logInfo ($message)
    {
        $this->log->info($this->_generateLog($message));
    }

    protected function logWarning ($message)
    {
        $this->log->warning($this->_generateLog($message));
    }

    protected function showResult ($message)
    {
        echo $message;
        echo PHP_EOL;
        echo PHP_EOL;
        exit(0);
    }

    protected function showErrorResult ($message)
    {
        $this->showResult("ERROR: ".$message);
    }

    protected function formatTime($sec)
    {
        if($sec > 100){
            $sec /= 60;
            if($sec > 100){
                $sec /= 60;
                return number_format($sec) . " hr";
            }
            return number_format($sec) . " min";
        }
        return number_format($sec) . " sec";
    }

    /**
     * show a status bar in the console
     *
     * <code>
     * for($x=1;$x<=100;$x++){
     *
     *     showProgress($x, 100);
     *     usleep(100000);
     *
     * }
     * </code>
     *
     * @param   int     $progress   how many items are completed
     * @param   int     $total      how many items are to be done total
     * @param   int     $size       optional size of the status bar
     * @return  void
     *
     */
    protected function showProgress ($progress, $total, $size=30, $lineWidth=30, $label = '')
    {
        if($lineWidth <= 0){
            $lineWidth = $_ENV['COLUMNS'];
        }

        static $start_time;

        // to take account for [ and ]
        $size -= 3;
        // if we go over our bound, just ignore it
        if($progress > $total) return;

        if(empty($start_time)) $start_time=time();
        $now = time();

        $perc=(double)($progress/$total);

        $bar=floor($perc*$size);

        // jump to the begining
        echo "\r";
        // jump a line up
        echo "\x1b[A";

        $status_bar="[";
        $status_bar.=str_repeat("=", $bar);
        if($bar<$size){
            $status_bar.=">";
            $status_bar.=str_repeat(" ", $size-$bar);
        } else {
            $status_bar.="=";
        }

        $disp=number_format($perc*100, 0);

        $status_bar.="]";
        $status_bar.= "$disp%  $progress/$total $label";

        $rate = ($now-$start_time)/$progress;
        $left = $total - $progress;
        //$eta = round($rate * $left, 2);

        $elapsed = $now - $start_time;

        //$details .= " " . formatTime($eta)." ". formatTime($elapsed);
        $status_bar.= "  - ". $this->formatTime($elapsed);

        $lineWidth--;
        /*
        if(strlen($details) >= $lineWidth){
            $details = substr($details, 0, $lineWidth-1);
        }*/
        echo "\n$status_bar";

        flush();

        // when done, send a newline
        if($progress == $total) {
            echo "\n";
        }
    }
}