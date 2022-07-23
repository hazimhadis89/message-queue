<?php

namespace App\Models;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class Message
{
    public static function queue($array = false): array|Collection
    {
        $queueString = Storage::get(config('queue.filename'));

        if (strlen($queueString) === 0) {
            return $array ? [] : collect();
        }

        $queueArray = preg_split("/\r\n|\n|\r/", $queueString);

        if ($array) {
            return $queueArray;
        }

        $queueCollection = collect();

        foreach ($queueArray as $queue) {
            $queueCollection->push(json_decode($queue));
        }

        return $queueCollection->sortBy('datetime')->values();
    }

    public static function total(): int
    {
        $queueArray = self::queue(true);

        return count($queueArray);
    }

    public static function store(string $message, $datetime = null): bool
    {
        if (empty($message)) {
            return false;
        }

        if ($datetime && !valid_iso8601($datetime)) {
             return false;
        }

        $datetime ?? now()->toIso8601String();

        $message = [
            'datetime' => $datetime,
            'message' => $message,
        ];

        Storage::append(config('queue.filename'), json_encode($message));

        return true;
    }

    public static function consume(): string|null
    {
        $queueArray = self::queue(true);

        if (empty($queueArray)) {
            return null;
        }

        $message = $queueArray[0];

        unset($queueArray[0]);

        Storage::delete(config('queue.filename'));
        foreach ($queueArray as $queue) {
            Storage::append(config('queue.filename'), $queue);
        }

        return $message;
    }
}
