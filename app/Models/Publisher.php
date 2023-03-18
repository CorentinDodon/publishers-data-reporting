<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Publisher
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|ReportingData[] $reporting_data
 *
 * @package App\Models
 */
class Publisher extends Model
{
    use HasFactory;

    protected $table = 'publisher';

    protected $fillable = [
        'name'
    ];

    public function reportingData(): HasMany
    {
        return $this->hasMany(ReportingData::class);
    }
}
