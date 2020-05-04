<?php

namespace App\Controller;

use App\Repository\PostRepository;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", methods={"GET"}, name="index")
     */
    public function index(Request $request, PostRepository $postRepository,
                          TagRepository $tagRepository): Response
    {
        $page = $request->query->get('page') ?? 1;
        $tag = $tagRepository->findByTag($request->query->get('tag'));

        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'paginator' => $postRepository->getPage($page, $tag),
            'featuredPosts' => $postRepository->getFeaturedPosts(),
            'tags' => $tagRepository->findAll()
        ]);
    }
}
