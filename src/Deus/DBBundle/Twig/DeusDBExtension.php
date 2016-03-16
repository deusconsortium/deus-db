<?php

/**
 * Created by PhpStorm.
 * User: jpasdeloup
 * Date: 09/03/16
 * Time: 14:06
 */

namespace Deus\DBBundle\Twig;

use Deus\DBBundle\Entity\Geometry;
use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Twig_Extension;

/**
 * Class DeusDBExtension
 * @package Deus\DBBundle\Twig
 * @Service("deus.twig")
 * @Tag("twig.extension")
 */
class DeusDBExtension extends Twig_Extension

{
    /**
     * @return array
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('formatZ', array($this, 'formatZFilter')),
            new \Twig_SimpleFilter('formatPattern', array($this, 'formatPatternFilter')),
        );
    }

    /**
     * @param $number
     * @return float|string
     */
    public function formatZFilter($number)
    {
        return Geometry::formatZ($number);
    }

    public function formatPatternFilter($pattern)
    {
        // First remove lead and tail /$   ^/
        $pattern = substr($pattern,2,-2);

        // Replace common patterns by simpler names
        $pattern = str_replace(['([0-9]{5})','(.*)'],['<number>','<name>'], $pattern);

        // Remove useless \
        $pattern = stripslashes($pattern);

        return $pattern;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'deusdb_extension';
    }
}