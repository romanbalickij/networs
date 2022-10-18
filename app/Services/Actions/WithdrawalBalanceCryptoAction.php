<?php


namespace App\Services\Actions;


use App\Enums\WithdrawType;
use App\Exceptions\PaymentFailedException;
use App\Services\Payments\Crypto\Ethereum;
use App\Services\Payments\Crypto\TronCrypto;

class WithdrawalBalanceCryptoAction
{
    const DECIMAL_USDT = 6;

    public function handler($sum, $type, $address) {


        $ribeccyApi = env('CRYPTO_API_ETHEREUM_URL');     //ERC-20
        $ropstenApi = 'https://rpc.ankr.com/eth_ropsten';     //ERC-20
        $tronApi    = env('CRYPTO_API_URL');            // TRC-20

        $platformWalletAddress = $type == WithdrawType::CRYPTO_TYPE_ETHEREUM
            ? env('CRYPTO_OWNER_ETHEREUM_ADDRESS')
            : env('CRYPTO_OWNER_ADDRESS');

        $crypto = match ($type) {
            WithdrawType::CRYPTO_TYPE_ETHEREUM => new Ethereum($ribeccyApi),
            WithdrawType::CRYPTO_TYPE_TRON     => new TronCrypto($tronApi),
            default => throw new PaymentFailedException('invalid type provider'),
        };

        $sumConvertToUSDT = intval($sum) * (10 ** self::DECIMAL_USDT);

        if($crypto->contractBalance($platformWalletAddress) < $sumConvertToUSDT) {
            throw new PaymentFailedException('The amount of excess balance :-(');
        }


        try{
            $transaction = $crypto->createTransaction($address, $sum);

            return $transaction;

        }catch (PaymentFailedException $exception) {
            throw new PaymentFailedException($exception->getMessage());
        }

    }
}
