<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\UserResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    /**
     * you can override this method to customize the record creation process
     *
     * @param array $data
     * @return Model
     */
    protected function handleRecordCreation(array $data): Model
    {
        $user = static::getModel()::create($data);
        $title = 'Welcome to ' . config('app.name');
        $user->notify(
            Notification::make()
                ->title($title)
                ->toDatabase()
        );
        return $user;
    }
}
