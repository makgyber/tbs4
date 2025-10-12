<?php

namespace App\Models;

use App\Observers\JobOrderObserver;
use App\Traits\HasComments;
use Carbon\Carbon;
use GalleryJsonMedia\JsonMedia\Concerns\InteractWithMedia;
use GalleryJsonMedia\JsonMedia\Contracts\HasMedia;
//use Guava\Calendar\Contracts\Eventable;
//use Guava\Calendar\ValueObjects\CalendarEvent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

//use Spatie\Image\Manipulations;
//use Spatie\MediaLibrary\HasMedia;
//use Spatie\MediaLibrary\InteractsWithMedia;
//use Spatie\MediaLibrary\MediaCollections\Models\Media;


#[ObservedBy([JobOrderObserver::class])]
class JobOrder extends Model implements HasMedia //, Eventable
{
    use HasFactory, HasRelationships, LogsActivity, HasTags, InteractWithMedia, HasComments;

    protected $fillable = [
        'code', 'summary', 'target_date', 'completed', 'confirmed', 'client_id', 'target_end',
        'created_by', 'job_order_type', 'address_id', 'signature', 'review', 'service_acknowledgement',
        'jobable_type', 'jobable_id', 'status', 'approved', 'approved_at', 'pre_service_notes', 'rating',
        'dept_head_rating',
        'addpest', 'endorsed_files', 'endorsed_sa', 'endorsed_layout', 'endorsed_jo_hard_copy', 'dept_head_review',
        'reviewed', 'reviewed_at', 'reviewed_by', 'serviceable_areas', 'sa_uploaded_by', 'tech_call_received', 'upsell'
    ];

    protected $casts = [
        'target_date' => 'datetime',
        'approved' => 'boolean',
        'service_acknowledgement' => 'array',
        'serviceable_areas' => 'array',
        'addpest' => 'boolean',
        'endorsed_files' => 'boolean',
        'endorsed_sa' => 'boolean',
        'endorsed_jo_hard_copy' => 'boolean',
        'endorsed_layout' => 'boolean',
        'reviewed' => 'boolean',
        'tech_call_received' => 'boolean',
        'upsell' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (JobOrder $jo) {
            $instructions = JobOrderType::where('name', $jo->job_order_type)
                ->get("instruction_list")->first()?->instruction_list;

            if ($instructions) {
                foreach ($instructions as $instruction) {
                    Instruction::create([
                        'job_order_id' => $jo->id,
                        'instruction' => $instruction
                    ]);
                }
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function saUploadedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sa_uploaded_by', 'id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->withPivot(['alloted', 'consumed'])
            ->withTimestamps();
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function instructions(): HasMany
    {
        return $this->hasMany(Instruction::class);
    }

    public function findings(): HasMany
    {
        return $this->hasMany(Finding::class);
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }

    public function conducives(): HasMany
    {
        return $this->hasMany(Conducive::class);
    }

    public function unservicedAreas(): HasMany
    {
        return $this->hasMany(UnservicedArea::class);
    }

    public function consumptions(): HasMany
    {
        return $this->hasMany(Consumption::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function sites(): BelongsToMany
    {
        return $this->belongsToMany(Site::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function serviceInterval(): Attribute
    {
        return Attribute::make(
            get: function () {
                $started = null;
                $completed = null;
                foreach ($this->activities()->getResults() as $activity) {

                    foreach ($activity->properties as $item) {

                        if (isset($item['status']) && $item['status'] == 'started') {
                            $started = $item['updated_at'];
                        }
                        if (isset($item['status']) && $item['status'] == 'completed') {
                            $completed = isset($item['updated_at']) ? $item['updated_at'] : $this->updated_at;
                        }
                    }
                }
                if (is_null($started)) {
                    return 'Not started';
                }

                if (is_null($completed)) {
                    return 'Started '.Carbon::make($started)->setTimezone('Asia/Manila')->format('Y-m-d h:i a');
                }

                return 'Completed '.Carbon::make($started)->setTimezone('Asia/Manila')->format('Y-m-d h:i a').' - '.Carbon::make($completed)->setTimezone('Asia/Manila')->format('Y-m-d h:i a');
            }
        );
    }

    public function completedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $started = null;
                $completed = null;
//                foreach ($this->activities()->getResults() as $activity) {
//                    foreach ($activity->properties as $item) {
//                        if (isset($item['status']) && $item['status'] == 'started') {
//                            $started = $item['updated_at'];
//                        }
//                        if (isset($item['status']) && $item['status'] == 'completed') {
//                            $completed = isset($item['updated_at']) ? $item['updated_at'] : $this->updated_at;
//                        }
//                    }
//                }
                $statuses = $this->activities()->getResults()->pluck('properties.attributes.updated_at',
                    'properties.attributes.status')->filter(fn($key, $val) => $val === 'completed')->all();

                if (!$statuses) {
                    return 'Not completed';
                }
                return Carbon::make($statuses['completed'])->setTimezone('Asia/Manila')->format('D, d M Y h:i:s A');
            }
        );
    }

    public function startedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $statuses = $this->activities()->getResults()->pluck('properties.attributes.updated_at',
                    'properties.attributes.status')->filter(fn($key, $val) => $val === 'started')->all();

                if (!$statuses) {
                    return 'Not started';
                } else {
                    return Carbon::make($statuses['started'])->setTimezone('Asia/Manila')->format('D, d M Y h:i:s A');
                }
            }
        );
    }

    public function postponedAt(): Attribute
    {
        return Attribute::make(
            get: function () {
                $statuses = $this->activities()->getResults()->pluck('properties.attributes.updated_at',
                    'properties.attributes.status')->filter(fn($key, $val) => $val === 'postponed')->all();

                if (!$statuses) {
                    return '-';
                }
                return Carbon::make($statuses['postponed'])->setTimezone('Asia/Manila')->format('D, d M Y h:i:s A');
            }
        );
    }

    public function concerns(): HasMany
    {
        return $this->hasMany(Concern::class);
    }

    public function fieldImages(): HasMany
    {
        return $this->hasMany(FieldImage::class);
    }

//    public function registerMediaConversions(Media $media = null): void
//    {
//        $this
//            ->addMediaConversion('preview')
//            ->fit(Manipulations::FIT_CROP, 300, 300)
//            ->nonQueued();
//    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function teamColor(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->teams->first()->color ?? '#111111'
        );
    }

    public function relatedJobOrders(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->jobable->jobOrders->sortByDesc('target_date')
        );
    }

    public function lastJobOrder(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->jobable->jobOrders->last()
        );
    }

    public function timeToNextService(): Attribute
    {
        return Attribute::make(
            get: function () {

                if (!$this->nextJobOrder) {
                    return 'last visit';
                }

                $currentTargetDate = Carbon::make($this->target_date)->startOfDay();

                if (!$this->nextJobOrder->target_date) {
                    return 'last visit';
                }

                $nextTargetDate = Carbon::make($this->nextJobOrder->target_date)->startOfDay();

                return $nextTargetDate->diffForHumans($currentTargetDate);
            }
        );
    }

    public function nextJobOrder(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->jobable?->jobOrders?->filter(fn($item
            ) => $item->target_date > $this->target_date)
                ->filter(fn($item) => in_array($item->status, ['unscheduled', 'scheduled']))
                ->sortBy('target_date')->first()
        );
    }

    public function previousJobOrder(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->jobable?->jobOrders?->filter(fn($item
            ) => $item->target_date < $this->target_date)
                ->filter(fn($item) => in_array($item->status, ['unscheduled', 'scheduled']))
                ->last()
        );
    }

    public function jobable()
    {
        return $this->morphTo(__FUNCTION__, 'jobable_type', 'jobable_id');
    }

    public function isServiceCompleted(): Attribute
    {
        return Attribute::make(
            get: function () {
                $modules = ['conducives', 'treatments', 'consumptions', 'unservicedAreas', 'infestations'];
                foreach ($modules as $module) {
                    if (!$this->$module()->count()) {
                        return false;
                    }
                }
                if (!$this->service_acknowledgement) {
                    return false;
                }
                if (!$this->signature) {
                    return false;
                }

                return true;
            }
        );
    }

    public function hasTechInputs(): Attribute
    {
        return Attribute::make(
            get: function () {
                $modules = ['conducives', 'treatments', 'consumptions', 'unservicedAreas', 'infestations'];

                foreach ($modules as $module) {
                    if ($this->$module()->count() > 0) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    public function isInfested(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->infestations->count() === 0) {
                    return false;
                }

                foreach ($this->infestations as $inf) {
                    if ($inf->pestType->name == 'None') {
                        return false;
                    }
                }

                return true;
            }
        );
    }

    public function hasSlowfeeding(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->infestations->count() === 0) {
                    return false;
                }

                foreach ($this->infestations as $inf) {
                    if ($inf->slow_feeding) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    public function hasReinfestation(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->infestations->count() === 0) {
                    return false;
                }

                foreach ($this->infestations as $inf) {
                    if ($inf->re_infestation) {
                        return true;
                    }
                }

                return false;
            }
        );
    }

    public function hasEndorsedFile(): Attribute
    {
        return Attribute::make(
            get: function () {
                return boolval($this->endorsed_file);
            }
        );
    }

    public function hasEndorsedSA(): Attribute
    {
        return Attribute::make(
            get: function () {
                return boolval($this->endorsed_sa);
            }
        );
    }

    public function hasEndorsedJoHardCopy(): Attribute
    {
        return Attribute::make(
            get: function () {
                return boolval($this->endorsed_jo_hard_copy);
            }
        );
    }

    public function hasEndorsedLayout(): Attribute
    {
        return Attribute::make(
            get: function () {
                return boolval($this->endorsed_layout);
            }
        );
    }

    public function hasAddpest(): Attribute
    {
        return Attribute::make(
            get: function () {
                return boolval($this->addpest);
            }
        );
    }

    public function infestations(): HasMany
    {
        return $this->hasMany(Infestation::class);
    }


    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public function contractedServiceableAreas(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->sites->map(fn($t) => $t->areaList())->first();
            }
        );
    }

    public function saReview(): HasOne
    {
        return $this->hasOne(SaReview::class);
    }

    public function proposition(): HasOne
    {
        return $this->hasOne(Proposition::class);
    }

    public function entomSurvey(): HasOne
    {
        return $this->hasOne(EntomSurvey::class);
    }

    public function entomSurveyProposals(): HasManyThrough
    {
        return $this->hasManyThrough(EntomSurveyProposal::class, EntomSurvey::class);
    }

    public function inspectedAreas(): HasMany
    {
        return $this->hasMany(InspectedArea::class);
    }

    public function uninspectedAreas(): HasMany
    {
        return $this->hasMany(UninspectedArea::class);
    }

    public function lateStatusReason(): Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->comments->last()?->body;
            }
        );
    }

    public function preServiceAssessment(): HasMany
    {
        return $this->hasMany(PreServiceAssessment::class);
    }

    public function postServiceAssessment(): HasMany
    {
        return $this->hasMany(PostServiceAssessment::class);
    }

    public function arrivals(): HasMany
    {
        return $this->hasMany(Arrival::class);
    }

//    public function toCalendarEvent(): array|CalendarEvent
//    {
//        return CalendarEvent::make()
//            ->title($this->client ? $this->client->name : $this->code)
//            ->start(Carbon::make($this->target_date)->addHours(8))
//            ->action('edit')
//            ->resourceIds($this->teams->pluck('id')->toArray() + ['unassigned']);
//    }

}
