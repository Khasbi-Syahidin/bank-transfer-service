<?php

namespace App\Providers;

use App\Domain\Transfer\Services\BankClientRegistry;
use App\Domain\Transfer\Services\BankService;
use App\Domain\Transfer\Services\BankServiceInterface;
use App\Domain\Transfer\Services\BankTransferExecutor;
use App\Domain\Transfer\Services\BankTransferExecutorInterface;
use App\Domain\Transfer\Services\TransferScheduler;
use App\Domain\Transfer\Services\TransferSchedulerInterface;
use App\Domain\Transfer\Services\TransferStatusService; // Added this import
use App\Domain\Transfer\Services\TransferStatusServiceInterface; // Added this import
use App\Infrastructure\Bank\Alpha\AlphaBankClient;
use App\Infrastructure\Bank\Beta\BetaBankClient;
use App\Infrastructure\Bank\Charlie\CharlieBankClient;
use App\Infrastructure\TransferLog\TransferLogRepository;
use App\Infrastructure\TransferLog\TransferLogRepositoryInterface;
use App\InfrastructureBank\BankRepository;
use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(BankClientRegistry::class, function ($app) {
            return new BankClientRegistry(
                [
                    $app->make(AlphaBankClient::class),
                    $app->make(BetaBankClient::class),
                    $app->make(CharlieBankClient::class),
                ],
                $app->make(BankRepository::class)
            );
        });

        $this->app->bind(
            BankServiceInterface::class,
            BankService::class
        );

        $this->app->bind(
            TransferSchedulerInterface::class,
            TransferScheduler::class
        );

        $this->app->bind(
            TransferLogRepositoryInterface::class,
            TransferLogRepository::class
        );

        $this->app->bind(
            TransferStatusServiceInterface::class,
            TransferStatusService::class
        );

        $this->app->bind(
            BankTransferExecutorInterface::class,
            BankTransferExecutor::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
