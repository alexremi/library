<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;

class AjaxController extends AbstractController
{
    /**
     * @Route("/_inline_ajax", name="_inline_ajax",methods={"GET", "POST"})
     */
    public function inline()
    {
        return new Response('This is not ajax', 400);
    }


    private function getConnection(): Connection
    {
        $conn = $this->getDoctrine()->getManager()->getConnection();
        return $conn;
    }
}