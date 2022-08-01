<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class Queue
{
    /**
     * Return Message directory path
     *
     * @param int $index
     * @return string
     */
    public static function path(int $index): string
    {
        return config('queue.folder').'\\'.str_pad($index, 8, '0', STR_PAD_LEFT).'.txt';
    }

    /**
     * Return all Message path in Queue
     *
     * @return array
     */
    public static function paths(): array
    {
        return Storage::files(config('queue.folder'));
    }

    /**
     * Return Message index key
     *
     * @param string $path
     * @return int
     */
    public static function index(string $path): int
    {
        return (int) substr($path, strlen(config('queue.folder')) + 1, -4);
    }

    /**
     * Return number of Message in Queue
     *
     * @return int
     */
    public static function count(): int
    {
        return count(Storage::files(config('queue.folder')));
    }

    /**
     * Return Queue latest details [current_index, total_messages]
     *
     * @return array
     */
    public static function get(): array
    {
        if (Storage::exists(config('queue.filename'))) {
            $queue['index'] = (int) json_decode(Storage::get(config('queue.filename')), true)['index'];
        } else {
            $queue['index'] = self::count();
        }
        $queue['total'] = self::count();

        Storage::put(config('queue.filename'), json_encode($queue));

        return $queue;
    }

    /**
     * Update and return Queue details [current_index, total_messages]
     *
     * @return array
     */
    public static function set(): array
    {
        $queue = self::get();
        $queue['index']++;
        $queue['total'] = self::count();
        Storage::put(config('queue.filename'), json_encode($queue));
        return $queue;
    }
}
