<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Redis;

class MainController extends Controller
{
    public function index()
    {
        return view('demo');
    }

    public function updateRandom()
    {
	$data = Redis::smembers('latest_group');
	$strData = implode(", ", $data);

	\Log::info("Current set from Redis: $strData");

	$response = new StreamedResponse(function() use ($strData)
	{
		$x = mt_rand(1, 100);
		echo "data: $strData\n\n";
		ob_flush();
		flush();
		sleep(2);
	});

        $response->headers->set('Content-Type', 'text/event-stream');
        return $response;
    }
}
