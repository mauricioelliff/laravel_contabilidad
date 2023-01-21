<?php

namespace Modules\Contabilidad\Emails;

use Modules\General\Models\Logic\MailComponentes;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;


class MailDeudas extends Mailable
{
    use Queueable, SerializesModels;

    public $MailComponentes;  // Será visible en la view al ser public
    
    //public $theme = "default.css";  // que layout utilizará.  



    
    /**
     * .
     *
     * @return void
     */
    public function __construct( MailComponentes $MailComponentes )
    {
        $this->MailComponentes = $MailComponentes;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from( $this->MailComponentes->from )
                    ->bcc( $this->MailComponentes->from )   // copia oculta
                    ->subject( reemplazarPatrones( $this->MailComponentes->asunto, $this->MailComponentes->patrones ) )
                    // ->attach()
                    ->view('contabilidad::mails.estudiantedeuda')
                    //->with(['MailComponentes'=>$this->MailComponentes]) // ya la toma porque la declare public
                    ;
    }
}
