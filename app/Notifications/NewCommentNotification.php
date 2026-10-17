<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Comment $comment,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $blog = $this->comment->blog;
        $moderationUrl = url('/admin/comments?status=pending');
        $blogUrl = $blog ? route('blog.show', $blog->slug).'#comment-'.$this->comment->id : url('/');

        return (new MailMessage)
            ->subject('Komentar baru menunggu moderasi')
            ->greeting('Halo Admin')
            ->line('Ada komentar baru yang menunggu moderasi:')
            ->line('Dari: **'.$this->comment->author_name.'** ('.$this->comment->author_email.')')
            ->line('Pada artikel: **'.($blog?->title ?? 'Unknown').'**')
            ->line('Isi: '.Str::limit($this->comment->content, 200))
            ->action('Buka Moderasi', $moderationUrl)
            ->line('Atau lihat di artikel: '.$blogUrl);
    }
}
