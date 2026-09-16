<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('e')) {
    function e($str) {
        return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('format_bytes')) {
    function format_bytes($bytes, $precision = 2) {
        if ($bytes === 0 || $bytes === null || $bytes === '') return '0 B';
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max((int)$bytes, 0);
        $pow = floor(($bytes > 0 ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        if ($pow < 0) $pow = 0;
        
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

if (!function_exists('format_duration')) {
    function format_duration($seconds) {
        if (empty($seconds) || $seconds <= 0) return '0:00';
        $h = floor($seconds / 3600);
        $m = floor(($seconds % 3600) / 60);
        $s = $seconds % 60;
        return $h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%d:%02d', $m, $s);
    }
}

if (!function_exists('get_type_badge')) {
    function get_type_badge($type) {
        $map = [
            'audio'    => ['label' => 'Audio', 'class' => 'success', 'icon' => 'bi bi-music-note-beamed'],
            'video'    => ['label' => 'Vidéo', 'class' => 'danger', 'icon' => 'bi bi-camera-reels'],
            'image'    => ['label' => 'Image', 'class' => 'primary', 'icon' => 'bi bi-image'],
            'document' => ['label' => 'Document', 'class' => 'warning', 'icon' => 'bi bi-file-earmark'],
            'link'     => ['label' => 'Lien', 'class' => 'info', 'icon' => 'bi bi-link-45deg'],
        ];
        return $map[$type] ?? ['label' => 'Média', 'class' => 'secondary', 'icon' => 'bi bi-file'];
    }
}