<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'receiver_id',
        'group_id',
        'message',
    ];

    public function conversation() {
        $this->belongsTo(Conversation::class);
    }

    public function sender() {
        $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver() {
        $this->belongsTo(User::class, 'receiver_id');
    }

    public function group() {
        $this->belongsTo(Group::class, 'group_id');
    }

    public function attachements() {
        $this->hasMany(MessageAttachement::class);
    }


}
