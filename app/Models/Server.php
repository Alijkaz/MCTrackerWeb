<?php

namespace App\Models;

use App\Helpers\ServerStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static active()
 */
class Server extends Model
{
    use HasFactory;

    protected $guarded = [];

    /* Scopes */

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    public function scopeVip(Builder $query): void
    {
        $query->where('is_vip', true);
    }

    public function scopeOnline(Builder $query): void
    {
        $query->where('up_from','>' , '0');
    }

    public function scopeOffline(Builder $query): void
    {
        $query->where('up_from','<' , '0');
    }

    /* Relations */

    public function records()
    {
        return $this->hasMany(Record::class,'server_id', 'id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'server_id', 'id');
    }

    public function currentRecord(): ?Record
    {
        return $this->records()->latest()->first();
    }

    public function fetchStats(): ?ServerStats
    {
        return ServerStats::fetch($this);
    }
}
