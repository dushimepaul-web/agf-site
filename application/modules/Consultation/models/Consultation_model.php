<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    private function _medecin_query()
    {
        $this->db->select('medecins.*, users.nom, users.prenom, users.email, users.telephone, users.photo, users.id as user_id');
        $this->db->join('users', 'users.id = medecins.user_id');
    }

    public function get_medecins($only_active = true)
    {
        $this->_medecin_query();
        if ($only_active) {
            $this->db->where('medecins.actif', 1);
        }
        $this->db->order_by('users.nom', 'ASC');
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->result_array() : [];
    }

    public function get_medecin($id)
    {
        $this->_medecin_query();
        $this->db->where('medecins.id', $id);
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->row_array() : null;
    }

    public function get_medecin_by_user_id($user_id)
    {
        $this->_medecin_query();
        $this->db->where('medecins.user_id', $user_id);
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->row_array() : null;
    }

    public function get_medecin_by_uuid($uuid)
    {
        $this->_medecin_query();
        $this->db->where('medecins.uuid', $uuid);
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->row_array() : null;
    }

    public function get_horaires($medecin_id)
    {
        $this->db->where('horaires_medecins.medecin_id', $medecin_id);
        $this->db->where('horaires_medecins.est_actif', 1);
        $this->db->order_by('horaires_medecins.heure_debut', 'ASC');
        $q = $this->db->get('horaires_medecins');
        $results = ($q !== false) ? $q->result_array() : [];

        $jourOrder = self::JOUR_ORDER;
        usort($results, function($a, $b) use ($jourOrder) {
            $oa = $jourOrder[$a['jour_semaine']] ?? 99;
            $ob = $jourOrder[$b['jour_semaine']] ?? 99;
            return $oa <=> $ob ?: strcmp($a['heure_debut'], $b['heure_debut']);
        });
        return $results;
    }

    public function group_horaires_by_day($horaires)
    {
        $grouped = [];
        foreach ($horaires as $h) {
            $grouped[$h['jour_semaine']][] = $h;
        }
        return $grouped;
    }

    public function create_consultation($data)
    {
        return $this->create('consultations', $data, true);
    }

    public function add_media($data)
    {
        return $this->create('consultation_medias', $data, true);
    }

    public function get_consultation($id)
    {
        return $this->read_one('consultations', ['id' => $id]);
    }

    public function get_medias($consultation_id)
    {
        $this->db->where('consultation_id', $consultation_id);
        $this->db->order_by('ordre', 'ASC');
        $q = $this->db->get('consultation_medias');
        return ($q !== false) ? $q->result_array() : [];
    }

    const JOUR_ORDER = [
        'lundi' => 1, 'mardi' => 2, 'mercredi' => 3,
        'jeudi' => 4, 'vendredi' => 5, 'samedi' => 6, 'dimanche' => 7
    ];

    const JOUR_LABELS = [
        'lundi' => 'Lundi', 'mardi' => 'Mardi', 'mercredi' => 'Mercredi',
        'jeudi' => 'Jeudi', 'vendredi' => 'Vendredi', 'samedi' => 'Samedi', 'dimanche' => 'Dimanche'
    ];
}
