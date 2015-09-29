<?php

namespace App\Http\Controllers\Integration\mailgun;

use App\Http\Controllers\Integration\Integrator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Mailgun\Mailgun;

class Mailgun extends Integrator
{

    /**
     * Mailgun constructor.
     */
    public function __construct()
    {
        $this->mgClient = new Mailgun(env('Mailgun_Secret_API_Key', false));

    }

    public function getStats($domain = null) {
        $result = $this->mgClient->get("$domain/stats");
        return $result;
    }

    public function getAllDomains() {
        return array(env('Mailgun_Domain1'),env('Mailgun_Domain2'),env('Mailgun_Domain3'),env('Mailgun_Domain4'),env('Mailgun_Domain5'));
    }
}
