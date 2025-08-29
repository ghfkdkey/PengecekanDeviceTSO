<?php

namespace App\Traits;

use App\Models\Area;
use App\Models\Regional;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait RegionalFilter
{
    /**
     * Apply regional filter to query based on user permissions
     */
    protected function applyRegionalFilter(Builder $query, $regionalColumn = 'regional_id')
    {
        $user = auth()->user();
        
        // Admin dapat melihat semua data
        if ($user->isAdmin()) {
            return $query;
        }
        
        // PIC GA dan PIC Operational hanya bisa melihat data regional mereka
        if ($user->isGA() || $user->isOperational()) {
            if ($user->regional_id) {
                $query->where($regionalColumn, $user->regional_id);
            }
        }
        
        return $query;
    }
    
    /**
     * Apply regional filter untuk relasi (contoh: buildings.regional_id)
     */
    protected function applyRegionalFilterWithRelation(Builder $query, $relation, $regionalColumn = 'regional_id')
    {
        $user = auth()->user();
        
        // Admin dapat melihat semua data
        if ($user->isAdmin()) {
            return $query;
        }
        
        // PIC GA dan PIC Operational hanya bisa melihat data regional mereka
        if ($user->isGA() || $user->isOperational()) {
            if ($user->regional_id) {
                $query->whereHas($relation, function ($q) use ($regionalColumn, $user) {
                    $q->where($regionalColumn, $user->regional_id);
                });
            }
        }
        
        return $query;
    }
    
    /**
     * Get filter restrictions untuk view
     */
    protected function getFilterRestrictions()
    {
        $user = auth()->user();
        
        $restrictions = [
            'isRestricted' => false,
            'assignedRegionalId' => null,
            'assignedAreaId' => null,
            'restrictedRegionalName' => null,
            'restrictedAreaName' => null
        ];
        
        // Admin tidak dibatasi
        if ($user->isAdmin()) {
            return $restrictions;
        }
        
        // PIC GA dan PIC Operational dibatasi ke regional mereka
        if (($user->isGA() || $user->isOperational()) && $user->regional_id) {
            $regional = Regional::with('area')->find($user->regional_id);
            
            if ($regional) {
                $restrictions = [
                    'isRestricted' => true,
                    'assignedRegionalId' => $regional->regional_id,
                    'assignedAreaId' => $regional->area_id,
                    'restrictedRegionalName' => $regional->regional_name,
                    'restrictedAreaName' => $regional->area->area_name ?? null
                ];
            }
        }
        
        return $restrictions;
    }
    
    /**
     * Get areas yang bisa diakses user
     */
    protected function getAccessibleAreas()
    {
        $user = auth()->user();
        
        // Admin bisa akses semua area
        if ($user->isAdmin()) {
            return Area::orderBy('area_name')->get();
        }
        
        // PIC GA dan PIC Operational hanya bisa akses area dari regional mereka
        if (($user->isGA() || $user->isOperational()) && $user->regional_id) {
            $regional = Regional::with('area')->find($user->regional_id);
            
            if ($regional && $regional->area) {
                return collect([$regional->area]);
            }
        }
        
        return collect([]);
    }
    
    /**
     * Get regionals yang bisa diakses user
     */
    protected function getAccessibleRegionals($areaId = null)
    {
        $user = auth()->user();
        
        // Admin bisa akses semua regional
        if ($user->isAdmin()) {
            $query = Regional::query();
            
            if ($areaId) {
                $query->where('area_id', $areaId);
            }
            
            return $query->orderBy('regional_name')->get();
        }
        
        // PIC GA dan PIC Operational hanya bisa akses regional mereka
        if (($user->isGA() || $user->isOperational()) && $user->regional_id) {
            return Regional::where('regional_id', $user->regional_id)
                          ->orderBy('regional_name')
                          ->get();
        }
        
        return collect([]);
    }
    
    /**
     * Validate apakah user boleh akses regional/area tertentu
     */
    protected function validateRegionalAccess($regionalId = null, $areaId = null)
    {
        $user = auth()->user();
        
        // Admin boleh akses semua
        if ($user->isAdmin()) {
            return true;
        }
        
        // PIC GA dan PIC Operational hanya boleh akses regional mereka
        if ($user->isGA() || $user->isOperational()) {
            if ($regionalId && $regionalId != $user->regional_id) {
                return false;
            }
            
            if ($areaId && $user->regional_id) {
                $userRegional = Regional::find($user->regional_id);
                if ($userRegional && $userRegional->area_id != $areaId) {
                    return false;
                }
            }
        }
        
        return true;
    }
    
    /**
     * Get filtered request parameters
     */
    protected function getFilteredRequestParams(Request $request)
    {
        $user = auth()->user();
        
        // Admin menggunakan request parameter asli
        if ($user->isAdmin()) {
            return [
                'area' => $request->get('area'),
                'regional' => $request->get('regional')
            ];
        }
        
        // PIC GA dan PIC Operational dipaksa menggunakan regional mereka
        if (($user->isGA() || $user->isOperational()) && $user->regional_id) {
            $regional = Regional::find($user->regional_id);
            
            return [
                'area' => $regional ? $regional->area_id : null,
                'regional' => $user->regional_id
            ];
        }
        
        return [
            'area' => null,
            'regional' => null
        ];
    }
}