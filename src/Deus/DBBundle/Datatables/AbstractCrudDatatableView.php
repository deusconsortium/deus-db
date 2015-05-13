<?php

namespace Deus\DBBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Base CRUD Datatable View
 */
abstract class AbstractCrudDatatableView extends AbstractDatatableView
{

    private $linesFormatter = [];


    public function __construct(TwigEngine $templating, TranslatorInterface $translator, RouterInterface $router, array $defaultLayoutOptions)
    {
        parent::__construct(
            $templating,
            $translator,
            $router,
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

}
