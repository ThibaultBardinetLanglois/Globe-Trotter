<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Repository\CountryRepository;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Form\CommentType;
use App\Service\ImageUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/", name="country_")
 */
class CountryFormController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(CountryRepository $countryRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'countries' => $countryRepository->findAll()
        ]);
    }

    /**
     * @Route("user/country/new", name="new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        EntityManagerInterface $em,
        ImageUploaderService $imageUploader
    ): Response
    {
        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageData = $form->get('image')->getData();
            if ($imageData !== null) {
                $newImageName = $imageUploader->upload($imageData);
                $country->setImage($newImageName);
            }
            $country->setOwner($this->getUser());
            $em->persist($country);
            $em->flush();
            $this->addFlash('succes', "L'image a bien été tranférée");
            return $this->redirectToRoute('country_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country_form/new.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    /**
     * @Route("country/show/{id}", name="show", requirements={"page"="\d+"})
     */
    public function show(
        Country $country,
        CommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $user = $this->getUser();
        $comment = new Comment;
        $comment->setCountry($country);
        $comment->setAuthor($user);
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('country_show', ['id' => $country->getId()]);
        }

        $comments = $commentRepository->findBy(
            ['country' => $country],
            ['id' => 'DESC']
        );
        return $this->render('country/show.html.twig', [
            'country' => $country,
            'user' => $user,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("user/country/edit/{id}", name="edit", methods={"GET","POST"}, requirements={"page"="\d+"})
     */
    public function edit(
        Request $request,
        EntityManagerInterface $em,
        Country $country,
        ImageUploaderService $imageUploader
    ): Response
    {
        $image = $country->getImage();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageData = $form->get('image')->getData();
            if ($imageData !== null) {
                if (is_string($this->getParameter('images_directory'))) {
                    $imageName = $this->getParameter('images_directory') . '/' . $image;
                    unlink($imageName);
                }
                $newImageName = $imageUploader->upload($imageData);
                $country->setImage($newImageName);
            }
            $em->persist($country);
            $em->flush();

            return $this->redirectToRoute('country_show', ['id' => $country->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('country_form/edit.html.twig', [
            'country' => $country,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/country/delete/{id}", name="delete", methods={"POST"}, requirements={"page"="\d+"})
     */
    public function delete(
        Request $request,
        EntityManagerInterface $em,
        Country $country
    ): Response
    {
        if ($this->isCsrfTokenValid('delete'.$country->getId(), $request->request->get('_token'))) {
            $em->remove($country);
            $em->flush();
        }

        return $this->redirectToRoute('country_index', [], Response::HTTP_SEE_OTHER);
    }
}
