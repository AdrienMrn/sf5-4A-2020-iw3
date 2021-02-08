<?php

namespace App\Controller\Back;

use App\Entity\Book;
use App\Entity\Tag;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\DBAL\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController
 * @package App\Controller
 *
 * @Route("/book", name="book_")
 */
class BookController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(BookRepository $bookRepository): Response
    {
        $this->get('session')->set('_locale', 'fr');

        return $this->render('back/book/index.html.twig', [
            'books' => $bookRepository->findBy([], ['position' => 'ASC'])
        ]);
    }

    /**
     * @Route("/show/{slug}", name="show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('back/book/show.html.twig', [
            'book' => $book
        ]);
    }

    /**
     * @Route("/sortable/{slug}/{sortable}", name="sortable", methods={"GET"}, requirements={"sortable": "up|down"})
     */
    public function sortable(Book $book, $sortable): Response
    {
        $book->setPosition($sortable == 'up' ? $book->getPosition()+1 : $book->getPosition()-1);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('back_book_index');
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */
    public function new(Request $request)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            $this->addFlash('blue', 'Création réussie');

            return $this->redirectToRoute('back_book_show', ['slug' => $book->getSlug()]);
        }

        return $this->render('back/book/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @IsGranted("edit", subject="book")
     */
    public function edit(Book $book, Request $request)
    {
        //$this->denyAccessUnlessGranted('edit', $book);

        $form = $this->createForm(BookType::class, $book);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('blue', 'Modification réussie');

            return $this->redirectToRoute('back_book_edit', ['id' => $book->getId()]);
        }

        return $this->render('back/book/edit.html.twig', [
            'book' => $book,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("delete", subject="book")
     */
    public function delete(Book $book, $token)
    {
        //$this->denyAccessUnlessGranted('delete', $book);

        if (!$this->isCsrfTokenValid('delete_book' . $book->getId(), $token)) {
            throw new Exception('Token CSRF invalid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($book);
        $em->flush();

        $this->addFlash('blue', 'Suppression réussie');

        return $this->redirectToRoute('back_book_index');
    }
}
