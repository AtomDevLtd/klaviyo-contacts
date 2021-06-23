<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static forUser(int|string|null $id)
 */
class ContactList extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id',
        'klaviyo_id'
    ];

    /**
     * All of the models Mutators&Accessors
     * should begin from here.
     */

    /**
     * All of the models scopes methods
     * should begin from here.
     */

    /**
     * @param Builder $query
     * @param $userId
     * @return Builder
     */
    public function scopeForUser(Builder $query, $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * All of the models custom methods
     * should begin from here.
     */

    /**
     * All of the models method overwrites
     * should begin from here.
     */


    /**
     * All of the models relationships
     * should begin from here.
     */

    /**
     * @return HasMany
     */
    public function contacts() :HasMany
    {
        return $this->hasMany(Contact::class);
    }

    /**
     * @return BelongsTo
     */
    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
