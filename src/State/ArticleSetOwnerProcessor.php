<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Article;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

#[AsDecorator('api_platform.doctrine.orm.state.persist_processor')]
class ArticleSetOwnerProcessor implements ProcessorInterface
{

    public function __construct(
        private ProcessorInterface $processor,
        private Security $security
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        if ($data instanceof Article && $data->getAuthor() === null && $this->security->getUser()) {
            $data->setAuthor($this->security->getUser());
        }
        $this->processor->process($data, $operation, $uriVariables, $context);
    }
}
