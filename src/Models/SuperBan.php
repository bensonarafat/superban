<?php 
namespace Edenlife\Superban\Models;

use Illuminate\Database\Eloquent\Model;

class SuperBan extends Model{
    protected $fillable = [
        'client_identifier',
        'route',
        'attempts',
        'banned_until',
    ];

    public function isExpired(): bool
    {
        return now()->gt($this->banned_until);
    }
}
?>