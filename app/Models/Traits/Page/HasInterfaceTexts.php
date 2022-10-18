<?php

namespace App\Models\Traits\Page;

use App\Models\InterfaceText;

trait HasInterfaceTexts
{

    public function interfaceTexts() {

        return $this->hasMany(InterfaceText::class);
    }

    public function transformInterfaceTexts() {

        if(!$this->interfaceTexts) {
            return  collect([]);
        }

        return $this->interfaceTexts->count()
            ? $this->interfaceTexts->map(fn($value) => ['key' => $value->key, 'text' => $value->text])->toArray()
            : collect(['key' => "footer", 'text' => null])->toArray();
    }
}
