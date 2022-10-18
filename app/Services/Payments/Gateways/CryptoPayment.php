<?php


namespace App\Services\Payments\Gateways;

use App\Services\Payments\CryptoInterface;

class CryptoPayment
{

    public function __construct(public CryptoInterface $crypto){}

    public function contractBalance(?string $address = null)
    {
            $balance = $this->crypto->contractBalance($address);

            return $balance;
    }

    public function createTransaction(string $toAddress, $amount) {

        return $this->crypto->createTransaction($toAddress, $amount);
    }

    public function getTransaction(string $id) {

       return $this->crypto->getTransaction($id);
    }

    public function getTransactionInfo(string $id) {

        return $this->crypto->getTransactionInfo($id);
    }

    public function getBlock() {

        return $this->crypto->getLatBlock();
    }

    public function getBlockTransaction(string $transactionId) {

        return $this->crypto->getBlockTransaction($transactionId);
    }
}

//https://developers.tron.network/docs/trc20-contract-interaction#balanceof
//https://developers.tron.network/reference/triggerconstantcontract
//https://github.com/iexbase/tron-api/tree/master/examples

//https://developers.tron.network/reference/createtransaction
//https://shasta.tronscan.org/?_ga=2.172821283.1266789018.1662474170-46756802.1661880568#/

//https://github.com/tronprotocol/tronweb/blob/master/src/lib/trx.js

//https://nile.tronscan.org/?_ga=2.209587090.1266789018.1662474170-46756802.1661880568#/contract/TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj/code
//https://nileex.io/join/getJoinPage
//https://nileex.io/join/getJoinPage
//https://bestofphp.com/repo/web3-php-web3
//https://developers.tron.network/reference/api-key#note
//https://nile.tronscan.org/?_ga=2.172821283.1266789018.1662474170-46756802.1661880568#/contract/TXLAQ63Xg1NAzckPwKHvzw7CSEmLMEqcdj/code
//https://nile.tronscan.org/?_ga=2.172821283.1266789018.1662474170-46756802.1661880568#/address/TEfn9qhYwCeduwsxNjSK7M3jkvmFbyUyjq
//



//enterous

//https://rinkeby.etherscan.io/address/0xde2bc1d884f9e91f7c8961bb1b6b6635576ae634
//https://faucets.chain.link/rinkeby
//https://faucets.chain.link/rinkeby
//https://www.ankr.com/rpc/eth/
//https://github.com/web3p/web3.php
//https://www.ankr.com/docs/build-blockchain/chains/v2/ethereum/how-to/connect-ethereum/
//https://rinkeby.etherscan.io/tx/0x45139222e3472c210d2d62da7a8d15e687d97332d4d5ce89e1284b0227845906
//https://github.com/web3-php/web3
//https://github.com/benrobot/web3.php_send_tokens_example/blob/main/public/sendTokens.php
//https://rinkeby.etherscan.io/token/0x01be23585060835e02b77ef475b0cc51aa1e0709?a=0xde2bc1d884f9e91f7c8961bb1b6b6635576ae634#code
//https://docs.debio.network/getting-started/how-to-get-free-testnet-token-on-rinkeby

//test
//https://rinkebyfaucet.com/
//https://docs.degate.com/testnet/degate-testnet-get-free-testnet-tokens-on-rinkeby
//https://teller.gitbook.io/teller-1/testing-guide/getting-testnet-tokens-rinkeby
//https://faucets.chain.link/rinkeby
//https://app.compound.finance/#

//test token send tusdt https://bybit-exchange.github.io/erc20-faucet/
