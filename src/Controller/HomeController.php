<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        $request->getSession()->set('test_csrf', 'ok');
        // dd($request->getSession()->getId());

        $message = null;

        if ($request->isMethod('POST')) {
            $submittedToken = $request->request->get('_token');

            if ($csrfTokenManager->isTokenValid(new CsrfToken('test-form', $submittedToken))) {
                $message = '✅ Jeton CSRF valide.';
            } else {
                $message = '❌ Jeton CSRF invalide !';
            }
        }

        $csrfToken = $csrfTokenManager->getToken('test-form')->getValue();

        return $this->render('home/index.html.twig', [
            'csrf_token' => $csrfToken,
            'message' => $message,
        ]);
    }
}
