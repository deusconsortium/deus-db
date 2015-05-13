<?php

namespace Deus\DBBundle\Datatables;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class UserDatatable
 * @Service("admin_user_datatable")
 * @Tag("sg.datatable.view")
 */
class UserDatatable extends AbstractCrudDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->setParameters();
        $this->setColumns();

        $this->getAjax()->setUrl($this->getRouter()->generate("admin_user_datatable"));

        //$this->setIndividualFiltering(true); // Uncomment it to have a search for each field

        $actions = [];
        if ($this->getRouter()->getRouteCollection()->get("admin_user_show") != null) {
            $actions[] = [
                "route" => "admin_user_show",
                "route_parameters" => array("id" => "id"),
                "label" => $this->getTranslator()->trans("crud.title.show", [], 'admin'),
                "icon" => "glyphicon glyphicon-eye-open",
                "attributes" => array(
                    "rel" => "tooltip",
                    "title" => "Show",
                    "class" => "btn btn-default btn-xs",
                    "role" => "button"
                )
            ];
        }
        if(count($actions)>0) {
            $this->getColumnBuilder()
                ->add(null, "action", array(
                    "title" => "Actions",
                    "actions" => $actions
                ));
        }
    }

    protected function setParameters() {
        $this->getFeatures()
            ->setServerSide(true)
            ->setProcessing(true)
        ;
        $this->setStyle(self::BOOTSTRAP_3_STYLE);
    }


    /**
     * {@inheritdoc}
     */
    protected function setColumns() {

        $this->getColumnBuilder()
            ->add("username", "column", array("title" => $this->getTranslator()->trans("admin.user.username", [], 'admin')))
            ->add("usernameCanonical", "column", array("title" => $this->getTranslator()->trans("admin.user.usernameCanonical", [], 'admin')))
            ->add("email", "column", array("title" => $this->getTranslator()->trans("admin.user.email", [], 'admin')))
            ->add("emailCanonical", "column", array("title" => $this->getTranslator()->trans("admin.user.emailCanonical", [], 'admin')))
            ->add("enabled", "column", array("title" => $this->getTranslator()->trans("admin.user.enabled", [], 'admin')))
            ->add("salt", "column", array("title" => $this->getTranslator()->trans("admin.user.salt", [], 'admin')))
            ->add("password", "column", array("title" => $this->getTranslator()->trans("admin.user.password", [], 'admin')))
            ->add("lastLogin", "datetime", array("title" => $this->getTranslator()->trans("admin.user.lastLogin", [], 'admin'), "format" => "L LTS"))
            ->add("locked", "column", array("title" => $this->getTranslator()->trans("admin.user.locked", [], 'admin')))
            ->add("expired", "column", array("title" => $this->getTranslator()->trans("admin.user.expired", [], 'admin')))
            ->add("expiresAt", "datetime", array("title" => $this->getTranslator()->trans("admin.user.expiresAt", [], 'admin'), "format" => "L LTS"))
            ->add("confirmationToken", "column", array("title" => $this->getTranslator()->trans("admin.user.confirmationToken", [], 'admin')))
            ->add("passwordRequestedAt", "datetime", array("title" => $this->getTranslator()->trans("admin.user.passwordRequestedAt", [], 'admin'), "format" => "L LTS"))
            ->add("roles", "column", array("title" => $this->getTranslator()->trans("admin.user.roles", [], 'admin')))
            ->add("credentialsExpired", "column", array("title" => $this->getTranslator()->trans("admin.user.credentialsExpired", [], 'admin')))
            ->add("credentialsExpireAt", "datetime", array("title" => $this->getTranslator()->trans("admin.user.credentialsExpireAt", [], 'admin'), "format" => "L LTS"))
        ;
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return 'DeusDBBundle:User';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return "user_datatable";
    }
}