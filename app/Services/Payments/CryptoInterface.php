<?php


namespace App\Services\Payments;


interface CryptoInterface
{

    public function contractBalance(string|null $address = null);

    public function createTransaction(string $toAddress, $amount);

    public function getTransaction(string $id);

    public function getTransactionInfo(string $id);

    public function getLatBlock();

    public function getBlockTransaction($transactionId);
}
