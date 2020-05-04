<?php


namespace App\Utility;


use App\Entity\Comment;
use App\Repository\PostRepository;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class RequestUtility
{
    private PostRepository $postRepository;

    /**
     * @param PostRepository $postRepository
     */
    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    private function getRequiredParameter(string $parameter, array $request)
    {
        if (!array_key_exists($parameter, $request)) {
            throw new Exception("Required parameter {$parameter} not provided");
        }
        return $request[$parameter];
    }

    public function getRequiredNumber(string $parameter, array $request)
    {
        $requiredValue = $this->getRequiredParameter($parameter, $request);
        if (!is_numeric($requiredValue)) {
            throw new Exception("Required parameter {$parameter} must be a number");
        }
        return $requiredValue;
    }

    public function getRequiredString(string $parameter, array $request)
    {
        $requiredValue = $this->getRequiredParameter($parameter, $request);
        if (is_numeric($requiredValue)) {
            throw new Exception("Required parameter {$parameter} must be a number");
        }
        return $requiredValue;
    }

    public function processRequestForComment(Request $request, UserInterface $user): Comment
    {
        $requestBody = RequestUtility::getRequestBody($request);
        $post = $this->postRepository->find(RequestUtility::getRequiredNumber('post', $requestBody));
        $content = RequestUtility::getRequiredParameter('comment', $requestBody);
        return (new Comment())
            ->setContent($content)
            ->setAuthor($user)
            ->setPost($post);
    }

    public function getRequestBody(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}