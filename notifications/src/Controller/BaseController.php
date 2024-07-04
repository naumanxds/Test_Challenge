<?php

namespace App\Controller;

use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BaseController extends AbstractController
{
    #[Route('/', name: 'landing_page')]
    public function index(): Response
    {
        return $this->render('base/index.html.twig');
    }

    #[Route('/logs', name: 'logs')]
    public function logs(LoggerInterface $logger): Response
    {
        $logsData = '';
        try {
            $logsData = file_get_contents($this->getParameter('path.user_event_log'));
        } catch (\Exception $e) {
            $logger->error('User Event File not found');
        }

        $logsData = explode(PHP_EOL, $logsData);

        return $this->render('base/logs.html.twig', [ 'logsData' => $logsData ]);
    }
}
