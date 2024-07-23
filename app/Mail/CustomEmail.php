<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Advertisement;

class CustomEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $advertisement;
    public $file;
    public $fileName;
    public $mimeType;
    /**
     * Create a new message instance.
     *
     * @return void
     */


    public function __construct($advertisement, $file, $fileName, $mimeType)
    {
        $this->advertisement = $advertisement;
        $this->file = $file;
        $this->fileName = $fileName;
        $this->mimeType = $mimeType;
    }

    public function build()
    {
        return $this->view('emails.custom')
            ->subject('New Advertisement')
            ->attachData($this->file, $this->fileName, ['mime' => $this->mimeType]);
    }
}
