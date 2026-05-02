<?php

namespace App\Support;

use App\Models\NhanVien;

class Rbac
{
    public const ADMIN_PERMISSION = 'system.admin';
    public const HR_PERMISSION = 'system.hr';

    public static function roles(): array
    {
        return config('rbac.roles', []);
    }

    public static function permissions(): array
    {
        return config('rbac.permissions', []);
    }

    public static function normalizePermission(string $permission): string
    {
        return config("rbac.permission_aliases.{$permission}", $permission);
    }

    public static function hasRole(?NhanVien $user, array|string $roles): bool
    {
        if (!$user) {
            return false;
        }

        $allowedRoles = is_array($roles) ? $roles : [$roles];

        return in_array(self::role($user), $allowedRoles, true);
    }

    public static function isRootAdmin(?NhanVien $user): bool
    {
        if (!$user) {
            return false;
        }

        $rootAdmin = config('rbac.root_admin', []);
        $rootId = $rootAdmin['id'] ?? null;
        $rootEmail = $rootAdmin['email'] ?? null;

        return ($rootId && (int) $user->id === (int) $rootId)
            || ($rootEmail && strtolower($user->email) === strtolower($rootEmail));
    }

    public static function role(?NhanVien $user): string
    {
        if (self::isRootAdmin($user) || self::hasPermission($user, self::ADMIN_PERMISSION, false)) {
            return 'admin';
        }

        if (self::hasPermission($user, self::HR_PERMISSION, false)) {
            return 'hr';
        }

        return 'staff';
    }

    public static function hasPermission(?NhanVien $user, string $permission, bool $includeRootAdmin = true): bool
    {
        if (!$user) {
            return false;
        }

        $permission = self::normalizePermission($permission);

        if ($includeRootAdmin && self::isRootAdmin($user)) {
            return true;
        }

        $aliases = array_keys(array_filter(
            config('rbac.permission_aliases', []),
            fn ($value) => $value === $permission
        ));

        return $user->phanQuyens()
            ->whereIn('ten_chuc_nang', array_merge([$permission], $aliases))
            ->exists();
    }

    public static function assignablePermissions(): array
    {
        return self::permissions();
    }
}
