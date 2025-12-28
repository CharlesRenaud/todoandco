<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        // Rediriger vers la page d'accueil avec un message flash
        $session = $request->getSession();
        $session->getFlashBag()->add('error', 'Accès refusé. Vous n\'avez pas les permissions nécessaires pour accéder à cette page.');
        
        return new RedirectResponse('/');
    }
}
