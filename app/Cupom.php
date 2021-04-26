<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cupom extends Model
{
    public static function findByCode($code)
    {
        return self::where('codigo', $code)->first();
    }

    public function desconto($total)
    {
        if ($this->type == 'fixed') {
            return $this->value;
        } elseif ($this->type == 'percent') {
            return round(($this->percent_off / 100) * $total);
        } else {
            return 0;
        }
    }
}
