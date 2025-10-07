<?php
namespace App\Http\Services;

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;
use App\Models\Sheet;
use Auth;

class GoogleSheetServices
{
    function getClient()
    {
        $client = new Client();
        $client->setApplicationName('Google Sheets API PHP Quickstart');
        $client->setScopes('https://www.googleapis.com/auth/spreadsheets');
        $client->setAuthConfig('/home/FULFILLEMENT/public_html/admin/credentials.json');
        $client->setAccessType('offline');

        return $client;
    }

    public function readSheet()
    {
        $client = $this->getClient();
        $service = new Sheets($client);
        $spreadsheetId = '1finVLOlx6EKm46Mz6wx_K6BLPpg60N5pjTrDPKz9aN4';
        $range = 'A2:L';
        $doc = $service->spreadsheets_values->get($spreadsheetId, $range);
        return $doc;
    }
    
}