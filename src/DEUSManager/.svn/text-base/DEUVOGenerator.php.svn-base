<?php

namespace DEUSConsortium\DEUSManager;

use DEUSConsortium\DEUSManager\DEUVOElement;

/**
 * Description of DEUVOGenerator
 *
 * @author admin
 */
class DEUVOGenerator {
    
    protected $dom;
    
    public function __construct($tag) 
    {
        $this->dom = new \DOMDocument('1.0', 'UTF-8');
        $this->root = $this->dom->createElementNS ("http://www.ivoa.net/xml/SimDM/v1.0", $tag);
    }
        
    public function getXml() 
    {
        $this->dom->appendChild($this->root);
        return $this->dom->saveXML();
    }
    
    protected function addElement($tagName, $value = "", $id = "", $parent = false)
    {        
        if(!$parent) {
            $parent = $this->root;
        }
        $element = new DEUVOElement($this->dom, $tagName, $value);
        if($id) {
            $element->setId($id);
        }        
        $parent->appendChild($element->getDomElement());
        return $element;
    }
    
    public function addIdentity($id, $parent = false)
    {
        $this->addElement("identity", "", $id, $parent);
        return $this;
    }
    
    public function addContact($id, $party, $role)
    {
        $contact = $this->addElement("contact");
        $this->addIdentity($id, $contact->getDomElement());
        $this->addElement("role", $role, "", $contact->getDomElement());
        $this->addElement("party", "", $party, $contact->getDomElement());
        return $this;
    }
    
    public function addCreated($date = false)
    {
        $date = new \DateTime($date);
        $this->addElement("created",$date->format("Y-m-d\TH:i:s.u"));
        return $this;
    }
    
    public function addParameter($id, $value, $unit = "")
    {
        $parameter = $this->addElement("parameter");
        $numericValue = $this->addElement("numericValue", "", "", $parameter->getDomElement());
        $this->addElement("value", $value, "", $numericValue->getDomElement());
        $this->addElement("unit", $unit, "", $numericValue->getDomElement());
        $this->addElement("inputParameter", "", $id, $parameter->getDomElement());
    }
    
    public function addOutputDataObjects($id, $maintype, $objects = array())
    {
        $output = $this->addElement("outputData");
        //$this->addIdentity($id, $output->getDomElement());
        $this->addElement("numberOfObjects", count($objects), "", $output->getDomElement());
        foreach($objects as $id => $oneObject) {
            $object = $this->addElement("object", "", $id, $output->getDomElement());
            foreach($oneObject as $type => $value) {
                $property = $this->addElement("property", "", "", $object->getDomElement());
                $this->addElement("property", "", $type, $property->getDomElement());
                if(is_array($value)) {
                    $numericValue = $this->addElement("numericValue", "", "", $property->getDomElement());
                    $this->addElement("value", $value['value'], "", $numericValue->getDomElement());
                    $this->addElement("unit", $value['unit'], "", $numericValue->getDomElement());
                } 
                else {
                    $this->addElement("stringValue", $value, "", $property->getDomElement());
                }
            }
        }
        $this->addElement("objectType", "", $maintype, $output->getDomElement());
    }
    
    public function addOutputDataStats($id, $maintype, $number, $characterisation = array())
    {
        $output = $this->addElement("outputData");
        $this->addIdentity($id, $output->getDomElement());
        $this->addElement("numberOfObjects", $number, "", $output->getDomElement());
        foreach($characterisation as $oneCharacterisation) {
            /*foreach($oneCharacterisation['values'] as "type" => $value) {
                $characterisation = $this->addElement("characterisation", $value, "", $output->getDomElement());
            } */           
        }
        $this->addElement("objectType", "", $maintype, $output->getDomElement());
    }
    
    public function __call($name, $arguments)
    {
        if(substr($name, 0, 3) == 'add') {
            $tag = lcfirst(substr($name, 3));
            $this->addElement($tag, $arguments[0], $arguments[1]);
            return $this;
        }
    }
}
