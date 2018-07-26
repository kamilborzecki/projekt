<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Blog;
use AppBundle\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     * @return Response
     */
 public function indexAction()
 {
     $entityManager = $this->getDoctrine()->getManager();

     $posts = $entityManager->getRepository(Blog::class)->findAll();

     return $this->render('blog/index.html.twig', ["posts" => $posts]);
 }

    /**
     * @Route("/blog/details/{id}", name="blog_details")
     *
     * @param Blog $blog
     * @return Response
     */
 public function detailsAction(Blog $blog)
 {
    return $this->render("blog/details.html.twig", ["blog" => $blog]);
 }

    /**
     * @Route("/blog/add", name="blog_add")
     * @param Request $request
     * @return Response
     */
    public function addAction(Request $request) {
        $blog = new Blog();

        $form = $this->createForm(BlogType::class, $blog);
        if ($request->isMethod("post")) {

            $form->handleRequest($request);

            if($form->isValid()) {

                $blog->setCreatedAt(new \DateTime());

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($blog);
                $entityManager->flush();
                return $this->redirectToRoute("blog_details", ["id" => $blog->getId()]);
            }
        }

        return $this->render("blog/add.html.twig", ["form" => $form->createView()]);
    }
}