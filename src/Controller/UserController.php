<?php

namespace App\Controller;

use App\Repository\CountryRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/list", name="user_show_list", requirements={"page"="\d+"})
     */
    public function list(
        UserInterface $user
    ): Response
    {
        return $this->render('user/list.html.twig', [
            'user' => $user
        ]);
    }
}