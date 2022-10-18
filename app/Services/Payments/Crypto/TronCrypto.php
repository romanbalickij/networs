<?php


namespace App\Services\Payments\Crypto;

use App\Exceptions\PaymentFailedException;
use App\Services\Payments\CryptoInterface;
use IEXBase\TronAPI\Tron;
use Illuminate\Support\Arr;

class TronCrypto implements CryptoInterface
{
    protected $init;

    public function __construct(
        public string $api,
        // public string $walletAddress,
    )
    {
        $fullNode     = new \IEXBase\TronAPI\Provider\HttpProvider($this->api);
        $solidityNode = new \IEXBase\TronAPI\Provider\HttpProvider($this->api);
        $eventServer  = new \IEXBase\TronAPI\Provider\HttpProvider($this->api);

        $this->init = $this->init($fullNode, $solidityNode, $eventServer);

    }

    public function contractBalance(?string $address = null)
    {
        $contract = $this->init->contract(env('CRYPTO_CONTRACT_ADDRESS'));

        return $contract->balanceOf($address); // TEfn9qhYwCeduwsxNjSK7M3jkvmFbyUyjq todo main platform owner  address
    }

    public function createTransaction(string $toAddress, $amount)
    {
        return $this->sendUSDT($toAddress, $amount);
    }

    public function getTransaction(string $id) {

       return $this->init->getTransaction($id);
    }

    public function getTransactionInfo(string $id) {

        return $this->init->getTransactionInfo($id);
    }

    public function getBlockTransaction($transactionId)
    {
        $transaction = $this->getTransactionInfo($transactionId);

        return $transaction ? $transaction['blockNumber'] : 0;
    }

    public function getLatBlock()
    {
        return Arr::get($this->init->getCurrentBlock(), 'block_header.raw_data.number');
    }

    protected function sendUSDT($toAddress, $amount) {

        $this->init->setPrivateKey(env('CRYPTO_PRIVATE_TRON_KEY'));

        $contract = $this->init->contract(env('CRYPTO_CONTRACT_ADDRESS'));//todo main platfor// address

        $transaction = $contract->transfer($toAddress, $amount, env('CRYPTO_OWNER_ADDRESS'));

        return $transaction;
    }

    protected function init($fullNode, $solidityNode, $eventServer) {

        try {

            return new Tron($fullNode, $solidityNode, $eventServer);

        } catch (\IEXBase\TronAPI\Exception\TronException $e) {
            throw new PaymentFailedException($e->getMessage());
        }
    }
}
