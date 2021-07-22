<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/user", name="user_")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/list", name="show_list", requirements={"page"="\d+"})
     */
    public function list(
        UserInterface $user
    ): Response
    {
        return $this->render('user/list.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @Route("/country/{countryId}/comment/delete/{commentId}", name="comment_delete", methods={"POST"}, requirements={"page"="\d+"})
     * @ParamConverter("country", class="App\Entity\Country", options={"mapping": {"countryId": "id"}})
     * @ParamConverter("comment", class="App\Entity\Comment", options={"mapping": {"commentId": "id"}})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        Comment $comment,
        Country $country
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $em->remove($comment);
            $em->flush();
        }

        return $this->redirectToRoute('country_show', ['id' => $country->getId()]);
    }
}