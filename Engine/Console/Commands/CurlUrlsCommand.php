<?php


namespace Temple\Engine\Console\Commands;


use Temple\Engine\Console\Command;


/**
 * Class TestCommand
 *
 * @package Temple\Engine\Console\Commands
 */
class CurlUrlsCommand extends Command
{

    /**
     * defines the command
     */
    public function define()
    {
        $this->setName("curl:urls");
        $this->setUseProgress(true);
        $this->setProgressTitle("curling urls...");
        $this->setProgressTitleColor("green");
    }


    public function execute($arg = null)
    {
        if (isset($this->config["curlUrls"])) {
            foreach ($this->config["curlUrls"] as $curlUrl) {
                $curl = curl_init($curlUrl);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_exec($curl);
                curl_close($curl);
            }
        }

    }

}
