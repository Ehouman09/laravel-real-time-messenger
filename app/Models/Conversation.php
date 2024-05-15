<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'last_message_id',
        'sender_id',
        'receiver_id',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function messages() {
        return $this->hasMany(Message::class, 'conversation_id');
    }

    public static function getConversationsForSideBar(User $user) {

        $users = User::getUserExceptUser($user);
        $groups = Group::getGroupsForUser($user);

         
    }
}
