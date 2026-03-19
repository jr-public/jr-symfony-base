<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileForm\ExampleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $profile = $user->getProfile() ?? new Profile();

        // The correct form type depends on the client and should be dinamically selected
        $form = $this->createForm(ExampleType::class, $profile);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profile->setOwner($user);
            $entityManager->persist($profile);
            $entityManager->flush();

            return $this->redirectToRoute('app_account');
        }

        return $this->render('account/profile.html.twig', [
            'profileForm' => $form,
        ]);
    }
}