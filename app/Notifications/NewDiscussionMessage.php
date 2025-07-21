<?php

namespace App\Notifications;

use App\Models\Thesis;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewDiscussionMessage extends Notification implements ShouldBroadcast
{
    use Queueable;

    public Thesis $thesis;
    public User $sender;

    /**
     * Buat instance notifikasi baru.
     */
    public function __construct(Thesis $thesis, User $sender)
    {
        $this->thesis = $thesis;
        $this->sender = $sender;
    }

    /**
     * Tentukan channel pengiriman notifikasi.
     */
    public function via(object $notifiable): array
    {
        // Kirim ke database DAN broadcast secara real-time
        return ['database', 'broadcast'];
    }

    /**
     * Format notifikasi yang akan disimpan di kolom 'data' pada database.
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'message' => "Pesan baru dari {$this->sender->name} di diskusi skripsi Anda.",
            'url' => route('thesis.detail', $this->thesis->id), // Sesuaikan dengan route Anda
            'sender_id' => $this->sender->id
        ];
    }

    /**
     * Format notifikasi yang akan dikirim secara real-time ke browser.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'message' => "Pesan baru dari {$this->sender->name} di diskusi skripsi Anda.",
            'url' => route('thesis.detail', $this->thesis->id),
        ]);
    }

    /**
     * Tentukan nama event untuk broadcast agar mudah didengarkan di frontend.
     */
    public function broadcastAs(): string
    {
        return 'new-notification';
    }
}