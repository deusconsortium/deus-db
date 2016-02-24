<?php
/**
 * Created by PhpStorm.
 * User: jean
 * Date: 08/02/16
 * Time: 15:13
 */

namespace Deus\DBBundle\Model;


use Deus\DBBundle\Entity\Simulation;
use Symfony\Component\Finder\Finder;

class IndexSimulation
{
    private $localPath;
    private $simulationName;
    private $boxlen;
    private $resolution;
    private $cosmo;
    private $root_dir;
    private $storage;
    private $file_format;

    /**
     * @var Simulation
     */
    private $Simulation;

    /**
     * IndexSimulation constructor.
     * @param $localPath
     */
    public function __construct($localPath)
    {
        $this->localPath = rtrim($localPath, "/ ");
        $this->retrieveSimulationName();
        $this->retrieveSimulationIndex();
    }

    public function retrieveSimulationName()
    {
        $this->simulationName = substr($this->localPath,strrpos($this->localPath,'/')+1);
        preg_match("/boxlen([0-9]+)_n([0-9]+)_(.*)/", $this->simulationName, $matches);

        if(!count($matches) == 4) {
            throw new \Exception("name doesn't match");
        }

        $this->boxlen = $matches[1];
        $this->resolution = $matches[2];
        $this->cosmo = $matches[3];
    }

    /**
     * @return mixed
     */
    public function getBoxlen()
    {
        return $this->boxlen;
    }

    /**
     * @return mixed
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * @return mixed
     */
    public function getCosmo()
    {
        return $this->cosmo;
    }

    /**
     * @return mixed
     */
    public function getSimulationName()
    {
        return $this->simulationName;
    }

    private function retrieveSimulationIndex()
    {
        $file = $this->localPath.'/index.txt';
        if(file_exists($file)) {
            $file_content = file_get_contents($file);
            $lines = explode("\n", $file_content);

            foreach($lines as $oneLine) {
                if(false !== strpos($oneLine, "=")) {
                    list($key,$value) = explode("=", $oneLine);
                    $key = strtolower($key);
                    $this->$key = $value;
                }
            }
        }
        else {
            throw new \Exception("index.txt not found");
        }
    }

    /**
     * @return mixed
     */
    public function getRootDir()
    {
        return $this->root_dir;
    }

    /**
     * @return mixed
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @return mixed
     */
    public function getFileFormat()
    {
        return $this->file_format;
    }

    public function getDirectories($path = null, $pattern = null)
    {
        $finder = new Finder();

        if($path) {
            $path = $this->localPath.DIRECTORY_SEPARATOR.$path;
        }
        else {
            $path = $this->localPath;
        }
        if(!file_exists($path)) {
            return [];
        }
        $dirs = $finder->in($path)->directories();

        if($pattern) {
            $dirs = $dirs->name($pattern);
        }
        $dirs->depth("==0");
        return $dirs;
    }

    /**
     * @return Simulation
     */
    public function getSimulation()
    {
        return $this->Simulation;
    }

    /**
     * @param Simulation $Simulation
     * @return $this
     */
    public function setSimulation($Simulation)
    {
        $this->Simulation = $Simulation;
        return $this;
    }
}