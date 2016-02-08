<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 08/02/16
 * Time: 15:14
 */

namespace Deus\DBBundle\Model;


class IndexDirectory
{
    private $localPath;

    private $files;

    /**
     * IndexDirectory constructor.
     * @param $localPath
     */
    public function __construct($localPath)
    {
        $this->localPath = $localPath;
        $this->files = [];
        $this->retrieveFiles($localPath);
    }

    public function retrieveFiles($path)
    {
        $ls = file_get_contents($path."/ls.txt");

        $lines = explode("\n",$ls);

        $realpath = array_shift($lines); // get path
        array_shift($lines); // remove total line
        $infos = [];

        foreach($lines as $oneLine) {
            if(preg_match("/([^ ]+) ([^ ]+) ([^ ]+) ([^ ]+)([ ]+)([0-9]+)([ ]+)([^ ]+)([ ]+)([^ ]+)([ ]+)([0-9]{4}) (.+)/", $oneLine, $infos)) {
                $this->files[$infos[13]] = $infos[6];
            }
            elseif($oneLine) {
                throw new \Exception("ls.txt not correctly formatted");
            }
        }
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    public function totalFromPattern($pattern)
    {
        $totalNb = 0;
        $totalSize = 0;
        foreach($this->files as $name => $size) {
            if(fnmatch($pattern, $name)) {
                $totalNb++;
                $totalSize = gmp_add($size, $totalSize);
            }
        }
        return [$totalNb, $totalSize];
    }
}