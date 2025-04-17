<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/user-list-tab', name: 'admin_user_list_tab')]
class UserListController extends AbstractController
{
    #[Route('', name: '')]
    public function __invoke(UserRepository $userRepository, EntityManagerInterface $em): Response
    {
        $users = $userRepository->findAllNonAdminUsers();

        return $this->render('admin/user_list.html.twig', [
            'users' => $users,
        ]);
    }
}
