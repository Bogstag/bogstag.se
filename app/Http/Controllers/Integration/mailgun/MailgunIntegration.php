<?php

namespace App\Http\Controllers\Integration\mailgun;

use Mailgun\Mailgun;
use App\Http\Controllers\Integration\Integrator;

class MailgunIntegration extends Integrator
{
    /**
     * MailgunIntegration constructor.
     */
    public function __construct()
    {
        $this->mgClient = new Mailgun(env('Mailgun_Secret_API_Key', false));
    }

    public function getStats($domain = null)
    {
        $result = $this->mgClient->get("$domain/stats");

        return $result;
    }

    public function getAllDomains()
    {
        return [env('Mailgun_Domain1'), env('Mailgun_Domain2'), env('Mailgun_Domain3'), env('Mailgun_Domain4'), env('Mailgun_Domain5')];
    }
}
