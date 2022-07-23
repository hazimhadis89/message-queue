<?php

namespace App\Http\Controllers;

use App\Http\Requests\MessageStoreRequest;
use App\Models\Message;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response(Message::all(), ResponseCode::HTTP_OK);
    }
    /**
     * Display total number of the resource.
     *
     * @return Response
     */
    public function total()
    {
        return response(Queue::get()['total'], ResponseCode::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MessageStoreRequest $request
     * @return Response
     */
    public function store(MessageStoreRequest $request)
    {
        $validated = $request->validated();
        $result = Message::store($validated['message'], $validated['datetime'] ?? null);

        if ($result) {
            return response('Queue the message success.', ResponseCode::HTTP_CREATED);
        } else {
            return response('Queue the message fail.', ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Consume first resource in storage.
     *
     * @return Response
     */
    public function consume()
    {
        $message = Message::consume();

        if ($message) {
            return response($message, ResponseCode::HTTP_ACCEPTED);
        } else {
            return response('Consume message from queue fail.', ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        }
    }
}
