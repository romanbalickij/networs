<?php


namespace App\Services\Payments\Crypto;


use App\Exceptions\PaymentFailedException;
use App\Services\Payments\CryptoInterface;
use Web3\Contract;
use Web3\Formatters\BigNumberFormatter;
use Web3\Utils;
use Web3\Web3;
use Web3p\EthereumTx\Transaction;

class Ethereum implements CryptoInterface
{

    const DECIMAL_USDT = 6;

    public $chainIds = [
        'mainnet' => 1,
        'ropsten' => 3,
        'rinkeby' => 4,
        'goerli' => 5,
        'kovan' => 42,
        'mumbai' => 80001,
        'polygon_testnet' => 80001,
        'polygon' => 137,
        'matic' => 137,
    ];

    protected $init;

    protected $abi;

    public function __construct(
        public string $api,
    )
    {
        $this->init = new Web3($api);
        $this->abi  = file_get_contents(public_path('Erc777TokenAbiArray.json'));
    }

    public function contractBalance(?string $address = null) {
        try{
            $total = 0;

            $contractAddress = env('CRYPTO_CONTRACT_ETHEREUM_ADDRESS');

            $contract = new Contract($this->init->provider, $this->abi);

            $contract->at($contractAddress)->call('balanceOf', $address, [
                'from' => $address
            ], function ($err, $results) use(&$total) {

                if ($err !== null) {throw $err;}

                if (isset($results)) {
                    foreach ($results as &$result) {

                        $bn    = Utils::toBn($result);
                        $total = $bn->toString();
                    }
                }
            });

            return $total;
        }catch (\Exception $exception){
            throw new PaymentFailedException($exception->getMessage());
        }

    }

    public function createTransaction(string $toAddress, $amount)
    {
        try{
            $sum = intval($amount) * (10 ** self::DECIMAL_USDT);

            $contractAddress = env('CRYPTO_CONTRACT_ETHEREUM_ADDRESS');
            $fromAccount     = env('CRYPTO_OWNER_ETHEREUM_ADDRESS');

            $rawTransactionData = $this->prepareTransfer($contractAddress, $toAddress, $sum);
            $transactionCount   = $this->getTransactionCount($fromAccount);
            $gasPrice           = $this->getGasPrice();


            $transactionParams = [
                'nonce' => "0x" . dechex($transactionCount->toString()),
                'from'  => $fromAccount,
                'to'    =>  $contractAddress,
             //   'gas'   =>  '0x' . dechex(8000000),
                'value' => '0x0',
                'data'  => $rawTransactionData
            ];


            $estimatedGas = $this->estimateGas($transactionParams);


            $gasPriceMultiplied =  $estimatedGas->toString(); // hexdec(dechex($estimatedGas->toString())) * intval(50000);

            $transactionParams['gas']      = '0x' . dechex($gasPriceMultiplied);
            $transactionParams['gasPrice'] = '0x' . dechex($gasPrice);
            $transactionParams['chainId']  = $this->chainIds['rinkeby'];

            $signedTx = $this->signTransaction($transactionParams);

            $txHash = null;

            $this->init->eth->sendRawTransaction($signedTx, function ($err, $txResult) use (&$txHash) {

                if ($err !== null) {throw $err;}

                $txHash = $txResult;
            });

            return $txHash;


        }catch (\Exception $exception) {
            throw new PaymentFailedException($exception->getMessage());
        }
    }

    public function getTransaction(string $id) {

        try{
            $result = collect([]);

            $this->init->eth->getTransactionByHash($id, function ($err, $transaction) use(&$result) {

                if ($err !== null) {throw $err;}

                $result = $transaction;
            });

            return $result;
        }catch (\Exception $exception) {
            throw new PaymentFailedException($exception->getMessage());
        }
    }

    public function getTransactionInfo(string $id)
    {
        try{
            $result = collect([]);

            $this->init->eth->getTransactionReceipt($id, function ($err, $transaction) use(&$result) {

                if ($err !== null) {throw $err;}

                $result = $transaction;
            });

            return $result;
        }catch (\Exception $exception ) {
            throw new PaymentFailedException($exception->getMessage());
        }
    }

    public function getLatBlock()
    {
        try {
            $block = 0;
            $contract = new Contract($this->init->provider, $this->abi);

            $contract->at(env('CRYPTO_CONTRACT_ETHEREUM_ADDRESS'))->getEth()->blockNumber(function ($err, $result) use(&$block) {

                if ($err !== null) {throw $err;}

                $block = $result->toString();

            });
            return $block;
        }catch (\Exception $exception) {
            throw new PaymentFailedException($exception->getMessage());
        }
    }

    public function getBlockTransaction($transactionId)
    {
        $transaction = $this->getTransactionInfo($transactionId);

        return BigNumberFormatter::format($transaction->blockNumber)->toString();
    }

    protected function getTransactionCount($accountAddress) {

        $transactionCount = null;

        $this->init->eth->getTransactionCount($accountAddress, function ($err, $transactionCountResult) use(&$transactionCount) {

            if ($err !== null) {throw $err;}

            $transactionCount = $transactionCountResult;
        });

        return $transactionCount;
    }

    protected function estimateGas($transactionParams) {

        $estimatedGas = null;

        $this->init->eth->estimateGas($transactionParams, function ($err, $gas) use (&$estimatedGas) {

            if ($err !== null) {throw $err;}

            $estimatedGas = $gas;
        });

        return $estimatedGas;
    }

    protected function getGasPrice() {

        $price =0;

        $this->init->eth->gasPrice(function ($err, $result) use(&$price){

            $price = $result->toString();
        });

        return $price;
    }

    protected function signTransaction($transactionParams) {

        $tx = new Transaction($transactionParams);

        return '0x' . $tx->sign(env('CRYPTO_ETHEREUM_PRIVATE_KEY'));
    }

    protected function prepareTransfer($contractAddress, $toAddress, $amount) {

        $abi = file_get_contents(

            public_path('Erc777TokenAbiArray.json')
        );

        $contract = new Contract($this->init->provider, $abi);

        $rawTransactionData = '0x' . $contract->at($contractAddress)->getData('transfer', $toAddress, $amount);

        return $rawTransactionData;
    }

}
