<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Créer un utilisateur administrateur avec un email et un mot de passe sécurisés.',
)]
class CreateAdminCommand extends Command
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question'); // Récupération du helper de question

        // Demande l'email
        $emailQuestion = new Question('Entrez l\'adresse email de l\'administrateur : ');
        $email = $helper->ask($input, $output, $emailQuestion);

        // Demande le mot de passe (masqué)
        $passwordQuestion = new Question('Entrez le mot de passe de l\'administrateur : ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $passwordQuestion);

        // Vérification des entrées
        if (empty($email) || empty($password)) {
            $io->error('L\'email et le mot de passe ne peuvent pas être vides.');
            return Command::FAILURE;
        }

        // Création du nouvel admin
        $user = new User();
        $user->setEmail($email);
        $user->setRoles(['ROLE_ADMIN']);

        // Hash du mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashedPassword);

        // Sauvegarde en base
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success("L'utilisateur admin $email a été créé avec succès !");
        return Command::SUCCESS;
    }
}
