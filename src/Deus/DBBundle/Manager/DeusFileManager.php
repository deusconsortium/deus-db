<?php

namespace Deus\DBBundle\Manager;

/**
 * Description of DEUSManager *
 * @author admin
 */
class DeusFileManager
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
            $this->path = $path;                        
            $this->retrieveSimulationName();
            $this->retrieveSimulationInfos();
            $this->retrieveSnapshots($checkFiles);
            $this->retrieveCones($checkFiles);
            $this->retrieveAllHalos($checkFiles);
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
    
    public function retrieveSimulationInfos()
    {
        $file = $this->path.'/log/mycosmoDM0.nml';
        if(!file_exists($file)) {
            $this->error("$file does not exist");
            return;
        }
        $this->simulationInfos = $this->convertNmlFile(file_get_contents($file));
    }  

    public function retrieveOneCube($path)
    {        
        $nb = 0;
        if ($handlesub = opendir($path)) {                        
            while (false !== ($subentry = readdir($handlesub))) {
                if(!is_dir($path.'/'.$subentry) && preg_match("/(.*)_cube_([0-9]{5})/", $subentry, $matchessub)) {                    
                     $nb++;
                }
                elseif(is_dir($path.'/'.$subentry) && preg_match("/group_([0-9]{5})/", $subentry, $matchessub)) {                                        
                    $nb += $this->retrieveOneCube($path.'/'.$subentry);
                }
            }
            closedir($handlesub);
        }
        return $nb;
    }
    
    public function retrieveSnapshots($checkFiles)
    {   
        if ($handle = opendir($this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if(is_dir($this->path.'/'.$entry) && preg_match("/cube_([0-9]{5})/", $entry, $matches)) {
                    $snapshot = $matches[1];
                    if($checkFiles) {
                        $this->snapshots[$snapshot]["cube"]["files"] = $this->retrieveOneCube($this->path.'/'.$entry);
                    }
                    else {
                        $this->snapshots[$snapshot]["cube"]["files"] = "?";
                    }
                    $this->retrieveSnapshotInfos($this->path.'/'.$entry,$snapshot);
                    $this->retrieveCubesProperties($snapshot);
                }
            }
            closedir($handle);
        }
    }
    
    public function retrieveCones($checkFiles)
    {        
        if ($handle = opendir($this->path)) {
            while (false !== ($entry = readdir($handle))) {
                if(is_dir($this->path.'/'.$entry) && preg_match("/cube_(fullsky|narrow)/", $entry, $matches)) {
                    $snapshot = $matches[1];
                    if($checkFiles) {
                        $this->cones[$snapshot]["cube"]["files"] = $this->retrieveOneCube($this->path.'/'.$entry);                    
                    }
                    else {
                        $this->snapshots[$snapshot]["cube"]["files"] = "?";
                    }
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
  
    public function retrieveCubesProperties($snapshot)
    {        
        $propCubesFile = realpath($this->path.'/../analysis/cubes_properties/fof_'.$this->getSimulationName().'_'.$snapshot.'_fields_00000_prop_00000.txt');
        if(file_exists($propCubesFile)) {
            $this->snapshots[$snapshot]['cube']["properties"] = $propCubesFile;
        }                
    }
    
    public function retrieveHalosProperties($snapshot, $b)
    {
        $propHalosFile = $this->path.'/../analysis/halos_properties/fof_'.$this->getSimulationName().'_'.$snapshot.'_halos_'.str_pad($b,5,'0',STR_PAD_LEFT).'_prop_00000.txt';
        if(file_exists($propHalosFile)) {
        $this->snapshots[$snapshot]['halos'][$b]["properties"] = $propHalosFile;
        }        
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
        $this->simulationName = trim(substr($this->path,strrpos($this->path,'/')+1), " /");
        
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
    
    public function retrieveAllHalos($checkFiles)
    {
        $path = $this->path.'/post';
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {                
                if(is_dir($path.'/'.$entry)) {
                    if($entry == "fof") {
                        $this->retrieveHalos($path.'/'.$entry, $checkFiles);
                    }                        
                    elseif(preg_match("/fof_b([0-9]{5})/", $entry, $matches)) {
                        $this->retrieveHalos($path.'/'.$entry, $checkFiles, (float)$matches[1]/10000.0);
                    }
                }             
            }
            closedir($handle);
        }
    }
    
    public function retrieveHalos($path, $checkFiles, $b = 0.2)
    {        
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {                
                if(is_dir($path.'/'.$entry) && preg_match("/output_([0-9]{5})/", $entry, $matches)) {                    
                    if($checkFiles) {
                        list($nb_masst, $nb_strct) = $this->retrieveOneHalo($path.'/'.$entry);                    
                        $this->snapshots[$matches[1]]['halos'][$b*10000]["masst"] = $nb_masst;
                        $this->snapshots[$matches[1]]['halos'][$b*10000]["strct"] = $nb_strct;
                    }
                    else {
                        $this->snapshots[$matches[1]]['halos'][$b*10000]["masst"] = "?";
                        $this->snapshots[$matches[1]]['halos'][$b*10000]["strct"] = "?";
                    }
                    $this->retrieveHalosProperties($matches[1], $b*10000);                    
                }
                elseif(is_dir($path.'/'.$entry) && preg_match("/cone_(fullsky|narrow)/", $entry, $matches)) {                    
                    if($checkFiles) {
                        list($nb_masst, $nb_strct) = $this->retrieveOneHalo($path.'/'.$entry);                    
                        $this->cones[$matches[1]]['halos'][$b*10000]["masst"] = $nb_masst;
                        $this->cones[$matches[1]]['halos'][$b*10000]["strct"] = $nb_strct;                    
                    }
                    else {
                        $this->snapshots[$matches[1]]['halos'][$b*10000]["masst"] = "?";
                        $this->snapshots[$matches[1]]['halos'][$b*10000]["strct"] = "?";
                    }
                }
            }
            closedir($handle);
        }
    }
    
    public function retrieveOneHalo($path)
    {        
        $nb_masst = 0;
        $nb_strct = 0;
        if ($handlesub = opendir($path)) {                        
            while (false !== ($subentry = readdir($handlesub))) {
                if(!is_dir($path.'/'.$subentry)) {
                    if(preg_match("/(.*)_masst_([0-9]{5})/", $subentry, $matchessub)) {
                        $nb_masst++;
                    }
                    elseif(preg_match("/(.*)_strct_([0-9]{5})/", $subentry, $matchessub)) {
                        $nb_strct++;
                    }
                }
                elseif(is_dir($path.'/'.$subentry) && preg_match("/group_([0-9]{5})/", $subentry, $matchessub)) {                                        
                   list($add_masst, $add_strct) = $this->retrieveOneHalo($path.'/'.$subentry);
                   $nb_masst += $add_masst;
                   $nb_strct += $add_strct;
                }
            }
            closedir($handlesub);
        }
        return array($nb_masst, $nb_strct);
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
}

?>
