<?php

declare(strict_types=1);

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Vote;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use UnexpectedValueException;

class VoteOnPollProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcessorInterface $persistProcessor,
        private readonly Security $security,
        private readonly EntityManagerInterface $entityManager,
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

        $options = $data->getOptions();
        $poll = $options->getPoll();

        $hasVoted = $this->entityManager->getRepository(Vote::class)->findOneBy([
            'user' => $currentUser,
            'poll' => $poll,
        ]);

        if (null !== $hasVoted) {
            throw new UnexpectedValueException('You have already voted on this poll');
        }

        $data->setUser($currentUser);
        $data->setPoll($poll);
        $this->persistProcessor->process($data, $operation, $uriVariables, $context);

        $poll->incrementTotalVotes();
        $options->incrementVoteCount();
        $this->entityManager->flush();

        return $data;
    }
}
