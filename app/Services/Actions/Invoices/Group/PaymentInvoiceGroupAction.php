<?php


namespace App\Services\Actions\Invoices\Group;


use App\Enums\HistoryType;
use App\Enums\InteractionType;
use App\Enums\InvoiceType;
use App\Notifications\NotificationHelper;
use App\Services\Actions\Invoices\GenerateInvoiceAction;
use App\Services\DataTransferObjects\InvoiceDto;
use App\Services\Payments\PaymentHandler\PaymentOperationInterface;
use App\Traits\VatCalculator;
use Illuminate\Support\Facades\Auth;

class PaymentInvoiceGroupAction
{

    use VatCalculator, NotificationHelper;

    public function __invoke(PaymentOperationInterface $operation) {

        $customer = $operation->customer();

        $sum = $this->sum($operation->getSum());

        $creatorSum = $sum->addCommissionPlatform()->getTotalIncludingCommission();

        $referralSum = $customer->partner() ? $sum->referralSum() : 0;

        $platformSum = $sum->platformSum();


        $this->transaction($operation, $creatorSum);

        if(!$operation->getSum()) {
            return;
        }

        // my
        $this->generateInvoice([
            'purpose_string' => $operation->purpose,
            'commission_sum' => config('invoices.platform'),
            'direction'      => InvoiceType::DIRECTION_CREDIT,
            'type_received'  => InvoiceType::MAKE_PAYMENT,
            'user_id'        => $customer->id(),
            'creator_id'     => Auth::id(),
            'sum'            => $operation->getSum(),
            'type'           => $operation->operationType(),
        ], true);


        //creator
        $this->generateInvoice([
            'purpose_string' => $operation->purpose,
            'commission_sum' => config('invoices.platform'),
            'direction'      => InvoiceType::DIRECTION_DEBIT,
            'type_received'  => InvoiceType::RECEIVED_PAYMENT,
            'user_id'        => Auth::id(),
            'creator_id'     => $customer->id(),
            'sum'            => $creatorSum,
            'type'           => $operation->operationType(),
        ],true);

        //platform
        $this->generateInvoice([
            'purpose_string' => $operation->purpose,
            'commission_sum' => config('invoices.platform'),
            'direction'      => InvoiceType::DIRECTION_DEBIT,
            'type_received'  => InvoiceType::RECEIVE_PLATFORM,
            'user_id'        => Auth::id(),
            'creator_id'     => $customer->id(),
            'sum'            => $platformSum,
            'type'           => $operation->operationType(),
        ], false);

        //referral
        if($referralUser = $customer->partner()) {

            $referralUser->addToBalance($referralSum);

            $operation->paymentStatistics($referralUser->id, HistoryType::REFERRAL_LINK, $customer->actualReferralId(), $referralSum);

            $this->generateInvoice([
                'purpose_string' => $operation->purpose,
                'commission_sum' => config('invoices.referral'),
                'direction'      => InvoiceType::DIRECTION_DEBIT,
                'type_received'  => InvoiceType::RECEIVED_REFERRAL,
                'user_id'        => Auth::id(),
                'creator_id'     => $referralUser->id,
                'sum'            => $referralSum,
                'type'           => $operation->referralOperationType(),
            ], true);
        }

        $customer->payload()->addToBalance($creatorSum);
    }


    private function generateInvoice($params, bool $pushToInteractions) {

        $invoice = app(GenerateInvoiceAction::class)(InvoiceDto::fromArray($params));


        if($pushToInteractions) {
            $invoice->pushToInteractions(InteractionType::TYPE_INVOICE_BILLING, $invoice->owner);

            $this->newInvoiceNotify($invoice);
        }

        return $invoice;
    }

    private function transaction($operation, $sum) :void {

        if($operation->storeTransaction) {

            $operation->addToTransaction($sum);
        }
    }

}
