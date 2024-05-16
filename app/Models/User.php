<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'avatar',
        'name',
        'email',
        'email_verified_at',
        'password',
        'is_admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    /**
     * Retrieve the groups associated with the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups() {
        return $this->belongsToMany(Group::class, 'group_users', 'user_id', 'group_id');
    }

    /**
     * Get users except the specified user.
     *
     * @param User $user The user to exclude
     * @return Collection List of users except the specified user
     */
    public static function getUsersExceptUser(User $user) { 

        $userId = $user->id;

        $query = User::select([
                'users.*', 
                'messages.message as last_message',
                'messages.created_at as last_message_at'
            ])->where('users.id', '!=', $userId)
            ->when(!$user->is_admin, function ($query) {
                $quey->whereNull('users.blocked_at');
            })
            ->leftJoin('conversations', function ($query) use ($userId) {
                $query->on('conversations.sender_id', '=', 'users.id')
                    ->where('conversations.receiver_id', '=', $userId)
                    ->orWhere(function ($query) use ($userId) {
                        $query->on('conversations.receiver_id', '=', 'users.id')
                            ->where('conversations.sender_id', '=', $userId);
                    });
            })
            ->leftJoin('messages', 'messages.id', '=',  'conversations.last_message_id')
            ->orderByRaw('IFNULL(users.blocked_at, 1)')
            ->orderBy('messages.created_at', 'desc')
            ->orderBy('users.name');

            // dd($query->toSql());
            return $query->get();

    }

    /**
     * A method to convert the User object to an array representation for conversation purposes.
     *
     * @return array
     */
    public function toConversationArray() {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'is_admin' => $this->is_admin,
            'is_group' => false,
            'is_user' => true,
            'blocked_at' => $this->blocked_at,
            'last_message' => $this->last_message,
            'last_message_at' => $this->last_message_at,
        ];
    }

    
}
