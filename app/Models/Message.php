<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Message
{
    /**
     * Return first Message in Queue
     *
     * @return string|null
     */
    public static function first(): string|null
    {
        $paths = Queue::paths();

        if (empty($paths)) {
            return null;
        }

        return $paths[0];
    }

    /**
     * Return all Message in Queue
     *
     * @return array
     */
    public static function all(): array
    {
        $paths = Queue::paths();

        if (empty($paths)) {
            return [];
        }

        $messages = [];
        foreach ($paths as $path) {
            $messages[] = json_decode(Storage::get($path), true);
        }

        return $messages;
    }

    /**
     * Store new Message in Queue
     *
     * @param string $message
     * @param $datetime
     * @return bool
     */
    public static function store(string $message, $datetime = null): bool
    {
        if (empty($message)) {
            return false;
        }

        if ($datetime && !valid_iso8601($datetime)) {
             return false;
        }

        $datetime ?? now()->toIso8601String();

        $queue = Queue::get();
        $message = [
            'datetime' => $datetime,
            'message' => $message
        ];

        if (Storage::put(Queue::path($queue['index'] + 1), json_encode($message))) {
            Queue::set();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Consume (retrieve & remove) Message from Queue
     *
     * @return string|null
     */
    public static function consume(): string|null
    {
        $message = self::first();

        if (empty($message)) {
            return null;
        }

        $content = Storage::get($message);
        Storage::delete($message);
        Queue::get();

        return $content;
    }
}
