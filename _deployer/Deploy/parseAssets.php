<?php

namespace Deploy;


/**
 * Class parseAssets
 *
 * @package Deploy
 */
class parseAssets
{

    /*
     *
     */
    public function __construct($dir, \lessc $less)
    {
        $assets = $dir . "/../assets/";
        $less->compileFile($assets . "dev/less/main.less", $assets . "prod/style.css");
        $this->copyImageDir($assets);
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