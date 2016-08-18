<?php

namespace Temple\Engine\Console;


/**
 * $progress = new CliProgress();
 * $progress->addTask(30);
 * when task is done
 * $progress->removeTask(1);
 * $progress->update();
 * Class CliProgress
 */
class CliProgress
{

    /** @var  callable $finishCallback */
    private $finishCallback;

    /** @var  number $tasks */
    private $tasks;

    /** @var  number $tasks */
    private $doneTasks = 1;

    /** @var  int $columnWidth */
    private $columnWidth;

    /** @var  int $startTime */
    private $startTime;

    /** @var  int $now */
    private $now;

    /** @var bool $stopped */
    private $stopped;


    /**
     * CliProgress constructor.
     */
    public function __construct()
    {
        $this->startTime   = time();
        $this->now         = $this->startTime;
        $this->columnWidth = exec("tput cols");
        $this->CliOutput   = new CliOutput(new CliColors());
    }


//    /**
//     * destroys the function
//     */
//    function __destruct()
//    {
//        $this->update();
//    }


    /**
     * add tasks to the progress bar
     *
     * @param int $tasks
     */
    public function addTask($tasks = 0)
    {
        $this->tasks += $tasks;
    }


    /**
     * remove tasks to the progress bar
     *
     * @param int $tasks
     */
    public function removeTask($tasks = 0)
    {
        $this->doneTasks += $tasks;
    }


    /**
     * updates the current progress
     */
    public function start()
    {
        echo "\n";
        $this->update();
    }


    /**
     * stops the progress bars
     */
    public function stop()
    {
        if (!$this->stopped) {
            $this->stopped = true;

            $this->update(100);
            $elapsed = $this->now - $this->startTime;
            $this->CliOutput->writeln("done in " . $this->formatTime($elapsed), "green");
            $this->CliOutput->outputBuffer();
            $this->finish();
            echo "\n";
        }
    }


    /**
     * updates the current progress
     *
     * @param null $percent
     */
    public function update($percent = null)
    {

        if ($this->stopped) {
            return;
        }

        $this->now = time();


        if ($this->doneTasks > $this->tasks) {
            $this->stop();

            return;
        }

        $this->draw($percent);
    }


    /**
     * draws the console progress bar
     *
     * @param $percent
     */
    public function draw($percent = null)
    {
        // jump to the beginning and a line up
        echo "\r;\e[A";
        if (is_null($percent)) {
            $percent = (double) ($this->doneTasks / $this->tasks);
        }

        echo $this->updateStatusInformation($percent);
        echo "\n";
        echo $this->updateStatusBar($percent);

        if ($this->doneTasks == $this->tasks) {
            echo "\n";
        }
        flush();
    }


    /**
     * displays done tasks percentage and time
     *
     * @param $percent
     *
     * @return string
     */
    private function updateStatusInformation($percent)
    {
        $now        = time();
        $percentage = number_format($percent * 100, 0);

        // calculate the established time
        $rate        = ($now - $this->startTime) / $this->doneTasks;
        $left        = $this->tasks - $this->doneTasks;
        $established = round($rate * $left, 2);

        $information = $this->doneTasks . "/" . $this->tasks . " | " . $percentage . "%";
        if ($established > 0) {
            $information .= " | established time: " . $this->formatTime($established);
        }

        $information .= str_repeat(" ", ($this->columnWidth - strlen($information) - 10));

        return $information;
    }


    private function updateStatusBar($percent)
    {
        // to take account for [ and ]
        $size = $this->columnWidth - 3;
        $bar  = floor($size * $percent);

        $statusBar = "[";
        $statusBar .= str_repeat(":", $bar);
        if ($bar < $size) {
            $statusBar .= ">";
            $statusBar .= str_repeat(" ", $size - $bar);
        } else {
            $statusBar .= ":";
        }
        $statusBar .= "]";

        return $statusBar;
    }


    /**
     * @param callable $callback
     */
    public function setFinishCallback(callable $callback)
    {
        $this->finishCallback = $callback;
    }


    /**
     * executes the finish callback
     */
    public function finish()
    {
        if (!is_null($this->finishCallback)) {
            call_user_func($this->finishCallback);
        }
    }


    /**
     * formats number into timestamp
     *
     * @param $sec
     *
     * @return string
     */
    private function formatTime($sec)
    {
        if ($sec > 100) {
            $sec /= 60;
            if ($sec > 100) {
                $sec /= 60;

                return number_format($sec) . " hr";
            }

            return number_format($sec) . " min";
        }

        return number_format($sec) . " sec";
    }


}