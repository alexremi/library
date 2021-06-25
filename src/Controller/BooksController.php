<?php


namespace App\Controller;

use App\Repository\BooksRepository;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Books;

/**
 * @Route("/books")
 */
class BooksController extends AbstractController
{
    /**
     * @Route("/", name="books_index", methods={"GET","POST"})
     * @param BooksRepository $booksRepository
     * @return Response
     */
    public function index(BooksRepository $booksRepository): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id GROUP BY b.id ";
        $bookId=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/index.html.twig', [
            'books'=>$bookId
        ]);
    }

    /**
     * @Route("/inline", name="inline", methods={"POST"})
     */
    public function inline(Request $request)
    {
        if (isset($_POST['id'])){
            $value = $_POST['value'];
            $column = $_POST['column'];
            $id = $_POST['id'];

            $sql = "UPDATE book SET $column= '$value' WHERE id=$id";
            $this->getConnection()->executeStatement($sql);
        }
        //return new Response();
        return $this->redirectToRoute('books_index');
    }

    /**
     * @Route("/name", name="books_name", methods={"GET"})
     * @param BooksRepository $booksRepository
     * @return Responselocal
     */
    public function indexname(BooksRepository $booksRepository): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id GROUP BY b.id ";
        $bookId=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/indexname.html.twig', [
            'books'=>$bookId
        ]);
    }

    /**
     * @Route("/au", name="books_au", methods={"GET"})
     * @param BooksRepository $booksRepository
     * @return Response
     */
    public function indexau(BooksRepository $booksRepository): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id GROUP BY b.id ";
        $bookId=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/indexau.html.twig', [
            'books'=>$bookId
        ]);
    }

    /**
     * @Route("/desc", name="books_desc", methods={"GET"})
     * @param BooksRepository $booksRepository
     * @return Response
     */
    public function indexdesc(BooksRepository $booksRepository): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id GROUP BY b.id ";
        $bookId=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/indexdesc.html.twig', [
            'books'=>$bookId
        ]);
    }

    /**
     * @Route("/img", name="books_img", methods={"GET"})
     * @param BooksRepository $booksRepository
     * @return Response
     */
    public function indeximg(BooksRepository $booksRepository): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id GROUP BY b.id ";
        $bookId=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/indeximg.html.twig', [
            'books'=>$bookId
        ]);
    }

    /**
     * @Route("/year", name="books_year", methods={"GET"})
     * @param BooksRepository $booksRepository
     * @return Response
     */
    public function indexyear(BooksRepository $booksRepository): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id GROUP BY b.id ";
        $bookId=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/indexyear.html.twig', [
            'books'=>$bookId
        ]);
    }

    /**
     * @Route("/new", name="books_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        if (isset($_POST['send'])) {
            $name = $_POST['name'];
            $author = $_POST['author'];
            $description = $_POST['description'];
            $year = $_POST['year'];

            $uploaddir = 'uploads/';
            $uploadfile = $uploaddir . basename($_FILES['image']['name']);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                echo "Файл корректен и был успешно загружен.\n";
            } else {
                echo "Возможная атака с помощью файловой загрузки!\n";
            }

            $sql = "INSERT INTO book (name, description, image, year) VALUES('$name', '$description', '$uploadfile', $year)";
            $sql2 = "SELECT LAST_INSERT_ID()";

            $this->getConnection()->executeStatement($sql);
            $bookId=$this->getConnection()->executeQuery($sql2)->fetchFirstColumn()[0];

            foreach ($author as $authorId){
                $sql = "INSERT INTO book_author (book_id, author_id) VALUES ($bookId, $authorId)";
                $this->getConnection()->executeStatement($sql);
            }

            return $this->redirectToRoute('books_index');
        }

        $sql = "SELECT * FROM author";
        $em = $this->getDoctrine()->getManager();
        /** @var \Doctrine\DBAL\Driver\PDO\Statement $stmt */
        $stmt = $em->getConnection()->executeQuery($sql);

        return $this->render('book/createbook.html.twig', ['authors'=>$stmt->fetchAllAssociative()]);
    }

    /**
     * @Route("/{id}", name="books_show", methods={"GET"})
     * @param Books $book
     * @return Response
     */
    public function show($id): Response
    {
        $sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT a.name ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id WHERE b.id=$id GROUP BY b.id";
        $book = $this->getConnection()->executeQuery($sql)->fetchAllAssociative();

        return $this->render('book/showbook.html.twig', [
            'books' => $book,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="books_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, $id): Response
    {
        if (isset($_POST['send'])) {
            $name = $_POST['name'];
            $author = $_POST['author'];
            $description = $_POST['description'];
            $year = $_POST['year'];

            $uploaddir = 'uploads/';
            $uploadfile = $uploaddir . basename($_FILES['image']['name']);

            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
                echo "Файл корректен и был успешно загружен.\n";
            } else {
                echo "Возможная атака с помощью файловой загрузки!\n";
            }

            $sql = "UPDATE book SET name='$name', description='$description', image='$uploadfile', year=$year WHERE id=$id";
            $this->getConnection()->executeStatement($sql);

            $sql = "DELETE FROM book_author WHERE book_id=$id";
            $this->getConnection()->executeStatement($sql);

            foreach ($author as $authorId){
                $sql = "INSERT INTO book_author (book_id, author_id) VALUES ($id, $authorId)";
                $this->getConnection()->executeStatement($sql);
            }
            return $this->redirectToRoute('books_index');
        }
        /*$sql = "SELECT b.id,b.name,b.description,b.image,b.year, GROUP_CONCAT(DISTINCT CONCAT(a.name,',', a.id) ORDER BY a.id ASC SEPARATOR ', ') authors FROM book b INNER JOIN book_author ba on b.id = ba.book_id INNER JOIN author a on ba.author_id = a.id WHERE b.id=$id GROUP BY b.id";
        $author=$this->getConnection()->executeQuery($sql)->fetchAllAssociative();
        $authors=explode(', ', $author[0]["authors"]);
        $suction = [];
        foreach ($authors as $value){
            $values = explode(',', $value);
            $authorId = $values[1];
            $authorName = $values[0];
            $suction[] = [$authorId,$authorName];
        }*/

        $sql3="SELECT b.name FROM book b WHERE b.id=$id";
        $bnames = $this->getConnection()->executeQuery($sql3)->fetchAllAssociative();
        $bname = implode('',$bnames[0]);

        $sql4="SELECT b.description FROM book b WHERE b.id=$id";
        $bdescs = $this->getConnection()->executeQuery($sql4)->fetchAllAssociative();
        $bdesc = implode('',$bdescs[0]);

        $sql5="SELECT b.year FROM book b WHERE b.id=$id";
        $byears = $this->getConnection()->executeQuery($sql5)->fetchAllAssociative();
        $byear = implode('',$byears[0]);

        $sql2= "SELECT * FROM author";
        $author2=$this->getConnection()->executeQuery($sql2)->fetchAllAssociative();

        return $this->render('book/editbook.html.twig', ['authors'=>$author2,'bookId'=>$id, 'bname'=>$bname, 'bdesc'=>$bdesc, 'byear'=>$byear]);
    }

    /**
     * @Route("/{id}/delete", name="books_delete", methods={"GET", "DELETE"})
     */
    public function delete(Request $request, $id): Response
    {
        $sql = "DELETE b,ba FROM book b JOIN book_author ba  ON b.id = ba.book_id WHERE b.id = $id";
        $this->getConnection()->executeQuery($sql);

        return $this->redirectToRoute('books_index');
    }

    private function getConnection(): Connection{
        $conn = $this->getDoctrine()->getManager()->getConnection();
        return $conn;
    }


}