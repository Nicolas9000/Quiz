<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\AddQuizType;
use App\Form\EditQuizType;
use App\Repository\CategorieRepository;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategorieRepository $repository): Response
    {
        return $this->render('main/index.html.twig', [
            'categories' => $repository->findAll()
        ]);
    }

    #[Route('categorie/{id}/', name: 'categorie_question', methods: ['GET', 'POST'])]
    public function CategorieQuestion(QuestionRepository $repository, Question $reponse, int $id, Request $request): Response
    {
        // $quiz = $repository->findOneBy(['id' => $id]);

        // dd($reponse);

        return $this->render('quiz/CategorieQuestion.html.twig', [
            'CategorieQuiz' => $repository->findBy(['categorie' => $id]),
        ]);
    }



    #[Route('quiz/update', name: 'all_quizz', methods: ['GET', 'POST'])]
    public function AllQuiz(QuestionRepository $repository): Response
    {
        return $this->render('quiz/update.html.twig', [
            'quizz' => $repository->findAll()
        ]);
    }

    #[Route('quiz/update/{id}', name: 'update_quizz', methods: ['GET', 'POST'])]
    public function edit(Question $question, Request $Request, EntityManagerInterface $manager): Response
    {
        // $quiz = $repository->findOneBy(['id' => $id]);
        $form = $this->createForm(EditQuizType::class, $question);
        $form->HandleRequest($Request);
        if ($form->isSubmitted() && $form->isValid()) {

            $quiz = $form->getData();

            $manager->persist($quiz);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre quiz a été modifié avec succès'
            );

            return $this->redirectToRoute('all_quizz');
        }

        return $this->render('quiz/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/quiz/delete/{id}', name: 'delete_quiz', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Question $question): Response
    {

        $manager->remove($question);
        $manager->flush();

        return $this->redirectToRoute('all_quizz');
    }

}
