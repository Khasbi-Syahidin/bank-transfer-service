<?php

namespace App\Http\Controllers\Api\V1;

use App\Domain\Transfer\Enums\Currency;
use App\Domain\Transfer\Services\TransferSchedulerInterface;
use App\Http\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferScheduleRequest;
use App\Http\Resources\Domain\Transfer\TransferScheduleResource;
use Carbon\Carbon;

class TransferScheduleController extends Controller
{
    public function __construct(
        private TransferSchedulerInterface $scheduler
    ) {}

    public function show(TransferScheduleRequest $request)
    {
        try {
            $currency = Currency::from($request->validated('currency'));
            $time = Carbon::createFromFormat('H:i', $request->validated('time'));

            $result = $this->scheduler->resolve(
                $time,
                $currency
            );

            return ApiResponse::success(
                message: 'Transfer schedule resolved successfully',
                data: TransferScheduleResource::make($result)
            );

        } catch (\Exception $e) {
            return ApiResponse::error(message: $e->getMessage());
        }
    }
}
