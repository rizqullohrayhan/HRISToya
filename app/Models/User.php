<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'team_id',
        'kantor_id',
        'email',
        'jabatan',
        'password',
        'picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the team that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    /**
     * Get all of the absensi for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'user_id', 'id');
    }

    /**
     * Get all of the aktivitas for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aktivitas()
    {
        return $this->hasMany(Aktivitas::class, 'user_id', 'id');
    }

    public function voucherAsUser()
    {
        return $this->hasMany(Voucher::class, 'user_id', 'id');
    }

    public function voucherAsReviewer()
    {
        return $this->hasMany(Voucher::class, 'reviewed_by', 'id');
    }

    public function voucherAsBookkeeper()
    {
        return $this->hasMany(Voucher::class, 'bookkeeped_by', 'id');
    }

    public function voucherAsApprover()
    {
        return $this->hasMany(Voucher::class, 'approved_by', 'id');
    }

    public function voucherAsCreator()
    {
        return $this->hasMany(Voucher::class, 'created_by', 'id');
    }

    /**
     * Get all of the cuti for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cuti()
    {
        return $this->hasMany(Cuti::class, 'user_id', 'id');
    }

    /**
     * Get all of the cutiSanksi for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cutiSanksi()
    {
        return $this->hasMany(CutiSanksi::class, 'user_id', 'id');
    }

    /**
     * Get all of the cutiTahunan for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cutiTahunan()
    {
        return $this->hasMany(CutiTahunan::class, 'user_id', 'id');
    }

    /**
     * Get the kantor that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kantor()
    {
        return $this->belongsTo(Kantor::class, 'kantor_id', 'id');
    }

    public function notulenRapat()
    {
        return $this->belongsTo(NotulenRapat::class);
    }

    public function masterDokumen()
    {
        return $this->hasMany(MasterDokumen::class, 'user_id', 'id');
    }
}
