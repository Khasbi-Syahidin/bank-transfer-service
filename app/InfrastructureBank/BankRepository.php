<?php

namespace App\InfrastructureBank;

class BankRepository
{
    public function all(): array
    {
        return [
            [
                'code' => 'A01',
                'name' => 'Bank Alpha',
                'connected' => true,
            ],
            [
                'code' => 'B02',
                'name' => 'Bank Beta',
                'connected' => true,
            ],
            [
                'code' => 'C03',
                'name' => 'Bank Charlie',
                'connected' => true,
            ],
            [
                'code' => 'D04',
                'name' => 'Bank Delta',
                'connected' => false,
            ],
            [
                'code' => 'E05',
                'name' => 'Bank Echo',
                'connected' => false,
            ],
            [
                'code' => 'F06',
                'name' => 'Bank Fanta',
                'connected' => false,
            ],
        ];
    }
}
