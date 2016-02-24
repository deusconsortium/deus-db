<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 08/02/16
 * Time: 15:14
 */

namespace Deus\DBBundle\Model;


use Symfony\Component\Finder\Finder;

class IndexDirectory
{
    private $localPath;

    private $files;

    private $groups;

    /**
     * IndexDirectory constructor.
     * @param $localPath
     */
    public function __construct($localPath)
    {
        $this->localPath = $localPath;
        $this->files = [];
        $this->groups = [];
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
            if(preg_match("/([^ ]+)[ ]+([^ ]+)[ ]+([^ ]+)[ ]+([^ ]+)([ ]+)([0-9]+)([ ]+)([^ ]+)([ ]+)([^ ]+)([ ]+)([0-9]{4})[ ]+(.+)/", $oneLine, $infos)) {
                $this->files[$infos[13]] = $infos[6];
            }
            elseif($oneLine) {
                throw new \Exception("ls.txt not correctly formatted: $oneLine");
            }
        }

        $groupDirs = $this->getGroupDirectories();
        foreach($groupDirs as $oneGroup) {
            $this->groups[] = new IndexDirectory($oneGroup->getRealPath());
        }

    }

    public function totalFromPattern($pattern)
    {
        $totalNb = 0;
        $totalSize = 0;
        $totalGroups = count($this->groups);

        foreach($this->files as $name => $size) {
            if(preg_match($pattern, $name)) {
                $totalNb++;
                $totalSize = gmp_add($size, $totalSize);
            }
        }
        $totalSize = gmp_div($totalSize, 1024);

        foreach($this->groups as $oneGroup) {
            list($subNb, $subSize, $subGroups) = $oneGroup->totalFromPattern($pattern);
            $totalNb += $subNb;
            $totalSize = gmp_add($subSize, $totalSize);
            $totalGroups += $subGroups;
        }

        return [$totalNb, $totalSize, $totalGroups];
    }

    protected function getGroupDirectories()
    {
        $finder = new Finder();

        $dirs = $finder
            ->in($this->localPath)
            ->directories()
            ->name('/group_([0-9]{5})/')
            ->depth("==0")
            ;

        return $dirs;
    }

}