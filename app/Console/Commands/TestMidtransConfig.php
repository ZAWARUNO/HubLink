<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestMidtransConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'midtrans:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Midtrans configuration and API connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Midtrans Configuration...');
        $this->newLine();

        // Check Server Key
        $serverKey = config('services.midtrans.server_key');
        if (empty($serverKey)) {
            $this->error('❌ MIDTRANS_SERVER_KEY is not set in .env file');
        } else {
            $this->info('✓ Server Key: ' . substr($serverKey, 0, 10) . '...' . substr($serverKey, -4));
        }

        // Check Client Key
        $clientKey = config('services.midtrans.client_key');
        if (empty($clientKey)) {
            $this->error('❌ MIDTRANS_CLIENT_KEY is not set in .env file');
        } else {
            $this->info('✓ Client Key: ' . substr($clientKey, 0, 10) . '...' . substr($clientKey, -4));
        }

        // Check Production Mode
        $isProduction = config('services.midtrans.is_production');
        $this->info('✓ Mode: ' . ($isProduction ? 'PRODUCTION' : 'SANDBOX'));

        // Check other settings
        $this->info('✓ Sanitized: ' . (config('services.midtrans.is_sanitized') ? 'Yes' : 'No'));
        $this->info('✓ 3DS: ' . (config('services.midtrans.is_3ds') ? 'Yes' : 'No'));

        $this->newLine();

        if (empty($serverKey) || empty($clientKey)) {
            $this->error('Please configure Midtrans API keys in your .env file:');
            $this->line('MIDTRANS_SERVER_KEY=your-server-key');
            $this->line('MIDTRANS_CLIENT_KEY=your-client-key');
            $this->newLine();
            $this->info('Get your API keys from: https://dashboard.sandbox.midtrans.com/');
            return 1;
        }

        $this->info('✅ Midtrans configuration looks good!');
        return 0;
    }
}
