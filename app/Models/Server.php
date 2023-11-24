<?php

namespace App\Models;

use App\Helpers\ServerStats;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

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

    public function records(): HasMany
    {
        return $this->hasMany(Record::class,'server_id', 'id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'server_id', 'id');
    }

    public function meta(): HasMany
    {
        return $this->hasMany(ServerMeta::class, 'server_id', 'id');
    }

    public function getMeta(string $key): mixed
    {
        return $this->meta()->firstWhere('key', $key)?->value;
    }

    /* Attributes */
    public function getSlugAttribute(): ?string
    {
        return str($this->name)->slug('_');
    }

    public function currentRecord(): ?Record
    {
        return $this->records()->latest()->first();
    }

    public function highestRecord(): ?Record
    {
        return $this->records()->orderByDesc('players')->first();
    }

    public function getSocials(): array
    {
        $cacheTTL = app()->isProduction() ? now()->addHour() : 0;

        return Cache::remember("server.{$this->id}.socials", $cacheTTL, function () {
            $platforms = ['discord' => 'discord.gg/', 'telegram' => 'https://t.me/', 'instagram' => 'https://instagram.com/', 'website' => 'https://'];
            return $this->meta()
                ->whereIn('key', array_keys($platforms))
                ->get()
                ->keyBy('key')
                ->mapWithKeys(fn ($meta, $platform) => [
                    $platform => str($meta->value)->isUrl() ? $meta->value : ($platforms[$platform] . $meta->value)
                ])->toArray();
        });
    }

    public function fetchStats(): ?ServerStats
    {
        return ServerStats::fetch($this);
    }
}
