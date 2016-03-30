<?php

namespace Sedona\SBOAdminThemeBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SedonaSBOAdminThemeExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     *
     * @param ContainerBuilder $container
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        $jsAssets  = '@SedonaSBOAdminThemeBundle/Resources/';
        $lteJs     = $jsAssets . 'public/vendor/adminlte/js/';
        $cssAssets = 'bundles/sedonasboadmintheme/';
        $lteCss    = $cssAssets . 'vendor/adminlte/css/';
        $lteFont   = $cssAssets . 'vendor/adminlte/fonts/';

        if(isset($bundles['TwigBundle'])) {
            $container->prependExtensionConfig('twig', array(
                'form' => array(
                    'resources' => array(
                        'SedonaSBOAdminThemeBundle:layout:form-theme.html.twig'
                    )
                ),
                'globals' => array(
                    'admin_theme' => '@avanzu_admin_theme.theme_manager'
                )
            ));
        }

        if (isset($bundles['AsseticBundle'])) {
            $container->prependExtensionConfig(
                'assetic',
                array(
                    'bundles' => array(
                        'SedonaSBOAdminThemeBundle'
                    ),
                    'assets' => array(
                        'admin_lte_flot'         => array(
                            'inputs' => array(
                                $lteJs . 'plugins/flot/*',
                            )
                        ),
                        'avatar_img'             => array(
                            'inputs' => array(
                                '@SedonaSBOAdminThemeBundle/Resources/public/img/avatar.png',
                                '@SedonaSBOAdminThemeBundle/Resources/public/vendor/select2/select2.png'
                            )
                        ),
                        'admin_lte_all'          => array(
                            'inputs' => array(
                                $jsAssets . 'public/vendor/jquery-ui/jquery-ui.min.js',
//                                $jsAssets . 'public/vendor/underscore/underscore.js',
//                                $jsAssets . 'public/vendor/backbone/backbone.js',
//                                $jsAssets . 'public/vendor/marionette/lib/backbone.marionette.js',
                                $jsAssets . 'public/vendor/bootstrap/dist/js/bootstrap.min.js',
//                                $jsAssets . 'public/vendor/bootbox/bootbox.js',
                                $jsAssets . 'public/js/dialogs.js',
//                                $jsAssets . 'public/js/namespace.js',
                                $jsAssets . 'public/vendor/holderjs/holder.js',
//                                $jsAssets . 'public/vendor/spinjs/spin.js',
                                $lteJs .    'plugins/bootstrap-slider/bootstrap-slider.js',
                                //$jsAssets . 'public/vendor/momentjs/moment.js',
                                $jsAssets . 'public/vendor/moment/min/moment-with-locales.min.js',
                                $lteJs .    'plugins/colorpicker/bootstrap-colorpicker.min.js',
                                //$lteJs . 'plugins/daterangepicker/daterangepicker.js',
// remplacer par le suivant     $lteJs .    'plugins/timepicker/bootstrap-timepicker.js',
                                $lteJs .    'plugins/datetimepicker/bootstrap-datetimepicker.min.js',
//                                $lteJs .    'plugins/input-mask/jquery.inputmask.js',
//                                $lteJs . 'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.js',
//                                $lteJs . 'plugins/morris/morris.js',
                                //$jsAssets . 'public/vendor/fullcalendar/dist/fullcalendar.min.js',
                                $jsAssets . 'public/js/jquery.dataTables.min.js',
                                $lteJs .    'plugins/datatables/dataTables.bootstrap.js',
//                                $lteJs .    'plugins/slimScroll/jquery.slimscroll.js',
                                $jsAssets . 'public/vendor/select2/select2.min.js',
                                $jsAssets . 'public/js/adminLTE.js',
                            )
                        ),
                        'admin_lte_all_css'      => array(
                            'inputs' => array(
                                //$lteCss . 'fullcalendar/fullcalendar.css',
//                                $lteCss . 'morris/morris.css',
//                                $lteCss . 'bootstrap-wysihtml5/bootstrap3-wysihtml5.css',
                                $lteCss . 'colorpicker/bootstrap-colorpicker.css',
                                //$lteCss . 'daterangepicker/daterangepicker-bs3.css',
                                $lteCss . 'timepicker/bootstrap-timepicker.css',
                                $lteCss . 'datetimepicker/bootstrap-datetimepicker.css',
                                $cssAssets . 'vendor/bootstrap/dist/css/bootstrap.min.css',
                                $lteCss . 'bootstrap-slider/slider.css',
                                $lteCss . 'datatables/dataTables.bootstrap.css',
                                $cssAssets . 'vendor/fontawesome/css/font-awesome.min.css',
                                $cssAssets . 'vendor/ionicons/css/ionicons.min.css',
                                $lteCss . 'AdminLTE.css',
                                $cssAssets . 'vendor/select2/select2.css',
                                $cssAssets . 'vendor/select2/select2-bootstrap.css',
                                $cssAssets . 'css/adminLTE.css',
                            )
                        ),
                    )
                )
            );

        }
    }
}
