<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\RfidLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RfidLogController extends Controller
{
    public function __construct(private RfidLogService $log) {}

    public function index(): View
    {
        $entries = $this->log->all();

        return view('rfid-log.index', ['entries' => array_slice($entries, 0, 200)]);
    }

    public function feed(): JsonResponse
    {
        return response()->json($this->log->all());
    }
}
