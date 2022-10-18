<?php

namespace App\Http\Controllers\Api;

use App\Enums\InteractionType;
use App\Enums\InvoiceType;
use App\Enums\WithdrawType;
use App\Exceptions\PaymentFailedException;
use App\Http\Controllers\BaseController;
use App\Http\Requests\User\WithdrawalBalanceRequest;
use App\Notifications\ConfirmedWithdrawalNotification;
use App\Services\Actions\Invoices\GenerateInvoiceAction;
use App\Services\Actions\WithdrawalBalanceAction;
use App\Services\Actions\WithdrawalBalanceCryptoAction;
use App\Services\DataTransferObjects\InvoiceDto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalPaymentBalanceController extends BaseController
{

    public function withdrawal(WithdrawalBalanceRequest $request, WithdrawalBalanceAction $withdrawalBalanceAction, WithdrawalBalanceCryptoAction $withdrawalBalanceCryptoAction) {

        try {
            DB::beginTransaction();

            $request->payment_type == WithdrawType::CREDIT_CARD
                ? $withdrawalBalanceAction->handler($request->sum)
                : $transactionId = $withdrawalBalanceCryptoAction->handler($request->sum, $request->crypto_type, $request->crypto_address);


          $invoice = app(GenerateInvoiceAction::class)(InvoiceDto::fromArray([
              'purpose_string' => 'cash withdrawal',
              'commission_sum' => 0,
              'direction'      => InvoiceType::DIRECTION_CREDIT,
              'type_received'  => InvoiceType::MAKE_WITHDRAWAL,
              'creator_id'     => Auth::id(),
              'sum'            => $request->sum,
              'type'           => InvoiceType::WITHDRAWAL,
              'transaction_crypto_id' => $transactionId,
              'crypto_type'    => $request->crypto_type,
              'status'         => $request->payment_type == WithdrawType::CREDIT_CARD ? InvoiceType::STATUS_SUCCESS : InvoiceType::STATUS_PENDING
            ]));

            $invoice->pushToInteractions(InteractionType::TYPE_INVOICE_CREATION, $invoice->owner);

            //TODO ADD SEND INVOICE TO EMAIL
            DB::commit();

            return $this->respondWithSuccess(1);

        }catch (PaymentFailedException $exception) {
            DB::rollback();

            return $this->respondError($exception->getMessage());
        }

    }

    public function confirmed() {

        $user = user();

        $user->notify(new ConfirmedWithdrawalNotification($user->generateConfirmedCode()));

        return $this->respondOk('Send Ok');
    }
}
