<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/new/{post}", methods={"POST"})
     */
    public function newComment(Request $request, Post $post, SerializerInterface $serializer)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if($form->isSubmitted()&&$form->isValid()){
            /** @var $user User */
            $user = $this->getUser();
            $comment->setAuthor($user);
            $comment->setPost($post);
            $em=$this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();

            $comment = $serializer->serialize($comment, 'json', ['groups' => ['post', 'comment']]);

            return JsonResponse::fromJsonString($comment);
        }

        return $this->json('error', 400);
    }

    /**
     * @Route("/comment/edit/{comment}", methods={"POST"})
     */
    public function editComment(Request $request, Comment $comment)
    {
        $form = $this->createForm(CommentType::class, $comment);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager();
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->json('change comment', 200);
        }

        return $this->json('error', 400);
    }

    /**
     * @Route("/comment/remove/{comment}", methods="DELETE")
     */
    public function removeComment(Request $request, Comment $comment)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        return $this->json(['message' => 'comment successfully deleted'], 200);
    }
}
