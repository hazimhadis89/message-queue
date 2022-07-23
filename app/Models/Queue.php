<?php

namespace App\Models;

use Illuminate\Support\Facades\Storage;

class Queue
{
    public static function path(int $index): string
    {
        return config('queue.folder').'\\'.str_pad($index, 8, '0', STR_PAD_LEFT).'.txt';
    }

    public static function paths(): array
    {
        return Storage::files(config('queue.folder'));
    }

    public static function index(string $path): int
    {
        return (int) substr($path, strlen(config('queue.folder')) + 1, -4);
    }

    public static function count(): int
    {
        return count(Storage::files(config('queue.folder')));
    }

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

    public static function set(): array
    {
        $queue = self::get();
        $queue['index']++;
        $queue['total'] = self::count();
        Storage::put(config('queue.filename'), json_encode($queue));
        return $queue;
    }
}
