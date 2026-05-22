<?php

namespace App\Observers;

use App\Models\Service;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ServiceObserver
{
    public function created(Service $service){

        $links = [];

        foreach($service->links as $link){
            $links[] = [
                'name' => $link['name'],
                'path' => $link['path'],
                'url'  => Storage::disk('hetzner')->url($link['path']),
            ];
        }

        $service->updateQuietly(['links' => $links]);

    }


    public function updated(Service $service){

        Log::alert('Service updated:', [$service]);

        $links = [];

        foreach($service->links as $link){

            Log::alert('Link:', $link);
            $url = Storage::disk('hetzner')->url($link['path']);
            Log::alert('URL:', [$url]);


            $links[] = [
              'name' => $link['name'],
              'path' => $link['path'],
              'url'  => $url,
            ];
        }

        $service->updateQuietly(['links' => $links]);

    }
}
