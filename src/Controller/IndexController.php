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
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(Request $request, PostRepository $postRepository,
                          TagRepository $tagRepository): Response
    {
        $page = $request->query->get('page') ?? 1;
        $tag = $tagRepository->findByTag($request->query->get('tag'));
        $category = $request->query->get('category');
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'paginator' => $postRepository->getPage($page, $tag, $category),
            'featuredPosts' => $postRepository->getFeaturedPosts(),
            'tags' => $tagRepository->findAll()
        ]);
    }

    /**
     * @Route("/find", name="find_articles", methods={"GET"})
     */
    public function search(Request $request, PostRepository $postRepository,
                           TagRepository $tagRepository)
    {
        $keywords = $request->query->get('keywords');
        $paginator = $postRepository->getSearchPaginator(explode(' ', $keywords));
//        dump($paginator);
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
            'paginator' => $paginator,
            'featuredPosts' => $postRepository->getFeaturedPosts(),
            'tags' => $tagRepository->findAll()
        ]);
    }
}
