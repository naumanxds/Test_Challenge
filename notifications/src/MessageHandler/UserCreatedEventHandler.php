<?php

namespace App\MessageHandler;

use App\Message\UserCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserCreatedEventHandler
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function __invoke(UserCreatedEvent $event): void
    {
        $logData = sprintf("{{{ User created: %s %s <%s> }}}", $event->getFirstName(), $event->getLastName(), $event->getEmail());
        dump($logData);
        $this->logger->error($logData);
    }
}