<?php

declare(strict_types=1);

namespace LeapLyhour\LighthouseGraphQLSanctumAuth\Support\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use LeapLyhour\LighthouseGraphQLSanctumAuth\Models\Device;

/**
 * Has Devices Trait
 * 
 * Provides device management functionality for user models.
 * ផ្តល់ device management functionality សម្រាប់ user models។
 */
trait HasDevices
{
    /**
     * Get all devices for this user.
     * ទទួល devices ទាំងអស់សម្រាប់ user នេះ។
     */
    public function devices(): HasMany
    {
        return $this->hasMany(Device::class, 'user_id');
    }

    /**
     * Get active devices for this user.
     * ទទួល active devices សម្រាប់ user នេះ។
     */
    public function activeDevices(): HasMany
    {
        return $this->devices()->where('is_active', true);
    }

    /**
     * Register a new device.
     * ចុះឈ្មោះ device ថ្មី។
     */
    public function registerDevice(
        string $name,
        ?string $tokenId = null,
        ?string $ipAddress = null,
        ?string $userAgent = null
    ): Device {
        // Check device limit
        // ពិនិត្យមើល device limit
        $maxDevices = config('lighthouse-sanctum-auth.devices.max_per_user', 10);
        $deviceCount = $this->devices()->count();

        if ($deviceCount >= $maxDevices) {
            // Deactivate oldest device
            // ធ្វើឱ្យ device ចាស់ជាងគេ inactive
            $oldestDevice = $this->devices()
                ->orderBy('last_used_at', 'asc')
                ->first();

            if ($oldestDevice !== null) {
                $oldestDevice->deactivate();
            }
        }

        return $this->devices()->create([
            'name' => $name,
            'token_id' => $tokenId,
            'ip_address' => $ipAddress ?? request()?->ip(),
            'user_agent' => $userAgent ?? request()?->userAgent(),
            'is_active' => true,
            'last_used_at' => now(),
        ]);
    }

    /**
     * Remove a device.
     * លុប device។
     */
    public function removeDevice(int|string $deviceId): bool
    {
        return $this->devices()
            ->where('id', $deviceId)
            ->delete();
    }

    /**
     * Remove all devices.
     * លុប devices ទាំងអស់។
     */
    public function removeAllDevices(): int
    {
        return $this->devices()->delete();
    }

    /**
     * Deactivate all devices.
     * ធ្វើឱ្យ devices ទាំងអស់ inactive។
     */
    public function deactivateAllDevices(): int
    {
        return $this->devices()->update(['is_active' => false]);
    }

    /**
     * Get device by token ID.
     * ទទួល device តាម token ID។
     */
    public function getDeviceByTokenId(?string $tokenId): ?Device
    {
        if ($tokenId === null) {
            return null;
        }

        return $this->devices()
            ->where('token_id', $tokenId)
            ->first();
    }

    /**
     * Update device last used time.
     * ធ្វើបច្ចុប្បន្នភាព device last used time។
     */
    public function touchDevice(?string $tokenId): void
    {
        $device = $this->getDeviceByTokenId($tokenId);

        if ($device !== null) {
            $device->touchLastUsed();
        }
    }
}

