<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PostController extends AbstractController
{
    /**
     * @Route("/show", methods={"GET"})
     * @IsGranted("ROLE_USER")
     */
    public function showPost(): JsonResponse
    {
        $post = $this->getDoctrine()->getRepository('App:Post')->findPost();

        if (!$post) {
            return $this->json('not exist', 404);
        }

        return $this->json($post);
    }

    /**
     * @Route("/post/new", methods={"POST"})
     */
    public function newPost(Request $request, SerializerInterface $serializer)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if($form->isSubmitted()&&$form->isValid()){
            $data['user'] = $this->getUser();
            $post->setData($data);
            $em = $this->getDoctrine()->getManager();
            $em->persist($post);
            $em->flush();

            return $this->json($post, 200);

        }

        return $this->json('error', 400);
    }

    /**
     * @Route("/post/edit/{post}", methods={"POST"})
     */
    public function editPost(Request $request, Post $post, SerializerInterface $serializer)
    {
        $form = $this->createForm(PostType::class, $post);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager();
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->json('change post', 200);
        }

        return $this->json('error', 400);
    }

    /**
     * @Route("/post/remove/{post}", methods="DELETE")
     */
    public function removePost(Request $request, Post $post)
    {
            $em = $this->getDoctrine()->getManager();
            $em->remove($post);
            $em->flush();

            return $this->json(['message' => 'post successfully deleted'], 200);
    }
}
