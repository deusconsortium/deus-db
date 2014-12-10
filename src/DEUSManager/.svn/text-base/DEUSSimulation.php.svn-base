<?php

namespace DEUSConsortium\DEUSManager;

use DEUSConsortium\DEUSManager\DEUVOElement;

/**
 * Description of DEUSSimulator
 *
 * @author admin
 */
class DEUSSimulation {
    
    protected $halos;
    protected $cones;
    protected $parameters;
    
    public function __construct() {
        ;
    }
    
    public function generateDEUVO()
    {   
        $simulation = new DEUVOGenerator("aSimulation");
        $simulation
                ->addIdentity("deuss/boxlen162_n1024_lcdmw5")
                ->addName("boxlen162_n1024_lcdmw5")
                ->addDescription()
                ->addReferenceURL()
                ->addCreated()
                ->addContact("deuss/jean-michel.alimi", "jean-michel.alimi", "publisher");

        $simulation->addParameter("deuss/omega_m", "0.26");
        $simulation->addParameter("deuss/h", "7.200E+01", "km s-1 Mpc-1");
        
        $snapshots["deuss/boxlen162_n1024_lcdmw5_0001"] = array(            
            "deuss/snapnum" => "0001",
            "deuss/redshift" => array("value" => 8.91, "unit" => "dimensionless")
        );
        $snapshots["deuss/boxlen162_n1024_lcdmw5_0003"] = array(
            "deuss/snapnum" => "0003",
            "deuss/redshift" => array("value" => 4, "unit" => "dimensionless")
        );
        
        $simulation->addOutputDataObjects("id", "deuss/snapshot", $snapshots);
        
        //file_put_contents("test.xml", $simulation->getXml());
        
        //echo "<xmp>";
        header('Content-Type: application/xml; charset=utf-8');
        echo $simulation->getXml();
        //echo "</xmp>";
        
    }
    
    public function generateDEUVO2()
    {   
        $simulation = new DEUVOGenerator("aPostProcessing");
        $simulation
                ->addIdentity("deuss/boxlen162_n1024_lcdmw5_0001")
                ->addName("boxlen162_n1024_lcdmw5_0001")
                ->addDescription()
                ->addReferenceURL()
                ->addCreated()
                ->addContact("jean-michel.alimi", "publisher");

        $simulation->addParameter("deuss/omega_m", "0.26");
        $simulation->addParameter("deuss/h", "7.200E+01", "km s-1 Mpc-1");
        
        $halos["deuss/boxlen162_n1024_lcdmw5_0001"] = array(            
            "deuss/snapnum" => "0001",
            "deuss/redshift" => 8.91
        );
        $halos["deuss/boxlen162_n1024_lcdmw5_0003"] = array(
            "deuss/snapnum" => "0003",
            "deuss/redshift" => 4
        );
        
        $simulation->addOutputDataStats("id", "deuss/halo", 750, $halos);
        
        //file_put_contents("test.xml", $simulation->getXml());
        
        //echo "<xmp>";
        header('Content-Type: application/xml; charset=utf-8');
        echo $simulation->getXml();
        //echo "</xmp>";
        
    }
    
}
