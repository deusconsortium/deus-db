<?php

namespace Deus\DBBundle\EventListener;


use JMS\DiExtraBundle\Annotation\Observe;
use JMS\DiExtraBundle\Annotation\Service;
use Sedona\SBOAdminThemeBundle\Event\SidebarMenuEvent;
use Sedona\SBOAdminThemeBundle\Model\MenuItemModel;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SidebarSetupMenuListener
 * @package Sedona\SBOTestBundle
 * @Service("menu.listener")
  */
class SidebarSetupMenuListener
{
    /**
     * @param SidebarMenuEvent $event
     * @Observe("theme.sidebar_setup_menu")
     * @Observe("theme.breadcrumb")
     */
    public function onSetupMenu(SidebarMenuEvent $event)
    {
        $request = $event->getRequest();

        foreach ($this->getMenu($request) as $item) {
            $event->addItem($item);
        }

    }

    protected function getMenu(Request $request)
    {
        $earg      = array();
        $rootItems = array(
            $simulationLink = new MenuItemModel('simulation', 'admin.simulation_search', 'admin_simulation_list', $earg, 'fa fa-edit'),
            $editLink = new MenuItemModel('crud', 'admin.edit_contents', null, $earg, 'fa fa-edit'),
        );

        $editLink
            ->addChild(new MenuItemModel('project', 'admin.project.entity_name', 'admin_project_list', $earg, 'fa fa-edit'))
            ->addChild(new MenuItemModel('cosmology', 'admin.cosmology.entity_name', 'admin_cosmology_list', $earg, 'fa fa-edit'))
            ->addChild(new MenuItemModel('supercomputer', 'admin.supercomputer.entity_name', 'admin_supercomputer_list', $earg, 'fa fa-edit'))
            ->addChild(new MenuItemModel('storage', 'admin.storage.entity_name', 'admin_storage_list', $earg, 'fa fa-edit'))
            ->addChild(new MenuItemModel('objecttype', 'admin.objecttype.entity_name', 'admin_objecttype_list', $earg, 'fa fa-edit'))
            ->addChild(new MenuItemModel('objectformat', 'admin.objectformat.entity_name', 'admin_objectformat_list', $earg, 'fa fa-edit'))
            ;

        return $this->activateByRoute($request->get('_route'), $rootItems);

    }

    protected function activateByRoute($route, $items)
    {
        // First check exact match
        $found = false;
        foreach($items as $item) { /** @var $item MenuItemModel */
            if($item->hasChildren()) {
                $this->activateByRoute($route, $item->getChildren());
            }
            else {
                if($item->getRoute() == $route) {
                    $item->setIsActive(true);
                    $found = true;
                }
            }
        }
        // Then check approx match admin_A_*
        if(!$found) {
            $routeElts = explode("_", $route);
            if($routeElts[0] == "admin" && count($routeElts) == 3) {
                foreach($items as $item) { /** @var $item MenuItemModel */
                    if($item->hasChildren()) {
                        $this->activateByRoute($route, $item->getChildren());
                    }
                    else {
                        $elts = explode("_", $item->getRoute());
                        if($elts[0] == "admin" && $elts[1] == $routeElts[1]) {
                            $item->setIsActive(true);
                        }
                    }
                }
            }

        }
        return $items;
    }


}