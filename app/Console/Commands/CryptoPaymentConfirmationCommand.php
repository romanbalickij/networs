<?php

namespace App\Console\Commands;

use App\Enums\InvoiceType;
use App\Enums\WithdrawType;
use App\Exceptions\PaymentFailedException;
use App\Models\Invoice;
use App\Services\Payments\Crypto\Ethereum;
use App\Services\Payments\Crypto\TronCrypto;
use App\Services\Payments\Gateways\CryptoPayment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CryptoPaymentConfirmationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $ribeccyApi = env('CRYPTO_API_ETHEREUM_URL');     //ERC-20
        $ropstenApi = 'https://rpc.ankr.com/eth_ropsten';     //ERC-20
        $tronApi    = env('CRYPTO_API_URL');            // TRC-20

        $invoice = Invoice::query()
            ->whereIn('crypto_type', [WithdrawType::CRYPTO_TYPE_ETHEREUM, WithdrawType::CRYPTO_TYPE_TRON])
            ->whereIn('status',      [InvoiceType::STATUS_PENDING, InvoiceType::STATUS_FAILED])
            ->get();

        $invoice->each(function ($invoice) use($ribeccyApi, $tronApi) {

            $providerType = $invoice->crypto_type;

            $cryptoType = match ($providerType) {
                WithdrawType::CRYPTO_TYPE_ETHEREUM => new Ethereum($ribeccyApi),
                WithdrawType::CRYPTO_TYPE_TRON     => new TronCrypto($tronApi),
                default => throw new PaymentFailedException('invalid type provider'),
            };

            $confirmedBlock = $providerType == WithdrawType::CRYPTO_TYPE_ETHEREUM
                ? WithdrawType::CONFIRMED_BLOCK_ETHEREUM
                : WithdrawType::CONFIRMED_BLOC_TRON;


            $crypto = new CryptoPayment($cryptoType);


            try{
                $block = $crypto->getBlock();

                $transactionBlock = $crypto->getBlockTransaction($invoice->transaction_crypto_id);

                $blockCount = $block - $transactionBlock;

                if($blockCount > $confirmedBlock) {
                    $invoice->update(['status' => InvoiceType::STATUS_SUCCESS]);
                    $invoice->owner->withdrawBalance($invoice->sum);

                }else {
                    $invoice->update(['status' => InvoiceType::STATUS_FAILED]);
                }


            }catch (PaymentFailedException $exception) {

                $invoice->update(['status' => InvoiceType::STATUS_FAILED]);
                Log::info("Cron CryptoPaymentConfirmationCommand error : ".$exception->getMessage());
            }
        });
    }
}
