<?php


namespace App\Services\DataTransferObjects;

use Spatie\DataTransferObject\DataTransferObject;


class InvoiceDto extends DataTransferObject
{
    /** @var integer|null */
    public $user_id;

    /** @var integer|null */
    public $creator_id;

    /** @var integer|null|float|string */
    public $sum;

    /** @var integer|null */
    public $commission_sum;

    /** @var string|null */
    public $type;

    /** @var string|null */
    public $direction;

    /** @var string|null */
    public $purpose_string;

    /** @var string|null */
    public $type_received;

    /** @var string|null|integer */
    public $transaction_crypto_id;

    /** @var string|null */
    public $crypto_type;

    /** @var string|null */
    public $status;


    public static function fromArray(array $data) {

        return new self([
            'user_id'        => $data['user_id'] ?? null,
            'creator_id'     => $data['creator_id'],
            'sum'            => $data['sum'],
            'commission_sum' => $data['commission_sum'],
            'type'           => $data['type'],
            'direction'      => $data['direction'],
            'purpose_string' => $data['purpose_string'],
            'type_received'  => $data['type_received'],
            'transaction_crypto_id'  => $data['transaction_crypto_id'] ?? NULL,
            'crypto_type'    => $data['crypto_type'] ?? NULL,
            'status'         => $data['status'] ?? NULL,
        ]);
    }
}
