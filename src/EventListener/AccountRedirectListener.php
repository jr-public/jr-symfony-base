<?php

namespace App\EventListener;

use App\Entity\User;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AccountRedirectListener
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private RouterInterface $router,
    ) {}

    #[AsEventListener]
    public function onRequestEvent(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        $currentRoute = $event->getRequest()->attributes->get('_route');
        if (!$user->isVerified()) {
            if ($currentRoute !== 'app_register') {
                $event->setResponse(new RedirectResponse($this->router->generate('app_register')));
            }
            return;
        }
        if ($user->getProfile() === null) {
            if ($currentRoute !== 'app_profile') {
                $event->setResponse(new RedirectResponse($this->router->generate('app_profile')));
            }
            return;
        }
    }
}