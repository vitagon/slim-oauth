<?php

declare(strict_types=1);

namespace App\Http\Action;

use App\Http\Kernel\JsonResponse;
use App\Models\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DbTestAction implements RequestHandlerInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addRootEntityFromClassMetadata(User::class, 'd');
        $query = $this->em->createNativeQuery("SELECT * FROM users u", $rsm);
        $res = $query->getResult();

        return new JsonResponse($res);
    }
}
