<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use App\Utility\ObjectUtility;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("add", name="add_post")
     */
    public function addPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $shouldRedirect = $this->getOutcomeOfFormHandling($form, $request, $post);
        return $shouldRedirect ?? $this->returnPostView($post, $form);
    }

    /**
     * @Route("post/edit/{id}", name="edit_post")
     */
    public function edit(Request $request, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $shouldRedirect = $this->getOutcomeOfFormHandling($form, $request, $post);
        return $shouldRedirect ?? $this->returnPostView($post, $form);
    }

    /**
     * @Route("", name="admin_view_posts", methods={"GET"})
     */
    public function viewPosts(Request $request, PostRepository $postRepository): Response
    {
        $page = $request->query->get('page') ?? 1;

        return $this->render('admin/index.html.twig', [
            'paginator' => $postRepository->getPage($page),
            'showPosts' => true,
            'pagePathName' => 'admin_view_posts'
        ]);
    }

    /**
     * @Route("comments", name="admin_view_comments", methods={"GET"})
     */
    public function viewComments(Request $request, CommentRepository $commentRepository): Response
    {
        $page = $request->query->get('page') ?? 1;

        return $this->render('admin/index.html.twig', [
            'paginator' => $commentRepository->getPage($page),
            'showPosts' => false,
            'pagePathName' => 'admin_view_comments'
        ]);
    }

    /**
     * @Route("post/delete/{id}" , name="admin_delete_post")
     */
    public function deletePost(Post $post): Response
    {
        $this->deleteObject($post, 'Post');
        return $this->redirectToRoute('admin_view_posts');
    }

    /**
     * @Route("comment/delete/{id}" , name="admin_delete_comment")
     */
    public function deleteComment(Comment $comment): Response
    {
        $this->deleteObject($comment, 'Comment');
        return $this->redirectToRoute('admin_view_comments');

    }

    private function deleteObject($object, string $objectName)
    {
        $objectId = $object->getId();
        ObjectUtility::handleUpdate($this->getDoctrine(), $object, true);
        $this->addFlash('success', "{$objectName} with ID {$objectId} deleted successfully");
    }

    private function returnPostView(Post $post, FormInterface $form): Response
    {
        return $this->render('admin/form/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    private function getOutcomeOfFormHandling(FormInterface $form, Request $request,
                                              Post $post, $isUpdate = false): ?RedirectResponse
    {
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messageVerb = $isUpdate ? 'Updated' : 'Created';
            ObjectUtility::handleUpdate($this->getDoctrine(), $post);
            $this->addFlash('success', "Post ({$post->getTitle()}) {$messageVerb} Successfully");
            return $this->getAppropriateRedirection($form->get('saveAndCreateNew')->isClicked());
        }

        return null;
    }

    private function getAppropriateRedirection(bool $userWantsToAddNewPost): RedirectResponse
    {
        if ($userWantsToAddNewPost) {
            return $this->redirectToRoute('add_post');
        }
        return $this->redirectToRoute('admin_view_posts');
    }
}
