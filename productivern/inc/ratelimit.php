<?php
function prd_rate_check($key, $max = 5, $window = 900) {
    $dir = sys_get_temp_dir() . '/prd_rl/';
    if (!is_dir($dir)) @mkdir($dir, 0700, true);
    $file = $dir . md5($key);
    $now = time();
    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : null;
    if (!$data || ($now - $data['t']) > $window) return true;
    return $data['n'] < $max;
}
function prd_rate_hit($key, $window = 900) {
    $dir = sys_get_temp_dir() . '/prd_rl/';
    if (!is_dir($dir)) @mkdir($dir, 0700, true);
    $file = $dir . md5($key);
    $now = time();
    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : null;
    if (!$data || ($now - $data['t']) > $window) $data = ['n' => 0, 't' => $now];
    $data['n']++;
    file_put_contents($file, json_encode($data), LOCK_EX);
}
function prd_rate_clear($key) {
    @unlink(sys_get_temp_dir() . '/prd_rl/' . md5($key));
}
