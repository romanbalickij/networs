<?php

namespace App\Http\Resources\Interaction;

use App\Enums\InteractionType;
use App\Traits\Filtratable;
use Illuminate\Http\Resources\Json\JsonResource;

class InteractionResource extends JsonResource
{
     use Filtratable;

    public function toArray($request)
    {

        return [
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_CHAT,    new ChatInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_POST,    new PostInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_MESSAGE, new MessageInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_USER,    new UserInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_FILE,    new FileInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_COMMENT, (new CommentInteractionResource($this))->except('replies')),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_DONATE ,  new DonationInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_INVOICE , new InvoiceInteractionResource($this)),


            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_SUBSCRIPTION and ($this->type == InteractionType::TYPE_SUBSCRIPTION_CANCELLATION or $this->type == InteractionType::TYPE_SUBSCRIPTION), new SubscriptionInteractionResource($this)),
            $this->mergeWhen($this->interactionable_type == InteractionType::MODEL_SUBSCRIPTION and ($this->type == InteractionType::TYPE_SUBSCRIBER_CANCELLATION or $this->type == InteractionType::TYPE_NEW_SUBSCRIBER), new SubscriberInteractionResource($this)),

        ];
    }
}
