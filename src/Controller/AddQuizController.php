<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\AddQuizType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddQuizController extends AbstractController
{
    #[Route('/add/quiz', name: 'app_add_quiz', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $manager): Response
    {
        $quiz = new Question();
        // dd($quiz);
        $form = $this->createForm(AddQuizType::class, $quiz);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $quiz = $form->getData();

            $manager->persist($quiz);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre quiz a été ajouté avec succès'
            );

            return $this->redirectToRoute('app_add_quiz');
        }

        return $this->render('add_quiz/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
