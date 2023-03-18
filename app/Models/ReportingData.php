<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ReportingData
 *
 * @property int $id
 * @property float|null $requests
 * @property float|null $impressions
 * @property float|null $clicks
 * @property float|null $revenues
 * @property Carbon $report_date
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $publisher_id
 *
 * @property Publisher $publisher
 *
 * @package App\Models
 */
class ReportingData extends Model
{
    use HasFactory;

    protected $table = 'reporting_data';

    protected $casts = [
        'requests' => 'float',
        'impressions' => 'float',
        'clicks' => 'float',
        'revenues' => 'float',
        'report_date' => 'datetime',
        'publisher_id' => 'int'
    ];

    protected $fillable = [
        'requests',
        'impressions',
        'clicks',
        'revenues',
        'report_date',
        'publisher_id'
    ];

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(Publisher::class);
    }
}
