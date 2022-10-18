<?php


namespace App\Services\Payments\PaymentHandler;


use App\Exceptions\PaymentFailedException;
use App\Models\PaymentMethod;
use App\Models\User;
use App\Notifications\PaymentFailedMailNotification;
use App\Services\Actions\Invoices\Group\PaymentInvoiceGroupAction;
use App\Services\Payments\PaymentGateway;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentHandler implements PaymentHandlerInterface
{

    public function __construct(

        public ?PaymentMethod $card

    )
    {
        $this->card = $card;
    }

    public function handler(PaymentOperationInterface $operation)
    {

        try {
            DB::beginTransaction();

            if($operation->getSum() > 0) {
                app(PaymentGateway::class)
                    ->withUser(user())
                    ->getCustomer()
                    ->charge($this->card, $operation->getSum());
            }

            app(PaymentInvoiceGroupAction::class)($operation);

            DB::commit();
        }catch(\Exception $e) {

            DB::rollback();

            $owner = $operation->customer()->payload();

            if($operation->getSum() > 0) {

                $owner->notify((new PaymentFailedMailNotification($this->card, $operation->getSum(), $operation->historyType, $owner))->locale($owner->locale));

            }
            throw new PaymentFailedException($e->getMessage());
        }
    }


}
