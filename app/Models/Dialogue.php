<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use League\CommonMark\CommonMarkConverter;

/**
 * @mixin IdeHelperDialogue
 */
class Dialogue extends Model
{
    use HasFactory;

    protected $appends = ['line_html'];

    protected $fillable = ['speaker', 'line', 'order'];

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Get the html of the line in markdown format
     */
    protected function lineHtml(): Attribute
    {
        $converter = new CommonMarkConverter(config('commonmark'));

        return Attribute::make(
            get: fn ($value, $attributes) => $converter->convert($attributes['line'])->getContent()
        );
    }
}
