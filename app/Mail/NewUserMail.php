<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\tt_t_usuario as User;

class NewUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $role;
    public $pass;

    public function __construct(User $user, string $role, string $pass)
    {
        $this->user = $user;
        $this->role = $role; // Corrige esta línea
        $this->pass = $pass;
    }

    public function build()
    {
        return $this->subject('MERO CrossFit - Bienvenido')
                    ->markdown('emails.nuevo-usuario');
    }
}

