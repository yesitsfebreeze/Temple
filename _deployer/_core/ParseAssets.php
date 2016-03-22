<?php

namespace Deployer;


/**
 * Class parseAssets
 *
 * @package Deployer
 */
class ParseAssets
{

    public function __construct($dir)
    {
        $assets = $dir . "/../assets/";

        $this->parseLess($assets);
        $this->copyImageDir($assets);
    }


    /**
     * @param $assets
     * @throws \Exception
     */
    private function parseLess($assets)
    {
        $less = new \lessc();
        $less->compileFile($assets . "dev/less/index.less", $assets . "prod/index.css");
        $less = new \lessc();
        $less->compileFile($assets . "dev/less/api.less", $assets . "prod/api.css");
    }

    /**
     * @param $assets
     */
    private function copyImageDir($assets)
    {
        $source = $assets . "dev/img";
        $dest   = $assets . "prod/img";

        exec("rm -rf " . escapeshellarg($dest));
        if (!is_dir($dest)) {
            mkdir($dest, 0755);
        }
        $source = $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($source as $item) {
            if ($item->isDir()) {
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }
}