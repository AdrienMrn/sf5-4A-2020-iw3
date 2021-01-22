<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    protected static $defaultName = 'app:create-user';
    private $em;

    public function __construct(string $name = null, EntityManagerInterface $em)
    {
        parent::__construct($name);

        $this->em = $em;
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a new user.')
            ->setHelp('This command allows you to create a user...')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $helper = $this->getHelper('question');

        $question = new Question('Please enter the username [email] : ');
        $username = $helper->ask($input, $output, $question);

        $question = new Question('Please enter the password : ');
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        $user = (new User())
            ->setEmail($username)
            ->setPassword($password);

        $this->em->persist($user);
        $this->em->flush();

        return Command::SUCCESS;
    }
}