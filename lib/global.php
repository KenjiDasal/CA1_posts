<?php
function get_words($text, $count = 10) {
  return implode(' ', array_slice(explode(' ', $text), 0, $count));
}

function old($key, $default=null) {
  global $request;
  if ($request->session()->has("flash_data")) {
    $data = $request->session()->get("flash_data");
    if (is_array($data) && array_key_exists($key, $data)) {
      return $data[$key];
    }
  }
  else {
    return $default;
  }
}

function error($key) {
  global $request;
  if ($request->session()->has("flash_errors")) {
    $errors = $request->session()->get("flash_errors");
    if (is_array($errors) && array_key_exists($key, $errors)) {
      return $errors[$key];
    }
  }
  return null;
}

function chosen($key, $search, $default=null) {
  global $request;
  if ($request->session()->has("flash_data")) {
    $data = $request->session()->get("flash_data");
    if (is_array($data) && array_key_exists($key, $data)) {
      $value = $data[$key];
      if (is_array($value)) {
        return in_array($search, $value);
      }
      else {
        return strcmp($value, $search) === 0;
      }
    }
    else {
      return FALSE;
    }
  }
  else if ($default !== null) {
    if (is_array($default)) {
      return in_array($search, $default);
    }
    else {
      return strcmp($default, $search) === 0;
    }
  }
  else {
    return FALSE;
  }
}
?>