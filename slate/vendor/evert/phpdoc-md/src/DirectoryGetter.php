<?

class DirectoryGetter
{

    public function getDirs($dir = NULL, &$folders = array(), $initialPath = NULL)
    {

        if (is_null($initialPath)) {
            $initialPath = $dir;
        }

        if (is_dir($dir)) {
            # add the directory to the array
            $addDir = str_replace($initialPath, "", $dir);
            if ($addDir != "") $folders[] = $addDir;

            # iterate over its children
            $dirs = scandir($dir);
            foreach ($dirs as $directory) {
                if ($directory != "." && $directory != "..") {
                    $path = $dir . "/" . $directory;
                    if (is_dir($path)) {
                        # recursive here!
                        $this->getDirs($path, $folders, $initialPath);
                    }
                }
            }

        }

        return $folders;
    }


    public function getMainFiles($dir)
    {
        $return = array();
        $files  = scandir($dir);
        foreach ($files as $file) {
            if ($file != "." && $file != "..") {
                if (is_file($dir . "/" . $file)) {
                    $check = str_replace(".md", "", $file);
                    if (!is_dir($dir . "/" . $check)) {
                        $return[] = $file;
                    }
                }
                if (is_dir($dir . "/" . $file)) {
                    $check = $file . ".md";
                    if (!file_exists($dir . "/" . $check)) {
                        $return[] = $file;
                    }
                }
            }
        }

        return $return;
    }

}
