<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'uid',
        'proof_of_payment',
        'proof_of_attendance',
        'consent_form',
    ];


    const STATUSES = [
        'rejected' => 'Rejected',
        'pending' => 'Pending',
        'reserved' => 'Reserved',
        'attended' => 'Attended',
    ];


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($eventRegistration) {
            $eventRegistration->uid = uniqid();
        });

        static::created(function ($eventRegistration) {
            Notification::make()
                ->title('Event Registration Created')
                ->success()
                ->body("Event Registration for {$eventRegistration->event->name} has been created.")
                ->sendToDatabase($eventRegistration->user)
                ->send();
        });
    }

    public function markAsPaid()
    {
        $this->status = EventRegistration::STATUSES['reserved'];
        $this->save();

        Notification::make()
            ->title('Event Registration Marked as Paid')
            ->success()
            ->body("Event Registration for {$this->event->name} has been marked as paid.")
            ->sendToDatabase($this->user)
            ->send();
    }

    public function markAsRejected()
    {
        $this->status = EventRegistration::STATUSES['rejected'];
        $this->save();

        Notification::make()
            ->title('Event Registration Marked as Rejected')
            ->success()
            ->body("Event Registration for {$this->event->name} has been marked as rejected.")
            ->sendToDatabase($this->user)
            ->send();
    }


    public function markAsAttended()
    {
        $this->status = EventRegistration::STATUSES['attended'];
        $this->save();

        Notification::make()
            ->title('Event Registration Marked as Attended')
            ->success()
            ->body("Event Registration for {$this->event->name} has been marked as attended.")
            ->sendToDatabase($this->user);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
