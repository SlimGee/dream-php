<?php
function style_tag($style)
{
    loadCss($style);
    echo "<link href = '/app/assets/stylesheets/$style.css' rel = 'stylesheet' data-turbolinks-track = 'reload'>";
}
function js_include_tag($js)
{
    loadJs($js);
    echo "<script src = '/app/assets/javascripts/$js.js' data-turbolinks-track = 'reload'></script>";
}
function loadCss($value)
{
  if (!file_exists(ASSETS . DS . 'stylesheets' . DS . $value . '.css')) {
    return;
  }

  $file = file_get_contents(ASSETS . DS . 'stylesheets' . DS . $value . '.css');

  if(preg_match_all('/import (.*)/',$file,$matches)) :
    foreach ($matches[1] as $value) :
      style_tag(trim(trim($value,"'"),'"'));
    endforeach;
  endif;
}

function loadJs($value)
{
  if (!file_exists(ASSETS . DS . 'javascripts' . DS . $value . '.js')) {
    return;
  }

  $file = file_get_contents(ASSETS . DS . 'javascripts' . DS . $value . '.js');

  if(preg_match_all('/\=require (.*)/',$file,$matches)) :
    foreach ($matches[1] as $value) :
      js_include_tag(trim(trim($value,"'"),'"'));
    endforeach;
  endif;
}
