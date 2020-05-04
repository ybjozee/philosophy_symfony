<?php

namespace App\Controller;

use App\Entity\Post;
use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * @Route("/{slug}", name="view_post", methods={"GET"})
     */
    public function index(Post $post, PostRepository $postRepository,
                          TagRepository $tagRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'post' => $post,
            'featuredPosts' => $postRepository->getFeaturedPosts(),
            'tags' => $tagRepository->findAll(),
            'paginator' => $postRepository->getPage(1),

        ]);
    }
}
