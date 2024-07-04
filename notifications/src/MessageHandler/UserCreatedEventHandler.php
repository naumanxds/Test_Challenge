<?php

namespace App\MessageHandler;

use App\Message\UserCreatedEvent;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class UserCreatedEventHandler
{

    public function __invoke(UserCreatedEvent $event): void
    {
        $logData = sprintf("User created: %s %s <%s>", $event->getFirstName(), $event->getLastName(), $event->getEmail());
        dump($logData);
    }
}