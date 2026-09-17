<?php
if (!function_exists('get_lang')) {
    function get_lang() {
        $ci =& get_instance();
        $uri = $ci->uri->segment(1);
        $valid_langs = ['en','fr','sw','rn','zh-CN','es','hi','ar','bn','pt','ru','ur','id','de','ja','ms','tr','ko','vi','it','fa','ta','th','pl','nl','uk','el','he','am','so','yo','ha','ig','zu','af','km','ne','mr','te','kn','gu','pa','da','no','fi','cs','hu','sv','ro'];
        if (in_array($uri, $valid_langs)) {
            return $uri;
        }
        $lang_cookie = $ci->input->cookie('agf_lang');
        if ($lang_cookie && in_array($lang_cookie, $valid_langs)) {
            return $lang_cookie;
        }
        $lang_session = $ci->session->userdata('agf_lang');
        if ($lang_session && in_array($lang_session, $valid_langs)) {
            return $lang_session;
        }
        return 'en';
    }
}

if (!function_exists('lang_base_url')) {
    function lang_base_url($path = '') {
        $lang = get_lang();
        $base = rtrim(base_url(), '/');
        if ($lang === 'en') {
            return $base . '/' . ltrim($path, '/');
        }
        return $base . '/' . $lang . '/' . ltrim($path, '/');
    }
}

if (!function_exists('t')) {
    function t($key, $default = null) {
        $translations = [
            'service_available_24h' => 'Service available 24h/24',
            'our_doctors' => 'Our Doctors',
            'doctors_subtitle' => 'Find the best doctors for your online consultation',
            'all_specialties' => 'All Specialties',
            'general_practitioner' => 'General Practitioner',
            'doctors_count' => 'doctor(s)',
            'available' => 'Available',
            'unavailable' => 'Unavailable',
            'years' => 'year(s)',
            'french' => 'English',
            'monday' => 'Monday',
            'tuesday' => 'Tuesday',
            'wednesday' => 'Wednesday',
            'thursday' => 'Thursday',
            'friday' => 'Friday',
            'saturday' => 'Saturday',
            'sunday' => 'Sunday',
            'by_appointment' => 'By appointment',
            'today_at' => 'Today at',
            'reviews' => 'review(s)',
            'experience' => 'Experience',
            'languages' => 'Languages',
            'schedule' => 'Schedule',
            'consultation_fee' => 'Consultation Fee',
            'burundi_price' => 'Resident price',
            'details' => 'Details',
            'appointment' => 'Make an appointment',
            'doctor_profile' => 'Doctor Profile',
            'verified' => 'Verified',
            'information' => 'Information',
            'license' => 'License',
            'day' => 'Day',
            'start' => 'Start',
            'end' => 'End',
            'no_schedule' => 'No schedule available',
            'prices' => 'Prices',
            'take_appointment' => 'Make an appointment',
            'doctor_unavailable' => 'Doctor currently unavailable',
            'no_doctors' => 'No doctors available at the moment',
            'why_24h' => 'Available 24h/24',
            'why_certified' => 'Certified Doctors',
            'why_certified_text' => 'All our doctors are verified and qualified',
            'why_affordable' => 'Affordable Prices',
            'why_affordable_text' => 'Competitive rates for quality consultations',
            'why_confidential' => 'Confidential',
            'why_confidential_text' => 'Your data is protected and confidential',
        ];
        return $translations[$key] ?? $default ?? $key;
    }
}
