<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static forContactList($listId)
 */
class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'title',
        'organization',
        'contact_list_id'
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
     * @param $listId
     * @return Builder
     */
    public function scopeForContactList(Builder $query, $listId): Builder
    {
        return $query->where('contact_list_id', $listId);
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function contactList(): BelongsTo
    {
        return $this->belongsTo(ContactList::class);
    }
}
