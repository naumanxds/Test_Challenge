<?php

namespace App\Controller;

use App\Entity\User;
use App\Message\UserCreatedEvent;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @param MessageBusInterface $bus
     */
    private $bus;

    public function __construct(
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus
    ) {
        $this->entityManager = $entityManager;
        $this->bus = $bus;
    }

    #[Route(path:'/user', name: 'create_user', methods: 'post')]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstName($data['firstName']);
        $user->setLastName($data['lastName']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->bus->dispatch(new UserCreatedEvent(
            $user->getEmail(),
            $user->getFirstName(),
            $user->getLastName()
        ));

        return new JsonResponse([
            'message' => 'User Created',
        ], Response::HTTP_CREATED);
    }
}
