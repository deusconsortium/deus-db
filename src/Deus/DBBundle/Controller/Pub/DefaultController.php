<?php

namespace Deus\DBBundle\Controller\Pub;

use Deus\DBBundle\Entity\ObjectGroup;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="public_home")
     */
    public function indexAction()
    {

//        $geometries = $this->getDoctrine()->getRepository("DeusDBBundle:Geometry")->findAll();
//        foreach ($geometries as $geometry) {
//            $geometry->setZ($geometry->getZ());
//        }
//        $this->getDoctrine()->getEntityManager()->flush();
//        die("OK?");


        $postDatatable = $this->get('public_datatable');
        //$postDatatable->buildDatatableView();

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
}
