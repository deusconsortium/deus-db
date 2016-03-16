<?php

namespace Deus\DBBundle\Controller\Pub;

use Deus\DBBundle\Entity\ObjectGroup;
use Deus\DBBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="public_home")
     */
    public function indexAction(Request $request)
    {
        $postDatatable = $this->get('public_datatable');

        $user = $this->get('security.token_storage')->getToken()->getUser();

        if($user instanceof User) {
            if($request->query->get("edit_visibility_mode")) {
                $user->addRole("ROLE_CHANGE_VISIBILITY");
            }
            else {
                $user->removeRole("ROLE_CHANGE_VISIBILITY");
            }
            $this->get('doctrine.orm.entity_manager')->flush();
        }

        return $this->render("@DeusDB/Pub/index.html.twig", array(
            "datatable" => $postDatatable
        ));
    }

    /**
     * JSON call for datatable to list all Simulation entities.
     *
     * @Route("/datatable", name="public_datatable")
     * @Method("GET")
     */
    public function datatableAction()
    {
        $postDatatable = $this->get('public_datatable');
        $datatable = $this->get("sedona_deusdb.datatable")->getDatatable($postDatatable);

        $res = $datatable->getResponse();

        return $res;
    }

    /**
     * Show a ObjectGroup.
     *
     * @Route("/show/{id}", name="public_show", options={"expose"=true})
     * @Method("GET")
     */
    public function showAction(ObjectGroup $entity)
    {
        return $this->render("DeusDBBundle:Pub:show.html.twig", array(
            "entity" => $entity
        ));
    }

    /**
     * Show a ObjectGroup.
     *
     * @Route("/export", name="public_export", options={"expose"=true})
     * @Method("GET")
     */
    public function exportAction()
    {
        $groups = $this->getDoctrine()->getEntityManager()->createQueryBuilder()
            ->from("DeusDBBundle:ObjectGroup", "og")
            ->select("og")
            ->join("og.Geometry","g")
            ->join("og.ObjectFormat","of")
            ->join("og.ObjectType","ot")
            ->join("og.Storage","Storage")
            ->join("g.Simulation","s")
            ->join("g.GeometryType","gt")
            ->join("s.Resolution", "r")
            ->join("s.Cosmology", "c")
            ->join("s.Boxlen", "b")
            ->addOrderBy("b.value", "ASC")
            ->addOrderBy("c.name", "ASC")
            ->addOrderBy("r.value", "ASC")
            ->addOrderBy("gt.id", "DESC")
            ->addOrderBy("g.Z", "DESC")
            ->addOrderBy("g.angle", "ASC")
            ->getQuery()

            ->getResult()
            ;

        $response = $this->render("DeusDBBundle:Pub:export.csv.twig", array(
            "groups" => $groups
        ));
        $response->headers->set("Content-Type", "text/csv");
        return $response;
    }

    /**
     * @Route("/toggleVisibility/object/{id}", name="public_visibility_object", options={"expose"=true})
     * @Method("GET")
     */
    public function toggleObjectVisibility(ObjectGroup $object)
    {
        if(!$this->getUser() || !$this->getUser()->hasRole('ROLE_CHANGE_VISIBILITY')) {
            throw new AccessDeniedException();
        }

        $object->setPublic(!$object->getPublic());
        $this->get("doctrine.orm.entity_manager")->flush();

        return new Response($object->getPublic() ? "checked" : "");
    }

    /**
     * @Route("/toggleVisibility/simulation/{id}", name="public_visibility_simulation", options={"expose"=true})
     * @Method("GET")
     */
    public function toggleObjectSimulationVisibility(ObjectGroup $object)
    {
        if(!$this->getUser() || !$this->getUser()->hasRole('ROLE_CHANGE_VISIBILITY')) {
            throw new AccessDeniedException();
        }

        $Simulation = $object->getGeometry()->getSimulation();
        if($Simulation->getPublic()) { // Simulation already public, toggle object
            $object->setPublic(!$object->getPublic());
        }
        else { // Else
            $object->setPublic(true);
            $Simulation->setPublic(true);
        }

        $this->get("doctrine.orm.entity_manager")->flush();

        return new Response($object->getPublic() ? "checked" : "");
    }
}
