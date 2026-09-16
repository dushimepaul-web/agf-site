<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Consultation_model extends Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_medecins()
    {
        $this->db->select('medecins.*, users.nom, users.prenom, users.email, users.id as user_id');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('medecins.actif', 1);
        $this->db->order_by('users.nom', 'ASC');
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->result_array() : [];
    }

    public function get_medecin($id)
    {
        $this->db->select('medecins.*, users.nom, users.prenom, users.email, users.telephone, users.id as user_id');
        $this->db->join('users', 'users.id = medecins.user_id');
        $this->db->where('medecins.id', $id);
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->row_array() : null;
    }

    public function get_medecin_by_user_id($user_id)
    {
        $this->db->where('user_id', $user_id);
        $q = $this->db->get('medecins');
        return ($q !== false) ? $q->row_array() : null;
    }

    public function get_horaires($medecin_id)
    {
        $this->db->where('horaires_medecins.medecin_id', $medecin_id);
        $this->db->where('horaires_medecins.disponible', 1);
        $this->db->order_by('horaires_medecins.jour', 'ASC');
        $this->db->order_by('horaires_medecins.heure_debut', 'ASC');
        $q = $this->db->get('horaires_medecins');
        $results = ($q !== false) ? $q->result_array() : [];
        
        // Tri manuel des jours en français
        $jourOrder = ['lundi'=>1,'mardi'=>2,'mercredi'=>3,'jeudi'=>4,'vendredi'=>5,'samedi'=>6,'dimanche'=>7];
        usort($results, function($a, $b) use ($jourOrder) {
            $oa = $jourOrder[$a['jour']] ?? 99;
            $ob = $jourOrder[$b['jour']] ?? 99;
            return $oa <=> $ob ?: strcmp($a['heure_debut'], $b['heure_debut']);
        });
        return $results;
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

    public function count_consultations()
    {
        return $this->db->count_all('consultations');
    }
}
