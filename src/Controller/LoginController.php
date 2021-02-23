<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="api_login", methods={"POST"})
     */
    public function login()
    {
        return $this->redirect('/show');
    }

    /**
     * @Route("/")
     */
    public function logout()
    {
        return $this->json(['result' => true]);
    }
}
