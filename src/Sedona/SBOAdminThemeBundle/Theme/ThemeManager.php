<?php
/**
 * ThemeManager.php
 * publisher
 * Date: 18.04.14
 */

namespace Sedona\SBOAdminThemeBundle\Theme;

use Sedona\SBOAdminThemeBundle\Util\DependencyResolverInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpKernel\Config\FileLocator;

class ThemeManager
{

    /** @var  Container */
    protected $container;

    protected $stylesheets = array();

    protected $javascripts = array();

    protected $locations   = array();

    protected $resolverClass;

    function __construct($container, $resolverClass = null)
    {
        $this->container     = $container;
        $this->resolverClass = $resolverClass?: 'Sedona\SBOAdminThemeBundle\Util\DependencyResolver';
    }



    public function registerScript($id, $src, $deps = array(), $location = "bottom")
    {

        if (!isset($this->javascripts[$id])) {
            $this->javascripts[$id] = array(
                'src'      => $src,
                'deps'     => $deps,
                'location' => $location
            );
        }

    }

    public function registerStyle($id, $src, $deps = array()) {
        if(!isset($this->stylesheets[$id])) {
            $this->stylesheets[$id] = array(
                'src'      => $src,
                'deps'     => $deps,
            );
        }
    }

    public function getScripts($location = 'bottom') {

        $unsorted = array(); $srcList = array(); $assetList = array();
        foreach($this->javascripts as $id => $scriptDefinition) {
            if($scriptDefinition['location'] == $location) {
                $unsorted[$id] = $scriptDefinition;
            }
        }

        $queue = $this->getResolver()->register($unsorted)->resolveAll();
        foreach($queue as $def){
            $srcList[] = $def['src'];
        }
        return $srcList;
    }

    public function getStyles() {
        $srcList = array();
        $queue = $this->getResolver()->register($this->stylesheets)->resolveAll();
        foreach($queue as $def){
            $srcList[] = $def['src'];
        }
        return $srcList;
    }

    /**
     * @return DependencyResolverInterface
     */
    protected function getResolver() {
        $class = $this->resolverClass;
        return new $class;
    }

    /**
     * @return FileLocator
     */
    protected function getLocator() {
        return $this->container->get('file_locator');
    }

}