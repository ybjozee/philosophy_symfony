<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Utility\DateTimeUtility;
use App\Utility\ObjectUtility;
use App\Utility\RequestUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/comment/")
 */
class CommentController extends AbstractController
{
    private RequestUtility $requestUtility;

    /**
     * CommentController constructor.
     * @param RequestUtility $requestUtility
     */
    public function __construct(RequestUtility $requestUtility)
    {
        $this->requestUtility = $requestUtility;
    }


    /**
     * @Route("add", name="comment_on_post", methods={"POST"})
     */
    public function addComment(Request $request, PostRepository $postRepository): JsonResponse
    {
//        try {
        ObjectUtility::handleUpdate($this->getDoctrine(), $this->requestUtility
            ->processRequestForComment($request, $this->getUser()));
        return $this->json([
            'message' => 'Comment Posted Successfully',
            'data' => [
                'user' => $this->getUser()->getUsername(),
                'publishedAt' => DateTimeUtility::getCurrentDateTime()->format('M j, Y @ G:i')
            ]
        ]);
//        } catch (Exception $exception) {
//            return $this->json([
//                'message' => $exception->getMessage()
//            ], Response::HTTP_BAD_REQUEST);
//        }
    }

    /**
     * @Route("{id}", name="get_comments_for_post", methods={"GET"})
     */
    public function getCommentsForPost($id, CommentRepository $commentRepository)
    {
        return $this->json([
            'data' => array_map(fn(Comment $comment) => $comment->toArray(),
                $commentRepository->getCommentsForPost($id))
        ]);
    }
}
