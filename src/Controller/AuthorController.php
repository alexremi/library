<?php


namespace App\Controller;

use App\Repository\AuthorRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;

/**
 * @Route("/author")
 */
class AuthorController extends AbstractController
{
    /**
     * @Route("/", name="author_index", methods={"GET"})
     * @param AuthorRepository $authorRepository
     * @return Response
     */
    public function index(AuthorRepository $authorRepository): Response
    {
        return $this->render('author/index.html.twig', [
            'authors' => $authorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="author_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        if (isset($_POST['send'])){
            $name = $_POST['name'];

        $sql = "INSERT INTO author (name) VALUES('$name')";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        return $this->redirectToRoute('author_index');
        }

        return $this->render('author/createauthor.html.twig');
    }

    /**
     * @Route("/{id}/edit", name="author_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id): Response
    {
        if (isset($_POST['update'])){
            $name = $_POST['name'];

            $sql = "UPDATE author SET name = '$name' WHERE id='$id'";
            $em = $this->getDoctrine()->getManager();
            $stmt = $em->getConnection()->prepare($sql);
            $stmt->execute();
        return $this->redirectToRoute('author_index');
        }
        $sql2="SELECT a.name FROM author a WHERE a.id=$id";
        $anames = $this->getConnection()->executeQuery($sql2)->fetchAllAssociative();
        $aname = implode('',$anames[0]);
        return $this->render('author/editauthor.html.twig', ['authorId'=>$id, 'aname'=>$aname]);
    }

    /**
     * @Route("/{id}", name="author_show", methods={"GET"})
     * @param Author $author
     * @return Response
     */
    public function show($id): Response
    {
        $author = $this->getDoctrine()->getRepository(Author::class)->find($id);
        return $this->render('author/showauthor.html.twig', [
            'author' => $author,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="author_delete", methods={"GET", "DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $author = $em->getRepository(Author::class)->find($id);
        $em->remove($author);
        $em->flush();

        return $this->redirectToRoute('author_index');
    }

    private function getConnection(): Connection{
        $conn = $this->getDoctrine()->getManager()->getConnection();
        return $conn;
    }
}