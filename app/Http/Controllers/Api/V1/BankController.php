<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\InfrastructureBank\BankRepository;

class BankController extends Controller
{
    public function __construct(
        private BankRepository $bankRepository
    ) {}

    public function index()
    {
        try {
            return ApiResponse::success(
                message: 'Banks retrieved successfully',
                data: $this->bankRepository->all()
            );
        } catch (\Exception $e) {
            return ApiResponse::error(message: $e->getMessage());
        }
    }
}
