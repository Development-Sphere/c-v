<?php

namespace App\Models;

use App\Enums\CvStatus;
use Database\Factories\CvFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'session_id', 'template', 'title',
    'personal_info', 'summary', 'experience', 'education', 'skills', 'extras',
    'status',
])]
class Cv extends Model
{
    /** @use HasFactory<CvFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_info' => 'array',
            'summary' => 'array',
            'experience' => 'array',
            'education' => 'array',
            'skills' => 'array',
            'extras' => 'array',
            'status' => CvStatus::class,
        ];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The Blade view path for this CV's current template, so rendering
     * code never hardcodes a view path (e.g. "cvs.templates.modern").
     */
    public function templateView(): string
    {
        return "cvs.templates.{$this->template}";
    }
}
