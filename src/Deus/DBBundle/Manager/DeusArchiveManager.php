<?php

namespace Deus\DBBundle\Manager;

/**
 * Description of DEUSManager *
 * @author admin
 */
class DeusArchiveManager
{
    protected $path;
    
    protected $snapshots;
    protected $cones;
    
    protected $simulationName;    
    protected $simulationInfos;
    
    protected $simulationType;
   
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
    
    public function retrieveSnapshotInfos($path, $snapshot)
    {
        $file = $path.'/info_'.$snapshot.'.txt';
        if(!file_exists($file)) {
            $this->error("$file does not exist");
            return;
        }
        $infos = $this->convertInfosFile(file_get_contents($file));
                
        $this->snapshots[$snapshot]["infos"] = $infos;
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
    
    public function convertInfosFile($data)
    {
        $lines = explode("\n", $data);

        for($i=0; $i<18; $i++) {
            if(strpos($lines[$i], "=") !== false) {
                list($arg, $value) = explode("=", $lines[$i]);
                if(trim($arg) && trim($value)) {
                    $res[trim($arg)] = trim($value);
                }
            }
        }
        $res["Z"] = abs(1.0/$res["aexp"] - 1.0);
        return $res;
    }
    
    public function convertNmlFile($data)
    {
        $lines = explode("\n", $data);
                
        $currBloc = "default";
        $res = array();
        
        foreach($lines as $oneLine) {            
            if(trim($oneLine) == "") {
                continue;
            }
            elseif($oneLine[0] == "&") {
                $currBloc = trim(substr($oneLine, 1));
            }
            elseif($oneLine[0] == "/") {
                $currBloc = "default";
            }
            else {
                list($arg, $value) = explode("=", $oneLine);
                if(trim($arg) && trim($value)) {
                    $res[$currBloc][trim($arg)] = trim($value);
                }    
            }
            
        }        
        return $res;
    }

    public function getSimulationName()
    {
        return $this->simulationName;
    }
    
    public function getSimulationResolution()
    {
        return $this->simulationType['resolution'];
    }
    
    public function getSimulationResolutionPow()
    {
        return pow($this->simulationType['resolution'],3);
    }
    
    public function getSimulationBoxlen()
    {
        return $this->simulationType['boxlen'];
    }
    
    public function getSimulationCosmo()
    {
        return $this->simulationType['cosmo'];
    }
    
    public function retrieveSimulationName()
    {
        $this->path = rtrim($this->path,"/ ");
        $this->simulationName = substr($this->path,strrpos($this->path,'/')+1);

        preg_match("/boxlen([0-9]+)_n([0-9]+)_(.*)/", $this->simulationName, $matches);

        if(!count($matches) == 4) {
            $this->error("name doesn't match");
            return;
        }

        $this->simulationType = array(
            'boxlen' => $matches[1],
            'resolution' => $matches[2],
            'cosmo' => $matches[3]
        );
        
    }

    public function getSnaphots() 
    {
        return $this->snapshots;
    }
    
    public function getCones() 
    {
        return $this->cones;
    }
    
    public function getPath() 
    {
        return $this->path;
    }
    
    public function getInfos($bloc, $value) 
    {
        return $this->simulationInfos[$bloc][$value];
    }
    
    public function getBValues() 
    {        
        $b = array();
        foreach($this->snapshots as $oneSnapshot) {
            foreach($oneSnapshot['halos'] as $oneB => $files) {
                $b[$oneB] = true;
            }
        }
        
        ksort($b);
        return array_keys($b);
    }
    
    public function error($msg) 
    {
        echo $msg;
        //echo '<span style="color:red;">Error: '.$msg.'</span>';
    }

    public function getFileSize($file)
    {
        list($localSize,$file) = explode("\t", exec("du ".$file, $return));
        return $localSize;
    }
}

?>
