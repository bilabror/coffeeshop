<?php

function pages($page = '', $data = '') {
  $ci = get_instance();
  $ci->load->view('_layouts/header', $data);
  $ci->load->view('_layouts/layout', $data);
  $ci->load->view('_layouts/sidebar', $data);
  $ci->load->view($page, $data);
  $ci->load->view('_layouts/footer', $data);
}

function pages_frontend($page = '', $data = '') {
  $ci = get_instance();

  $ci->load->view('frontend/_layouts/header', $data);
  $ci->load->view('frontend/_layouts/navbar', $data);
  $ci->load->view($page, $data);
  $ci->load->view('frontend/_layouts/footer', $data);
}