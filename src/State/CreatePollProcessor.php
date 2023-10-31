<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Poll;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class CreatePollProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
    ) {
    }

    /**
     * @param Poll $data
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Poll {
        if (!$data instanceof Poll) {
            $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof User) {
            throw new UserNotFoundException('User not found');
        }

        $data->setOwner($currentUser);
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $data;
    }
}
