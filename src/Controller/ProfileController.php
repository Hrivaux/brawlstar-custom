<?php

namespace App\Controller;

use App\Service\PlayerApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ProfileController extends AbstractController
{
    private PlayerApiService $playerApiService;

    public function __construct(PlayerApiService $playerApiService)
    {
        $this->playerApiService = $playerApiService;
    }

    #[Route('/profile', name: 'user_profile')]
    public function profile(): Response
    {
        // Récupération de l'utilisateur connecté
        $user = $this->getUser();

        // Vérification que le tag de joueur est renseigné
        if (!$user || !$user->getPlayerTag()) {
            $this->addFlash('error', 'Aucun PlayerTag renseigné.');
            return $this->redirectToRoute('app_home');
        }

        try {
            // Appels à l'API pour récupérer les données du joueur et son historique de batailles
            $playerData = $this->playerApiService->fetchPlayerByTag($user->getPlayerTag());
            $battleLog = $this->playerApiService->fetchPlayerBattleLog($user->getPlayerTag());
            $battleLogItems = $this->normalizeBattleLog($battleLog); // Normalisation des données de bataille
        } catch (\Exception $e) {
            $this->addFlash('error', 'Erreur lors de la récupération des données.');
            $playerData = [];
            $battleLogItems = [];
        }

        // Normalisation des données pour éviter des erreurs dans le template
        $playerData['club'] = $playerData['club'] ?? null;

        return $this->render('profile/index.html.twig', [
            'playerData' => $playerData,
            'battleLog' => $battleLogItems,
        ]);
    }

    /**
     * Normalise le format des dates dans les logs de bataille.
     *
     * @param array $battleLog Les logs de bataille renvoyés par l'API.
     * @return array Les logs avec des dates formatées.
     */
    private function normalizeBattleLog(array $battleLog): array
    {
        $items = $battleLog['items'] ?? []; // Par défaut, un tableau vide
        foreach ($items as &$item) {
            if (isset($item['battleTime'])) {
                try {
                    $dateTime = \DateTime::createFromFormat('Ymd\THis.v\Z', $item['battleTime']);
                    $item['battleTimeFormatted'] = $dateTime ? $dateTime->format('d/m/Y H:i:s') : 'Date invalide';
                } catch (\Exception $e) {
                    $item['battleTimeFormatted'] = 'Date invalide';
                }
            } else {
                $item['battleTimeFormatted'] = 'Date manquante';
            }
        }
        return $items;
    }

    #[Route('/profile/edit', name: 'profile_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form->get('oldPassword')->getData();

            // Vérifier que l'ancien mot de passe est correct
            if (!$passwordHasher->isPasswordValid($user, $oldPassword)) {
                $this->addFlash('error', 'Ancien mot de passe incorrect.');
                return $this->redirectToRoute('profile_edit');
            }

            // Vérification et hashage du mot de passe s'il est fourni
            if ($form->get('plainPassword')->getData()) {
                $hashedPassword = $passwordHasher->hashPassword($user, $form->get('plainPassword')->getData());
                $user->setPassword($hashedPassword);
            }

            // Mise à jour de la date de modification
            $user->setUpdatedAt(new \DateTime());

            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('profile_edit');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
