<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin-user',
    description: 'Add user admin to access the back office',
)]
class CreateAdminUserCommand extends Command
{
    public function __construct(private EntityManagerInterface $entityManager, private UserPasswordHasherInterface $passwordEncoder)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Creates a new admin user.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $helper = $this->getHelper('question');

        $email = $helper->ask($input, $output, new Question('Enter the email address: '));
        $password = $helper->ask($input, $output, new Question('Enter the password: '));
        $name = $helper->ask($input, $output, new Question('Enter the first name: '));
        $lastName = $helper->ask($input, $output, new Question('Enter the last name: '));

        $user = new User();
        $user->setEmail($email);
        $user->setName($name);
        $user->setLastName($lastName);
        $user->setRoles(['ROLE_ADMIN']);

        $encodedPassword = $this->passwordEncoder->hashPassword($user, $password);
        $user->setPassword($encodedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->success('Admin user created successfully.');

        return Command::SUCCESS;
    }
}
