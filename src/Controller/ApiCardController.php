<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use OpenApi\Attributes as OA;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/card', name: 'api_card_')]
#[OA\Tag(name: 'Card', description: 'Routes for all about cards')]
class ApiCardController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger
    ) {
    }
    #[Route('/all', name: 'List all cards', methods: ['GET'])]
    #[OA\Parameter(name: 'page', description: 'The current page', in: 'query', required: false, schema: new OA\Schema(type: 'integer'))]
    #[OA\Parameter(name: 'set_code', description: 'The set code', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Put(description: 'Return all cards in the database')]
    #[OA\Response(response: 200, description: 'List all cards with a limit of a certain number')]
    #[OA\Response(response: 404, description: 'No cards found')]
    public function cardAll(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $setCode = $request->query->get('set_code') ?? '';
        $loggerStartText = 'All cards have been requested';
        $loggerDuration = microtime(true);
        $cards = $this->entityManager->getRepository(Card::class)->getAllWithPagination($page,$setCode, 100);
        if (empty($cards)) {
            $this->logger->error($loggerStartText.' but '.'no cards found', ['page' => $page, "limit" => 100]);
            return $this->json(['error' => 'No cards found'], 404);
        }
        $loggerEndText = 'All cards have been shown';
        $loggerDuration = microtime(true) - $loggerDuration;
        $loggerConcat = $loggerStartText.' and '.$loggerEndText.' in '.$loggerDuration.' seconds';
        $this->logger->info($loggerConcat, ['page' => $page, "limit" => 100, "cards" => $cards]);
        return $this->json($cards);
    }

    #[Route('/search', name: 'Search card', methods: ['GET'])]
    #[OA\Parameter(name: 'query', description: 'Search query', in: 'query', required: true, schema: new OA\Schema(type: 'string'))]
    #[OA\Parameter(name: 'set_code', description: 'The set code', in: 'query', required: false, schema: new OA\Schema(type: 'string'))]
    #[OA\Put(description: 'Search a card by name')]
    #[OA\Response(response: 200, description: 'Search card')]
    #[OA\Response(response: 404, description: 'Card not found')]
    public function cardSearch(Request $request): Response
    {
        $query = $request->query->get('query');
        $setCode = $request->query->get('set_code') ?? '';
        $loggerStartText = 'Card with specific name has been requested';
        $loggerDuration = microtime(true);
        $card = $this->entityManager->getRepository(Card::class)->search($query, $setCode);
        if (!$card) {
            $this->logger->error($loggerStartText.' but '.'card not found', ['query' => $query]);
            return $this->json(['error' => 'Card not found'], 404);
        }
        $loggerEndText = 'Card with name has been shown';
        $loggerDuration = microtime(true) - $loggerDuration;
        $loggerConcat = $loggerStartText.' and '.$loggerEndText.' in '.$loggerDuration.' seconds';
        $this->logger->info($loggerConcat, ['query' => $query, "card" => $card]);
        return $this->json($card);
    }

    #[Route('/{uuid}', name: 'Show card', methods: ['GET'])]
    #[OA\Parameter(name: 'uuid', description: 'UUID of the card', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))]
    #[OA\Put(description: 'Get a card by UUID')]
    #[OA\Response(response: 200, description: 'Show card')]
    #[OA\Response(response: 404, description: 'Card not found')]
    public function cardShow(string $uuid): Response
    {
        $loggerStartText = 'Card with specific UUID has been requested';
        $loggerDuration = microtime(true);
        $card = $this->entityManager->getRepository(Card::class)->findOneBy(['uuid' => $uuid]);
        if (!$card) {
            $this->logger->error($loggerStartText.' but '.'card not found', ['uuid' => $uuid]);
            return $this->json(['error' => 'Card not found'], 404);
        }
        $loggerEndText = 'Card with UUID has been shown';
        $loggerDuration = microtime(true) - $loggerDuration;
        $loggerConcat = $loggerStartText.' and '.$loggerEndText.' in '.$loggerDuration.' seconds';
        $this->logger->info($loggerConcat, ['uuid' => $uuid, "card" => $card]);
        return $this->json($card);
    }
}
