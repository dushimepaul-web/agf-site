<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// Language prefix routes (strip /en/ or /fr/ prefix)
$langs = 'en|fr|sw|rn|zh-CN|es|hi|ar|bn|pt|ru|ur|id|de|ja|ms|tr|ko|vi|it|fa|ta|th|pl|nl|uk|el|he|am|so|yo|ha|ig|zu|af|km|ne|mr|te|kn|gu|pa|da|no|fi|cs|hu|sv|ro';
$route['('.$langs.')'] = 'Home/Home/index';
$route['('.$langs.')/(:any)'] = '$1';

// Redirection pour /index -> Admin
$route['index'] = 'Admin/index';
$route['index/(:any)'] = 'Admin/index';

// Auth
$route['Admin'] = 'Admin/index';
$route['Admin/Login'] = 'Admin/Login';
$route['Admin/do_login'] = 'Admin/do_login';
$route['Admin/register'] = 'Admin/register';
$route['Admin/do_register'] = 'Admin/do_register';
$route['Logout'] = 'Admin/Logout';

// Profil
$route['Profile'] = 'Profile/index';
$route['api/profile/update'] = 'Profile/api_update';
$route['api/profile/change_password'] = 'Profile/api_change_password';

// Dashboard
$route['Dashboard'] = 'Dashboard/Dashboard/index';
$route['api/dashboard/data'] = 'Dashboard/Dashboard/api_data';
$route['api/dashboard/filters'] = 'Dashboard/Dashboard/api_filters';
$route['Dashboard/(:any)'] = 'Dashboard/Dashboard/index/$1';


// Produits
$route['Produits'] = 'Produits/Produits/index';
$route['Produits/add_edit'] = 'Produits/Produits/add_edit';
$route['Produits/add_edit/(:num)'] = 'Produits/Produits/add_edit/$1';
$route['Produits/(:any)'] = 'Produits/Produits/$1';

// Catégories
$route['Categories'] = 'Produits/Categories/index';
$route['Categories/add_edit'] = 'Produits/Categories/add_edit';
$route['Categories/add_edit/(:num)'] = 'Produits/Categories/add_edit/$1';
$route['Categories/(:any)'] = 'Produits/Categories/$1';

// Unités d'affaires
$route['Unites'] = 'Produits/Unites/index';
$route['Unites/add_edit'] = 'Produits/Unites/add_edit';
$route['Unites/add_edit/(:num)'] = 'Produits/Unites/add_edit/$1';
$route['Unites/(:any)'] = 'Produits/Unites/$1';

// Upload
$route['Produits/api_upload'] = 'Produits/Produits/api_upload';
$route['Unites/api_upload'] = 'Produits/Unites/api_upload';

// Menus
$route['Menus'] = 'Administration/Menus/index';
$route['Menus/(:any)'] = 'Administration/Menus/$1';

// Rôles
$route['Roles'] = 'Administration/Roles/index';
$route['Roles/(:any)'] = 'Administration/Roles/$1';

// Galerie Medias
$route['GalerieMedias'] = 'admin_galerie/Media/index';
$route['GalerieMedias/index/(:any)'] = 'admin_galerie/Media/index/$1';
$route['GalerieMedias/(:any)'] = 'admin_galerie/Media/$1';

// API CRUD (api.js crudResource pattern)
$route['api/menus'] = 'Administration/Menus/api_list';
$route['api/menus/create'] = 'Administration/Menus/api_create';
$route['api/menus/(:any)/update'] = 'Administration/Menus/api_update/$1';
$route['api/menus/(:any)/delete'] = 'Administration/Menus/api_delete/$1';
$route['api/menus/(:any)'] = 'Administration/Menus/api_get/$1';

$route['api/roles'] = 'Administration/Roles/api_list';
$route['api/roles/create'] = 'Administration/Roles/api_create';
$route['api/roles/(:any)/update'] = 'Administration/Roles/api_update/$1';
$route['api/roles/(:any)/delete'] = 'Administration/Roles/api_delete/$1';
$route['api/roles/(:any)'] = 'Administration/Roles/api_get/$1';

// Contact Us
$route['ContactUs'] = 'Public/ContactUs/index';
$route['ContactUs/(:any)'] = 'Public/ContactUs/$1';

// FAQ
$route['Faq'] = 'Public/Faq/index';
$route['Faq/add_edit'] = 'Public/Faq/add_edit';
$route['Faq/add_edit/(:num)'] = 'Public/Faq/add_edit/$1';
$route['Faq/(:any)'] = 'Public/Faq/$1';

// Partenaires
$route['Partenaires'] = 'Public/Partenaires/index';
$route['Partenaires/add_edit'] = 'Public/Partenaires/add_edit';
$route['Partenaires/add_edit/(:num)'] = 'Public/Partenaires/add_edit/$1';
$route['Partenaires/(:any)'] = 'Public/Partenaires/$1';

// Social Links
$route['SocialLinks'] = 'Public/SocialLinks/index';
$route['SocialLinks/(:any)'] = 'Public/SocialLinks/$1';

// Brokers
$route['Brokers'] = 'Finance/Brokers/index';
$route['Brokers/add_edit'] = 'Finance/Brokers/add_edit';
$route['Brokers/add_edit/(:num)'] = 'Finance/Brokers/add_edit/$1';
$route['Brokers/(:any)'] = 'Finance/Brokers/$1';

// Investors
$route['Investors'] = 'Finance/Investors/index';
$route['Investors/add_edit'] = 'Finance/Investors/add_edit';
$route['Investors/add_edit/(:num)'] = 'Finance/Investors/add_edit/$1';
$route['Investors/(:any)'] = 'Finance/Investors/$1';

// Logs
$route['Logs'] = 'Administration/Logs/index';
$route['Logs/(:any)'] = 'Administration/Logs/$1';

// Users
$route['Users'] = 'Administration/Users/index';
$route['Users/(:any)'] = 'Administration/Users/$1';

// Sessions
$route['Sessions'] = 'Administration/Sessions/index';
$route['Sessions/(:any)'] = 'Administration/Sessions/$1';

// Visitors
$route['Visitors'] = 'Administration/Visitors/index';
$route['Visitors/(:any)'] = 'Administration/Visitors/$1';

// Temoignages
$route['Temoignages'] = 'Temoignages/Temoignages/index';
$route['Temoignages/(:any)'] = 'Temoignages/Temoignages/$1';

// Consultations (admin)
$route['Consultations'] = 'Consultation/Consultations/index';
$route['Consultations/medecins'] = 'Consultation/Consultations/medecins';
$route['Consultations/medecin_form/(:any)'] = 'Consultation/Consultations/medecin_form/$1';
$route['Consultations/medecin_form'] = 'Consultation/Consultations/medecin_form';
$route['Consultations/(:any)'] = 'Consultation/Consultations/$1';

// Patients
$route['Patients'] = 'Consultation/Patients/index';
$route['Patients/(:any)'] = 'Consultation/Patients/$1';

// Consultation
$route['doctor'] = 'Home/PatientForm/Medicin';
$route['consultation'] = 'Home/PatientForm/Medicin';
$route['patient-form'] = 'Home/PatientForm/index';
$route['patient-form/create'] = 'Home/PatientForm/create';
$route['patient-form/api_submit'] = 'Home/PatientForm/api_submit';
$route['patient-form/changeDoctor'] = 'Home/PatientForm/changeDoctor';
$route['patient-form/get_countries'] = 'Home/PatientForm/get_countries';
$route['Consultation'] = 'Consultation/Consultation/index';
$route['Consultation/detail/(:num)'] = 'Consultation/Consultation/detail/$1';
$route['Consultation/formulaire/(:num)'] = 'Consultation/Consultation/formulaire/$1';
$route['Consultation/(:any)'] = 'Consultation/Consultation/$1';

// Frontend pages — About & Content
$route['about'] = 'Home/About/index';
$route['about/(:any)'] = 'Home/About/detail/$1';
$route['a-propos'] = 'Home/About/index';

// About submenu
$route['about-us'] = 'Home/About/about_us';
$route['aninova-industries'] = 'Home/About/aninova_industries';
$route['seriqa-labo'] = 'Home/About/seriqa_labo';
$route['our-Products'] = 'Home/About/our_Products';
$route['agriculture-bioresources'] = 'Home/About/agriculture_bioresources';



// politique
$route['legal'] = 'Home/About/legal';
$route['privacy'] = 'Home/About/privacy';

// Investment
$route['markets-partners'] = 'Home/About/markets_partners';
$route['sustainability-impact'] = 'Home/About/sustainability_impact';
$route['broker'] = 'Home/About/broker';
$route['broker/store'] = 'Home/About/broker_store';
$route['api/pays/search'] = 'Home/About/api_pays_search';
$route['api/detect-country'] = 'Home/About/api_detect_country';
$route['investor'] = 'Home/About/investor';
$route['investor/store'] = 'Home/About/investor_store';
$route['credit-summary'] = 'Home/About/credit_summary';
$route['Investors-form'] = 'Home/About/investors_form';



// Frontend pages — Products
$route['Products'] = 'Produits/Produits/index';
$route['Products/detail/(:any)'] = 'Produits/Produits/detail/$1';
$route['Products/(:any)'] = 'Produits/Produits/$1';

// Frontend pages — Media & Contact
$route['media'] = 'Home/Media/index';
$route['media/trending'] = 'Home/Media/trending';
$route['media/news'] = 'Home/Media/news';
$route['media/temoignages'] = 'Home/Media/temoignages';
$route['media/type/(:any)'] = 'Home/Media/type/$1';
$route['media/detail/(:any)'] = 'Home/Media/detail/$1';
$route['media/apiSearch'] = 'Home/Media/apiSearch';
$route['media/liveSearch'] = 'Home/Media/liveSearch';
$route['media/apiTrackView'] = 'Home/Media/apiTrackView';
$route['media/apiGrid'] = 'Home/Media/apiGrid';
$route['media/downloader/(:any)'] = 'Home/Media/downloader/$1';
$route['Home/Media'] = 'Home/Media/index';
$route['Home/Contact'] = 'Home/Contact/index';
$route['contact'] = 'Home/Contact/index';
$route['shop'] = 'Home/Shop/index';
$route['shop/category/(:any)'] = 'Home/Shop/category/$1';
$route['shop/detail/(:any)'] = 'Home/Shop/detail/$1';
$route['shop/apiProducts'] = 'Home/Shop/apiProducts';
$route['search/ajax_search'] = 'Home/Search/ajax_search';
