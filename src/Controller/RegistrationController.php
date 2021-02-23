<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\SerializerInterface;

class RegistrationController extends AbstractController
{
    private $passwordEncoder;
    private $apiToken;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->apiToken = bin2hex(random_bytes(60));
    }

    /**
     *@Route("/user/registration", methods={"POST"})
     */
    public function registration(Request $request, SerializerInterface $serializer)
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $data = json_decode($request->getContent(), true);
        $form->submit($data);
        if($form->isSubmitted()&&$form->isValid()){
//            $user->setRoles(['ROLE_USER']);
//            $user->setApiToken($this->apiToken);
//            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
            $data['role'] = ['ROLE_USER'];
            $data['password'] = $this->passwordEncoder->encodePassword($user, $user->getPassword());
            $data['token'] = $this->apiToken;
            $user->setData($data);

            $em=$this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->json($user, 200);

//            $user = $serializer->serialize($user, 'json');
//
//            return JsonResponse::fromJsonString($user);
        }

        return $this->json('error', 400);
    }
}
