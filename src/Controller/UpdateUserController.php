<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AllUserType;
use App\Form\UpdateUserType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UpdateUserController extends AbstractController
{
    #[Route('/update/user/{id}', name: 'app_update_user', methods: ['GET', 'POST'])]
    public function index(
        User $user,
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $hasher
    ): Response {


        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if ($this->getUser() !== $user) {
            return $this->redirectToRoute('app_main');
        }


        $form = $this->createForm(UpdateUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $user->setPassword(
                $hasher->hashPassword(
                    $user,
                    $form->get('Password')->getData()
                )
            );

            $user = $form->getData();

            $manager->persist($user);
            $manager->flush();;

            return $this->redirectToRoute('app_main');
        }


        return $this->render('update_user/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    #[Route('/all/user', name: 'app_all_user', methods: ['GET', 'POST'])]
    public function AllUser(UserRepository $repository)
    {


        if ($this->getUser()->getUserAdmin() == '1') {
            return $this->render('user/alluser.html.twig', [
                'user' => $repository->findAll()
            ]);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }

    #[Route('user/update/{id}', name: 'update_user', methods: ['GET', 'POST'])]
    public function edit(User $user, Request $Request, EntityManagerInterface $manager): Response
    {
        // $quiz = $repository->findOneBy(['id' => $id]);
        if ($this->getUser()->getUserAdmin() == '1') {
            $form = $this->createForm(AllUserType::class, $user);
            $form->HandleRequest($Request);
            if ($form->isSubmitted() && $form->isValid()) {

                $info = $form->getData();

                $manager->persist($info);
                $manager->flush();

                return $this->redirectToRoute('app_all_user');
            }

            return $this->render('user/updateuser.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('app_main');
        }
    }


    #[Route('/user/delete/{id}', name: 'user_delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, User $user): Response
    {


        if ($this->getUser()->getUserAdmin() == '1') {

            $manager->remove($user);
            $manager->flush();

            return $this->redirectToRoute('app_all_user');
        } else {
            return $this->redirectToRoute('app_main');
        }
    }
}
