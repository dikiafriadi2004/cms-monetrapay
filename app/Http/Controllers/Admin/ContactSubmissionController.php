<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\ContactSubmission;
use App\Models\Setting;
use App\Services\MailConfigService;
use App\Traits\RespondsWithError;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactSubmissionController extends Controller
{
    use RespondsWithError;

    /**
     * List all contact submissions.
     */
    public function index(Request $request)
    {
        $query = ContactSubmission::query();

        if ($request->filled('filter')) {
            if ($request->filter === 'unread') {
                $query->where('is_read', false);
            } elseif ($request->filter === 'read') {
                $query->where('is_read', true);
            }
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('phone', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%")
                    ->orWhere('city', 'like', "%{$term}%");
            });
        }

        $submissions = $query->orderByDesc('created_at')->paginate(20)->withQueryString();
        $unreadCount = ContactSubmission::where('is_read', false)->count();

        return view('admin.contacts.index', compact('submissions', 'unreadCount'));
    }

    /**
     * Show a single submission and mark as read.
     */
    public function show(ContactSubmission $contact)
    {
        if (! $contact->is_read) {
            $contact->update(['is_read' => true]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    /**
     * Toggle read state.
     */
    public function toggleRead(ContactSubmission $contact)
    {
        $contact->update(['is_read' => ! $contact->is_read]);

        return back()->with('success', $contact->is_read ? 'Ditandai sudah dibaca.' : 'Ditandai belum dibaca.');
    }

    /**
     * Reply to a contact submission via email.
     */
    public function reply(Request $request, ContactSubmission $contact)
    {
        if (empty($contact->email)) {
            return $this->respondError('Kontak ini tidak memiliki alamat email untuk dibalas.');
        }

        $validated = $request->validate([
            'reply_message' => ['required', 'string', 'min:5', 'max:5000'],
        ], [
            'reply_message.required' => 'Pesan balasan wajib diisi.',
            'reply_message.min' => 'Pesan balasan terlalu pendek.',
        ]);

        // Apply mail config from DB
        app(MailConfigService::class)->apply();

        try {
            Mail::send(
                'emails.contact-reply',
                [
                    'contact' => $contact,
                    'replyMessage' => $validated['reply_message'],
                    'adminName' => auth()->user()->name,
                ],
                function ($mail) use ($contact) {
                    $siteName = Setting::get('site_name', 'general') ?? config('app.name');
                    $mail->to($contact->email, $contact->name)
                        ->subject('Re: Pendaftaran Anda di '.$siteName)
                        ->replyTo(
                            Setting::get('contact_email', 'general') ?? config('mail.from.address'),
                            $siteName.' Support'
                        );
                }
            );
        } catch (\Throwable $e) {
            return $this->respondError('Gagal mengirim email: '.$e->getMessage());
        }

        // Save reply record
        $contact->update([
            'admin_reply' => $validated['reply_message'],
            'replied_at' => now(),
            'replied_by' => auth()->id(),
            'is_read' => true,
        ]);

        AuditLog::record('contact.replied', ['contact_id' => $contact->id]);

        return back()->with('success', 'Balasan berhasil dikirim ke '.$contact->email);
    }

    /**
     * Delete a submission.
     */
    public function destroy(ContactSubmission $contact)
    {
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Pesan kontak berhasil dihapus.');
    }
}
