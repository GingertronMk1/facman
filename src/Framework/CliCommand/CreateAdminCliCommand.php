<?php

namespace App\Framework\CliCommand;

use App\Application\User\Command\CreateUserCommand;
use App\Application\User\CommandHandler\CreateUserCommandHandler;
use App\Domain\User\UserRepositoryException;
use App\Domain\User\ValueObject\UserId;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Create an admin user with username `admin@facman.test` and password `password`',
)]
class CreateAdminCliCommand extends Command
{
    public function __construct(
        private readonly CreateUserCommandHandler $handler,
    ) {
        parent::__construct();
    }

    /**
     * @throws \InvalidArgumentException
     * @throws UserRepositoryException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $command = new CreateUserCommand(
            name: 'FacMan Admin',
            email: 'admin@facman.test',
            password: 'password',
            id: UserId::fromString('0190cc0b-f01f-7c54-865c-600e259ec5fb')
        );

        $this->handler->handle($command);
        $io->definitionList(
            ['id' => $command->id],
            ['name' => $command->name],
            ['email' => $command->email],
            ['password' => $command->password],
        );

        return Command::SUCCESS;
    }
}
