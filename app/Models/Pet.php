<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Enums\Fit;

class Pet extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'name',
        'chip',
        'species',
        'breed',
        'gender',
        'dob',
        'weight',
        'description',
    ];

    protected $appends = ['photo_url'];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(120)
            ->height(120)
            ->fit(Fit::Crop, 120, 120)
            ->optimize()
            ->nonQueued();

        $this->addMediaConversion('medium')
            ->width(400)
            ->height(400)
            ->fit(Fit::Contain, 400, 400)
            ->optimize()
            ->nonQueued();
    }

    public function getPhotoUrlAttribute(): array
    {
        $media = $this->getFirstMedia('pet-photos');

        return [
            'thumb'  => $media?->getUrl('thumb'),
            'medium' => $media?->getUrl('medium'),
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vetVisits()
    {
        return $this->hasMany(VetVisit::class)->orderBy('visit_date', 'desc');
    }

    public function vaccines()
    {
        return $this->hasMany(Vaccine::class)->orderBy('administered_date', 'desc');
    }

    public function medications()
    {
        return $this->hasMany(Medication::class)->orderBy('start_date', 'desc');
    }
}