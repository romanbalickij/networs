<?php


namespace App\Services\Actions;


use App\Models\InterfaceText;

class UpdateInterfaceTextAction
{

    public function execute(InterfaceText $interfaceText, ?array $payload, ?array $images){

        collect($images)->each(
            fn($image) => $interfaceText->addImages($image)
        );

        return tap($interfaceText)->update($payload);
    }
}
