<?php

namespace DEUSConsortium\DEUSManager;

/**
 * Description of DEUSManager
 *
 * @author admin
 */
class KnownDirManager 
{
    protected $filename;
    
    protected $knownDirs;
    
    public function __construct($filename) 
    {        
        $this->filename = $filename;
        $this->generateKnownDirs();
    }
    
    public function getKnownDirs()
    {
        return $this->knownDirs;
    }
    
    protected function generateKnownDirs()
    {
        $string_dir = trim(file_get_contents($this->filename));
        $list_dir = explode("\n", $string_dir);
        sort($list_dir);
        $this->knownDirs = $list_dir;        
    }
    
    protected function explodeDir($dir)
    {
        $array = explode("/",trim($dir,'/'));
        
        if(count($array) == 1) {
            return $dir;
        }
        else {
            $main_dir = array_shift($array);
            return array($main_dir => $this->explodeDir(implode("/", $array)));
        }
    }

    public function addKnownDir($dir)
    {
        $dir = trim($dir);
        if(!in_array($dir, $this->knownDirs)) {
            file_put_contents($this->filename, $dir."\n", FILE_APPEND );
        }
        
    }
}

?>
