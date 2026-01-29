<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Transfer\DTO\TransferRequest as TransferRequestDTO;
use App\Domain\Transfer\Enums\Currency;
use App\Domain\Transfer\Services\BankServiceInterface;
use App\Domain\Transfer\Services\TransferStatusServiceInterface;
use App\Domain\Transfer\ValueObjects\BankCode;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\TransferStatusRequest;
use App\Http\Resources\Domain\Transfer\TransferResource;
use DateTimeImmutable;

class TransferController extends Controller
{
    public function __construct(
        private BankServiceInterface $bankService,
        private TransferStatusServiceInterface $statusService
    ) {}

    public function execute(TransferRequest $request)
    {
        try {
            $dto = new TransferRequestDTO(
                transfer_id: $request->transfer_id,
                source_bank_code: new BankCode($request->source_bank_code),
                source_account: $request->source_account,
                destination_bank_code: new BankCode($request->destination_bank_code),
                destination_account: $request->destination_account,
                description: $request->description,
                amount: (float) $request->amount,
                currency: Currency::from($request->currency),
                transfer_time: new DateTimeImmutable($request->transfer_time)
            );

            $result = $this->bankService->sendMoney($dto);

            if ($result->success) {
                return ApiResponse::success(message: $result->message, data: TransferResource::make($result));
            } else {
                return ApiResponse::badRequest(message: $result->message, additional: ['error' => TransferResource::make($result)]);
            }

        } catch (\Exception $e) {
            return ApiResponse::error(message: $e->getMessage());
        }
    }

    public function check(TransferStatusRequest $request)
    {
        try {
            $result = $this->statusService->check($request->transfer_id);

            return ApiResponse::success(message: 'Transfer status', data: $result->toArray());
        } catch (\Exception $e) {
            return ApiResponse::error(message: $e->getMessage());
        }
    }
}
