<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Vote;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class VoteOnPollProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
    ) {
    }

    /**
     * @param Vote $data
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): Vote {
        if (!$data instanceof Vote) {
            $this->persistProcessor->process($data, $operation, $uriVariables, $context);
        }

        $currentUser = $this->security->getUser();
        if (null === $currentUser) {
            throw new UserNotFoundException('User not found');
        }

        $data->setUser($currentUser);
        $data->setPoll($data->getOptions()->getPoll());
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        return $data;
    }
}
