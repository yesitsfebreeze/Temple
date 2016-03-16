<?php

namespace Caramel\Services;


use Caramel\Exceptions\CaramelException;

class Helpers extends Service
{

    /**
     * searches a string for the needle and returns true if found
     *
     * @param string $string
     * @param string $needle
     * @return bool
     */
    public function str_find($string, $needle)
    {
        return sizeof(explode($needle, $string)) > 1;
    }


    /**
     * returns all found template files for the given abbreviation
     *
     * @param string $file
     * @return array
     * @throws CaramelException
     */
    public function templates($file)
    {
        # get the file extension
        # add add the config extension if it doesn't exist
        $ext       = strrev(substr(strrev($file), 0, 4));
        $configExt = '.' . $this->config->get("extension");
        if ($ext != $configExt) $file = $file . $configExt;

        $files = array();
        foreach ($this->template->dirs() as $level => $templateDir) {

            # concat all template directories
            # with he passed file path
            $template = $templateDir . $file;
            # add them to our array if they exist
            if (file_exists($template)) $files[ $level ] = $template;
        }
        # if we found some files return them
        if (sizeof($files) > 0) return $files;

        # otherwise throw an error
        throw new CaramelException("Can't find template file.", $file);
    }

}