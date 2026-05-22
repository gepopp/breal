<?php

namespace App\Observers;

use App\Models\Service;
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

        $links = [];

        foreach($service->links as $link){
            $links[] = [
              'name' => $link['name'],
              'path' => $link['path'],
              'url'  => Storage::disk('s3')->url($link['path']),
            ];
        }

        $service->updateQuietly(['links' => $links]);

    }
}
