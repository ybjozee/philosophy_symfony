<?php

namespace App\Controller;

use App\Utility\DateTimeUtility;
use App\Utility\ObjectUtility;
use App\Utility\RequestUtility;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
    public function addComment(Request $request): JsonResponse
    {
        try {
            ObjectUtility::handleUpdate($this->getDoctrine(), $this->requestUtility
                ->processRequestForComment($request, $this->getUser()));
            return $this->json([
                'message' => 'Comment Posted Successfully',
                'data' => [
                    'user' => $this->getUser()->getUsername(),
                    'publishedAt' => DateTimeUtility::getCurrentDateTime()->format('M j, Y @ G:i')
                ]
            ]);
        } catch (Exception $exception) {
            return $this->json([
                'message' => $exception->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }
    }

}
