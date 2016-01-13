<?php

namespace Deus\DBBundle\Manager;

/**
 * Description of DEUSManager *
 * @author admin
 */
class DeusArchiveManager extends DeusFileManager
{
    public function __construct($path, $checkFiles = false) 
    {
        if(!file_exists($path)) {
            $this->error("path does not exist");
            return;
        }
        else {
            $this->snapshots = [];
            $this->cones = [];
            $this->path = $path;                        
            $this->retrieveSimulationName();

            $this->retrieveSnapshots($checkFiles);
            $this->retrieveCones($checkFiles);
            ksort($this->snapshots);
        }
    }


    public function retrieveOneDir($path)
    {
        $nbCube = 0;
        $sizeCube = "0";
        $nbStrct = 0;
        $sizeStrct = "0";
        $nbMasst = 0;
        $sizeMasst = "0";
        $localPath = "";

        $ls = file_get_contents($path."/ls.txt");

        $lines = explode("\n",$ls);

        $realpath = array_shift($lines); // get path
        array_shift($lines); // remove total line
        $infos = [];

        foreach($lines as $oneLine) {
            if(preg_match("/([^ ]+) ([^ ]+) ([^ ]+) ([^ ]+)([ ]+)([0-9]+)([ ]+)([^ ]+)([ ]+)([^ ]+)([ ]+)([0-9]{4}) (.+)/", $oneLine, $infos)) {

                $localSize = $infos[6];
                $localPath= $infos[13];

                if(strpos($localPath, "grav") === false && (strpos($localPath, "multicube") !== false || strpos($localPath, "grouptar") !== false)) {
                    $nbCube++;
                    $sizeCube = gmp_add($sizeCube, $localSize);
                }
                elseif(strpos($localPath, "multimasst") !== false || strpos($localPath, "masstprop.tar") !== false || strpos($localPath, "masst.tar") !== false) {
                    $nbMasst++;
                    $sizeMasst = gmp_add($sizeMasst, $localSize);
                }
                elseif(strpos($localPath, "multistrct") !== false) {
                    $nbStrct++;
                    $sizeStrct = gmp_add($sizeStrct, $localSize);
                }
            }
            elseif($oneLine) {
                echo "MATCH PAS: ".$oneLine;
            }
        }

        $res = [];

        if($nbCube > 0) {
            $res['cube'] = [
                'files' => $nbCube,
                'path' => $realpath,
                'size' => gmp_strval(gmp_div_q($sizeCube,1024))
            ];
        }

        if($nbMasst > 0) {
            $res['halos'][2000]["nb_masst"] = $nbMasst;
            $res['halos'][2000]["size_masst"] = gmp_strval(gmp_div_q($sizeMasst, 1024));
            $res['halos'][2000]["path"] = $realpath;
        }

        if($nbStrct > 0) {
            $res['halos'][2000]["nb_strct"] = $nbStrct;
            $res['halos'][2000]["size_strct"] = gmp_strval(gmp_div_q($sizeStrct, 1024));
            $res['halos'][2000]["path"] = $realpath;
        }

        return $res;
    }
    
    public function retrieveSnapshots($checkFiles)
    {   
        if ($handle = opendir($this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if(is_dir($this->path.'/'.$entry) && preg_match("/output_([0-9]{5})/", $entry, $matches)) {
                    $snapshot = $matches[1];
                    $this->snapshots[$snapshot] = $this->retrieveOneDir($this->path.'/'.$entry);
                    $this->retrieveSnapshotInfos($this->path.'/'.$entry,$snapshot);
                }
            }
            closedir($handle);
        }
    }
    
    public function retrieveCones($checkFiles)
    {        
        if ($handle = opendir($this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if(is_dir($this->path.'/'.$entry) && preg_match("/output_cone_([0-9])/", $entry, $matches)) {
                    $snapshot = $matches[1];
                    $this->cones[$snapshot] = $this->retrieveOneDir($this->path.'/'.$entry);
                }
            }
            closedir($handle);
        }
    }


}

?>
