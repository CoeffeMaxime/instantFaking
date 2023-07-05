<?php

namespace App\Controller\Front;

use App\Repository\GameRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

#[Route('/game', name: 'app_game_')]
class GameController extends AbstractController
{

    public function __construct(
        private GameRepository $gameRepository,
        private TranslatorInterface $translator
    ) { }

    /**
     * @throws NonUniqueResultException
     */
    #[Route('/{slug}', name: 'show')]
    public function show(string $slug): Response
    {
        $game = $this->gameRepository->findFullOneBy($slug);

        if ($game === null) {
            $this->addFlash(
                'danger',
                $this->translator->trans('pages.game.show.error')
            );
            return $this->redirectToRoute('app_home');
        }

        $relatedGames = $this->gameRepository->findByRelatedCategory($game, 9);

        return $this->render('front/pages/game/show.html.twig', [
            'game' => $game,
            'relatedGames' => $relatedGames,
        ]);
    }

}
