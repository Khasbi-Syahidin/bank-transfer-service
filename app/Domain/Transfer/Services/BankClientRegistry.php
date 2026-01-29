<?php

namespace App\Domain\Transfer\Services;

use App\InfrastructureBank\BankRepository;
use RuntimeException;

class BankClientRegistry
{
    public function __construct(
        private iterable $clients,
        private BankRepository $bankRepository
    ) {}

    public function resolvePriority(array $priorityBankCodes): BankClientInterface
    {
        foreach ($priorityBankCodes as $code) {
            foreach ($this->clients as $client) {
                if ($client->getBankCode() === $code) {
                    return $client;
                }
            }
        }

        throw new RuntimeException('No available bank client');
    }

    public function getBankCodes(): array
    {
        return array_column($this->bankRepository->all(), 'code');
    }

    public function getClient(string $bankCode): BankClientInterface
    {
        foreach ($this->clients as $client) {
            if ($client->getBankCode() === $bankCode) {
                return $client;
            }
        }

        throw new RuntimeException('Bank client not found');
    }
}
