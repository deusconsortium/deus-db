<?php

namespace DEUSConsortium\DEUSManager;

/**
 * Description of DEUVOGenerator
 *
 * @author admin
 */
class DEUVOElement {
    
    protected $dom;
    protected $element;
        
    public function __construct($dom, $tag, $value) 
    {
        $this->dom = $dom;        
        $this->element = $this->dom->createElement($tag, $value);
    }
    
    public function setAttribute($attr, $value)
    {
        $attr = $this->dom->createAttribute($attr);
        $attr->value = $value;
        $this->element->appendChild($attr);
    }
    
    public function setId($id) 
    {
        $this->setAttribute('publisherDID', $id);
        //$this->setAttribute('ivoId', "ivo://deus-consortium.org/".$id);
    }
    
    public function getDomElement()
    {
        return $this->element;
    }
        
    
}
