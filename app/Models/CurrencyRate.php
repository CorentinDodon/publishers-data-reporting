<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CurrencyRate
 *
 * @property int $id
 * @property string $code
 * @property float $rate
 * @property Carbon $rate_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class CurrencyRate extends Model
{
    use HasFactory;

    protected $table = 'currency_rate';

    protected $casts = [
        'rate' => 'float',
        'rate_date' => 'date'
    ];

    protected $fillable = [
        'code',
        'rate',
        'rate_date'
    ];
}
