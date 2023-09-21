<?php

namespace App\Controller;

use App\Entity\VideoGames;
use App\Form\VideoGamesType;
use App\Repository\VideoGamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/video/games')]
class VideoGamesController extends AbstractController
{
    #[Route('/', name: 'app_video_games_index', methods: ['GET'])]
    public function index(VideoGamesRepository $videoGamesRepository): Response
    {
        return $this->render('video_games/index.html.twig', [
            'video_games' => $videoGamesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_video_games_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $videoGame = new VideoGames();
        $form = $this->createForm(VideoGamesType::class, $videoGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($videoGame);
            $entityManager->flush();

            return $this->redirectToRoute('app_video_games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('video_games/new.html.twig', [
            'video_game' => $videoGame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_video_games_show', methods: ['GET'])]
    public function show(VideoGames $videoGame): Response
    {
        return $this->render('video_games/show.html.twig', [
            'video_game' => $videoGame,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_video_games_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VideoGames $videoGame, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VideoGamesType::class, $videoGame);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_video_games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('video_games/edit.html.twig', [
            'video_game' => $videoGame,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_video_games_delete', methods: ['POST'])]
    public function delete(Request $request, VideoGames $videoGame, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$videoGame->getId(), $request->request->get('_token'))) {
            $entityManager->remove($videoGame);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_video_games_index', [], Response::HTTP_SEE_OTHER);
    }
}
