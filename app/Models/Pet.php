<?php

namespace App\Models;
use Carbon\Carbon;
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

    protected $appends = ['photo_url', 'formatted_dob', 'age'];

    /**
     * MEDIA COLLECTION
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('pet-photos')
            ->useDisk('public');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(150)
            ->height(150)
            ->fit(Fit::Crop, 150, 150)
            ->quality(80)
            ->format('webp')
            ->nonQueued();

       
        $this->addMediaConversion('medium')
            ->width(1200)
            ->height(1200)
            ->fit(Fit::Contain, 1200, 1200)
            ->quality(90)
            ->format('webp')
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

    public function getFormattedDobAttribute()
    {
        return $this->dob
            ? Carbon::parse($this->dob)->format('d.m.Y')
            : null;
    }

    public function getAgeAttribute()
    {
        return $this->dob
            ? Carbon::parse($this->dob)->age
            : null;
    }

    public function vetVisits()
    {
        return $this->hasMany(VetVisit::class)
            ->orderBy('visit_date', 'desc');
    }

    public function vaccines()
    {
        return $this->hasMany(Vaccine::class)
            ->orderBy('administered_date', 'desc');
    }

    public function medications()
    {
        return $this->hasMany(Medication::class)
            ->orderBy('start_date', 'desc');
    }
}