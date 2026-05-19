<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Post extends Model
{
    protected $fillable = ['title', 'content'];

    // Rating relationship
    public function ratings(): MorphMany
    {
        return $this->morphMany(Rating::class, 'rateable');
    }

    // Calculate average rating
    public function getAverageRatingAttribute(): float
    {
        return round($this->ratings()->avg('rating') ?: 0, 1);
    }

    // Get rating percentage for each star
    public function getRatingPercentage(int $star): float
    {
        $total = $this->ratings()->count();
        if ($total === 0) return 0;
        
        $count = $this->ratings()->where('rating', $star)->count();
        return round(($count / $total) * 100);
    }

    // Check if user has rated
    public function hasUserRated($userId): bool
    {
        return $this->ratings()->where('user_id', $userId)->exists();
    }

    // Get user's rating
    public function getUserRating($userId): ?int
    {
        $rating = $this->ratings()->where('user_id', $userId)->first();
        return $rating ? $rating->rating : null;
    }
}