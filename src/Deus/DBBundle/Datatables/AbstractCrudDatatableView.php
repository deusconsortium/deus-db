<?php

namespace Deus\DBBundle\Datatables;

use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Ajax;
use Sg\DatatablesBundle\Datatable\View\Features;
use Sg\DatatablesBundle\Datatable\View\Options;
use Sg\DatatablesBundle\Datatable\View\Style;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Twig_Environment;

/**
 * Base CRUD Datatable View
 */
abstract class AbstractCrudDatatableView extends AbstractDatatableView
{

    private $linesFormatter = [];


    public function __construct(
        AuthorizationCheckerInterface $authorizationChecker,
        TokenStorageInterface $securityToken,
        Twig_Environment $twig,
        TranslatorInterface $translator,
        RouterInterface $router,
        RegistryInterface $doctrine,
        array $defaultLayoutOptions)
    {
        parent::__construct(
            $authorizationChecker,
            $securityToken,
            $twig,
            $translator,
            $router,
            $doctrine,
            $defaultLayoutOptions
        );

        $this->initLineFormatter();
    }

    public function addLineFormatter(callable $lineFormatter = null)
    {
        $this->linesFormatter[] = $lineFormatter;
    }


    protected function initLineFormatter() {
    }

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatters = $this->linesFormatter;

        $formatter = function($line) use ($formatters) {
            foreach($formatters as $callable) {
                if (is_callable($callable)) {
                    $line = call_user_func($callable, $line);
                }
            }
            return $line;
        };

        return $formatter;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getTranslator()
    {
        return $this->translator;
    }

    protected function setParameters()
    {
        $this->features->setFeatures(array(
            "auto_width" => true,
            "defer_render" => false,
            "info" => true,
            "jquery_ui" => false,
            "length_change" => true,
            "ordering" => true,
            "paging" => true,
            "processing" => true,
            "scroll_x" => true,
            "scroll_y" => "",
            "searching" => false,
            "server_side" => true,
            "state_save" => false,
            "delay" => 0
        ));
        $this->options->setOptions(array(
            "class" => Style::BOOTSTRAP_3_STYLE,
        ));
    }

    protected function setUrl($url)
    {
        $this->ajax->setOptions(array(
            "url" => $url,
            "type" => "GET"
        ));
    }
}
