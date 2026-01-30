<?php

namespace Tests\Feature;

use App\Domain\Transfer\Enums\Currency;
use App\Domain\Transfer\Services\BankClientInterface;
use App\Domain\Transfer\Services\BankClientRegistry;
use App\Domain\Transfer\Services\BankTransferResponse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class TransferTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function generateTransactionId(): string
    {
        return 'TRF-'.date('Y').'-TC-'.str_pad(rand(1, 999999999), 9, '0', STR_PAD_LEFT);
    }

    /** @test */
    public function it_can_successfully_perform_an_inhouse_transfer()
    {
        // NOTE: Mock BankClientRegistry to return a successful AlphaBankClient
        $alphaBankClientMock = Mockery::mock(BankClientInterface::class);
        $alphaBankClientMock->shouldReceive('getBankCode')->andReturn('A01');
        $alphaBankClientMock->shouldReceive('transfer')->andReturn(
            new BankTransferResponse(success: true, message: 'Transfer success via Bank Alpha')
        );

        $bankClientRegistryMock = Mockery::mock(BankClientRegistry::class);
        $bankClientRegistryMock->shouldReceive('getBankCodes')->andReturn(['A01', 'B02', 'C03']);
        $bankClientRegistryMock->shouldReceive('getClient')->with('A01')->andReturn($alphaBankClientMock);
        $bankClientRegistryMock->shouldReceive('getClient')->with('B02')->andReturn($alphaBankClientMock);
        $bankClientRegistryMock->shouldReceive('getClient')->with('C03')->andReturn($alphaBankClientMock);

        $this->app->instance(BankClientRegistry::class, $bankClientRegistryMock);

        $payload = [
            'transfer_id' => $this->generateTransactionId(),
            'source_bank_code' => 'A01',
            'source_account' => '123456789',
            'destination_bank_code' => 'A01',
            'destination_account' => '987654321',
            'amount' => 100.00,
            'currency' => Currency::IDR,
            'transfer_time' => now()->format('H:i'),
            'description' => 'Inhouse transfer test',
        ];

        $response = $this->postJson('/api/transfers/execute', $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'code' => 20000,
                'message' => 'Transfer success via Bank Alpha',
            ])
            ->assertJsonPath('data.status_transfer', 'SUCCESS')
            ->assertJsonPath('data.bank_code', 'A01');
    }

    /** @test */
    public function it_can_perform_an_online_transfer_with_fallback()
    {
        $bankClientRegistryMock = Mockery::mock(BankClientRegistry::class);
        $bankClientRegistryMock->shouldReceive('getBankCodes')->andReturn(['A01', 'B02', 'C03']);
        $this->app->instance(BankClientRegistry::class, $bankClientRegistryMock);

        // NOTE simulate successful fallback
        $mockedTransferResult = new \App\Domain\Transfer\DTO\TransferResult(
            success: true,
            status: \App\Domain\Transfer\Enums\TransferStatus::SUCCESS,
            bankCode: 'B02',
            message: 'Beta Bank succeeded',
            scheduledAt: null
        );

        $bankTransferExecutorMock = Mockery::mock(\App\Domain\Transfer\Services\BankTransferExecutorInterface::class);
        $bankTransferExecutorMock->shouldReceive('executeTransferWithFallback')
            ->once()
            ->andReturn($mockedTransferResult);

        $this->app->instance(\App\Domain\Transfer\Services\BankTransferExecutorInterface::class, $bankTransferExecutorMock);

        $payload = [
            'transfer_id' => $this->generateTransactionId(),
            'source_bank_code' => 'A01',
            'source_account' => '123456789',
            'destination_bank_code' => 'C03',
            'destination_account' => '987654321',
            'amount' => 100.00,
            'currency' => Currency::IDR,
            'transfer_time' => '05:00',
            'description' => 'Online transfer with fallback test',
        ];

        $response = $this->postJson('/api/transfers/execute', $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'code' => 20000,
                'message' => 'Beta Bank succeeded',
            ])
            ->assertJsonPath('data.status_transfer', 'SUCCESS')
            ->assertJsonPath('data.bank_code', 'B02');
    }

    /** @test */
    public function it_defers_a_usd_transfer_at_18_00()
    {
        $bankClientRegistryMock = Mockery::mock(BankClientRegistry::class);
        $bankClientRegistryMock->shouldReceive('getBankCodes')->andReturn(['A01', 'B02', 'C03']);
        $this->app->instance(BankClientRegistry::class, $bankClientRegistryMock);

        $payload = [
            'transfer_id' => $this->generateTransactionId(),
            'source_bank_code' => 'A01',
            'source_account' => '123456789',
            'destination_bank_code' => 'B02',
            'destination_account' => '987654321',
            'amount' => 100.00,
            'currency' => Currency::USD,
            'transfer_time' => '18:00',
            'description' => 'Deferred USD transfer test',
        ];

        $response = $this->postJson('/api/transfers/execute', $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'code' => 20000,
                'message' => 'Transfer is scheduled',
            ])
            ->assertJsonPath('data.status_transfer', 'PENDING')
            ->assertJsonPath('data.scheduled_at', '22:00');
    }

    /** @test */
    public function it_defers_a_transfer_outside_operational_hours_at_02_00()
    {
        $bankClientRegistryMock = Mockery::mock(BankClientRegistry::class);
        $bankClientRegistryMock->shouldReceive('getBankCodes')->andReturn(['A01', 'B02', 'C03']);
        $this->app->instance(BankClientRegistry::class, $bankClientRegistryMock);

        $payload = [
            'transfer_id' => $this->generateTransactionId(),
            'source_bank_code' => 'A01',
            'source_account' => '123456789',
            'destination_bank_code' => 'B02',
            'destination_account' => '987654321',
            'amount' => 100.00,
            'currency' => Currency::IDR,
            'transfer_time' => '02:00',
            'description' => 'Deferred transfer outside operational hours test',
        ];

        $response = $this->postJson('/api/transfers/execute', $payload);

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'code' => 20000,
                'message' => 'Transfer is scheduled',
            ])
            ->assertJsonPath('data.status_transfer', 'PENDING')
            ->assertJsonPath('data.scheduled_at', '04:00');
    }
}
