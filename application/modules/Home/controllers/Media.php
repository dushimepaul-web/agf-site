<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('Model');
        $this->load->helper('text');
        $this->load->helper('string');

        if ($this->input->is_ajax_request()) {
            $this->config->set_item('csrf_protection', FALSE);
        }
    }

    private function getCurrentUser()
    {
        if ($this->session->userdata('user_id')) {
            return $this->db->query("
                SELECT id, uuid, email, nom, prenom, photo, type_utilisateur 
                FROM users 
                WHERE id = ? AND is_active = 1
            ", [$this->session->userdata('user_id')])->row_array();
        }
        return null;
    }

    public function index()
    {
        $user = $this->getCurrentUser();

        $medias = $this->db->query("
            SELECT g.*,
                   (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
            FROM galerie_medias g
            WHERE g.est_actif = 1
            ORDER BY g.created_at DESC
        ")->result_array();
        $medias = $this->formatMedias($medias);
        $categories = $this->getCategoriesWithCount();

        $data = [
            'medias' => $medias,
            'categories' => $categories,
            'current_type' => null,
            'search_query' => null,
            'results_count' => count($medias),
            'user' => $user
        ];

        $this->load->view('Media_View', $data);
    }

    public function trending()
    {
        $this->type('video');
    }

    public function news()
    {
        $data = [
            'medias' => [],
            'categories' => $this->getCategoriesWithCount(),
            'current_type' => null,
            'search_query' => null,
            'results_count' => 0,
            'page_title' => 'News',
            'user' => $this->getCurrentUser()
        ];
        $this->load->view('Media_View', $data);
    }

    public function temoignages()
    {
        $temoignages = $this->db->query("
            SELECT id, titre, video_url, miniature, auteur, description
            FROM temoignages
            WHERE est_actif = 1
            ORDER BY ordre ASC, created_at DESC
        ")->result_array();

        $data = [
            'medias' => [],
            'categories' => $this->getCategoriesWithCount(),
            'current_type' => null,
            'search_query' => null,
            'results_count' => 0,
            'show_temoignages' => true,
            'temoignages' => $temoignages,
            'page_title' => 'Testimonials',
            'user' => $this->getCurrentUser()
        ];
        $this->load->view('Media_View', $data);
    }

    public function type($type)
    {
        $valid_types = ['video', 'audio', 'image', 'document', 'link', 'book'];
        if (!in_array($type, $valid_types)) {
            show_404();
            return;
        }

        $user = $this->getCurrentUser();

        $db_type = ($type === 'book') ? 'document' : $type;

        if ($type === 'video') {
            $medias = $this->db->query("
                SELECT g.*,
                       (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count,
                       1 as is_video_content
                FROM galerie_medias g
                WHERE g.est_actif = 1 
                AND (
                    g.type = 'video' 
                    OR (g.type = 'link' AND g.lien IS NOT NULL AND (
                        g.lien LIKE '%youtube%' OR g.lien LIKE '%youtu.be%' OR 
                        g.lien LIKE '%vimeo%' OR g.lien LIKE '%dailymotion%'
                    ))
                )
                ORDER BY CASE WHEN g.type = 'video' THEN 0 ELSE 1 END, g.created_at DESC
            ")->result_array();
        } elseif ($type === 'book') {
            $medias = $this->db->query("
                SELECT g.*,
                       (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
                FROM galerie_medias g
                WHERE g.est_actif = 1 AND g.type = 'document' AND g.sous_type = 'book'
                ORDER BY g.created_at DESC
            ")->result_array();
        } else {
            $medias = $this->db->query("
                SELECT g.*,
                       (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
                FROM galerie_medias g
                WHERE g.est_actif = 1 AND g.type = ?
                ORDER BY g.created_at DESC
            ", [$db_type])->result_array();
        }

        $medias = $this->formatMedias($medias);
        $categories = $this->getCategoriesWithCount();

        $data = [
            'medias' => $medias,
            'categories' => $categories,
            'current_type' => $type,
            'page_title' => ucfirst($type),
            'search_query' => null,
            'results_count' => count($medias),
            'user' => $user
        ];

        $this->load->view('Media_View', $data);
    }

    public function detail($identifier)
    {
        $user = $this->getCurrentUser();

        if (is_numeric($identifier)) {
            $media = $this->db->query("
                SELECT g.*,
                       (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
                FROM galerie_medias g
                WHERE g.id_media = ? AND g.est_actif = 1
            ", [$identifier])->row_array();
        } else {
            $media = $this->db->query("
                SELECT g.*,
                       (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
                FROM galerie_medias g
                WHERE g.slug = ? AND g.est_actif = 1
            ", [$identifier])->row_array();
        }

        if (!$media) {
            show_404();
            return;
        }

        $media = $this->formatMedia($media);

        if (is_numeric($identifier) && !empty($media['slug'])) {
            redirect('media/detail/' . $media['slug'], 'location', 301);
            return;
        }

        $this->recordView($media['id_media']);

        $playlist = $this->db->query("
            SELECT id_media, slug, type, titre
            FROM galerie_medias
            WHERE est_actif = 1 AND type = ?
            ORDER BY created_at DESC
        ", [$media['type']])->result_array();

        $recommended = $this->db->query("
            SELECT g.id_media, g.slug, g.titre, g.type, g.credits, g.miniature, g.fichier, g.lien,
                   (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
            FROM galerie_medias g
            WHERE g.est_actif = 1 AND g.type = ? AND g.id_media != ?
            ORDER BY RAND()
            LIMIT 8
        ", [$media['type'], $media['id_media']])->result_array();
        $recommended = $this->formatMedias($recommended);

        $categories = $this->getCategoriesWithCount();

        $data = [
            'media' => $media,
            'categories' => $categories,
            'user' => $user,
            'playlist' => $playlist,
            'recommended' => $recommended
        ];

        $this->load->view('Media_Detail_View', $data);
    }

    public function apiTrackView()
    {
        $id_media = $this->input->post('id_media');
        $session_id = session_id();
        $ip_address = $this->input->ip_address();
        $user_agent = $this->input->user_agent();
        $user_id = $this->session->userdata('user_id') ?: null;

        $viewed = $this->db->query("
            SELECT id FROM media_views 
            WHERE id_media = ? AND session_id = ? 
            LIMIT 1
        ", [$id_media, $session_id])->num_rows();

        if (!$viewed) {
            $this->db->insert('media_views', [
                'id_media' => $id_media,
                'user_id' => $user_id,
                'session_id' => $session_id,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'viewed_at' => date('Y-m-d H:i:s')
            ]);
        }

        $views = $this->db->query("
            SELECT COUNT(*) as count FROM media_views WHERE id_media = ?
        ", [$id_media])->row()->count;

        echo json_encode(['success' => true, 'views' => $views]);
    }

    public function apiSearch()
    {
        $query = $this->input->get('q');
        $limit = (int)($this->input->get('limit') ?? 10);

        if (empty($query) || strlen($query) < 2) {
            redirect('media');
            return;
        }

        $like = '%' . $this->db->escape_like_str($query) . '%';

        $medias = $this->db->query("
            SELECT g.id_media, g.titre, g.type, g.slug, g.miniature,
                   g.description,
                   (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
            FROM galerie_medias g
            WHERE g.est_actif = 1 
            AND (g.titre LIKE ? OR g.credits LIKE ? OR g.description LIKE ?)
            ORDER BY CASE WHEN g.titre LIKE ? THEN 10 ELSE 1 END DESC
            LIMIT ?
        ", [$like, $like, $like, $like, $limit])->result_array();

        $data = [
            'search_query' => $query,
            'results_count' => count($medias),
            'medias' => $medias,
            'categories' => $this->getCategoriesWithCount(),
            'user' => $this->getCurrentUser(),
            'current_type' => null
        ];

        $this->load->view('Media_View', $data);
    }

    public function liveSearch()
    {
        $query = trim((string) $this->input->get('q'));

        if (empty($query) || mb_strlen($query) < 2) {
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['success' => true, 'html' => '']);
            return;
        }

        $like = '%' . $this->db->escape_like_str($query) . '%';

        $medias = $this->db->query("
            SELECT g.id_media, g.titre, g.slug, g.type, g.fichier, g.lien, g.miniature,
                   g.date_media, g.taille, g.duree, g.credits, g.categorie,
                   (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
            FROM galerie_medias g
            WHERE g.est_actif = 1
            AND (g.titre LIKE ? OR g.credits LIKE ? OR g.description LIKE ? OR g.categorie LIKE ?)
            ORDER BY CASE WHEN g.titre LIKE ? THEN 10 ELSE 1 END DESC, g.created_at DESC LIMIT 12
        ", [$like, $like, $like, $like, $like])->result_array();

        $html = '';
        if (!empty($medias)) {
            $medias = $this->formatMedias($medias);
            foreach ($medias as $m) {
                $html .= $this->_renderSearchItem($m);
            }
        } else {
            $html = '<div class="search-no-result"><i class="bi bi-search"></i><p>No results</p></div>';
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['success' => true, 'html' => $html, 'count' => count($medias)]);
        exit;
    }

    private function _renderSearchItem($media)
    {
        $type  = $media['type'] ?? 'link';
        $title = htmlspecialchars($media['titre'] ?? '');
        $channel = htmlspecialchars($media['credits'] ?? $media['categorie'] ?? 'A.G.F');
        $views = number_format($media['views_count'] ?? 0);
        $slug  = $media['slug'] ?? $media['id_media'];

        if (!empty($media['miniature'])) {
            $thumb = (strpos($media['miniature'], 'http') === 0)
                ? $media['miniature']
                : base_url($media['miniature']);
        } else {
            $defaults = [
                'audio'    => 'assets/backend/images/defaut-mignature-audio.jpeg',
                'video'    => 'assets/backend/images/defaut-mignature-video.jpeg',
                'image'    => 'assets/backend/images/default-avatar.jpg',
                'document' => 'assets/backend/images/default-avatar-pdf-mignature.png',
                'link'     => 'assets/backend/images/default-avatar.jpg',
            ];
            $thumb = base_url($defaults[$type] ?? $defaults['link']);
        }

        $duration = '';
        if (in_array($type, ['audio', 'video']) && !empty($media['duree'])) {
            $d = (int) $media['duree'];
            $h = floor($d / 3600);
            $m = floor(($d % 3600) / 60);
            $s = $d % 60;
            $duration = $h > 0 ? sprintf('%d:%02d:%02d', $h, $m, $s) : sprintf('%d:%02d', $m, $s);
            $duration = '<span class="search-duration">' . $duration . '</span>';
        }

        $typeIcon = [
            'audio' => 'bi-music-note-beamed',
            'video' => 'bi-play-btn-fill',
            'image' => 'bi-image',
            'document' => 'bi-file-earmark-text',
            'link'  => 'bi-link-45deg',
        ];
        $icon = $typeIcon[$type] ?? 'bi-file';
        $url = base_url('media/detail/' . $slug);

        return '
        <a href="' . $url . '" class="search-item">
            <div class="search-item-thumb">
                <img src="' . $thumb . '" alt="' . $title . '" loading="lazy">
                ' . $duration . '
            </div>
            <div class="search-item-info">
                <div class="search-item-title"><i class="bi ' . $icon . '"></i> ' . $title . '</div>
                <div class="search-item-meta">' . $channel . ' &middot; ' . $views . ' views</div>
            </div>
        </a>';
    }

    public function apiGrid()
    {
        $type = $this->input->get('type');
        $query = $this->input->get('q');
        $page = max(1, (int)($this->input->get('page') ?? 1));
        $limit = 20;
        $offset = ($page - 1) * $limit;

        $where = 'g.est_actif = 1';
        $params = [];

        if (!empty($type) && $type !== 'all') {
            if ($type === 'book') {
                $where .= " AND g.type = 'document' AND g.sous_type = 'book'";
            } elseif ($type === 'video') {
                $where .= " AND (g.type = 'video' OR (g.type = 'link' AND g.lien IS NOT NULL AND (g.lien LIKE '%youtube%' OR g.lien LIKE '%youtu.be%' OR g.lien LIKE '%vimeo%')))";
            } else {
                $where .= " AND g.type = ?";
                $params[] = $type;
            }
        }

        if (!empty($query) && strlen($query) >= 2) {
            $like = '%' . $this->db->escape_like_str($query) . '%';
            $where .= " AND (g.titre LIKE ? OR g.credits LIKE ? OR g.description LIKE ? OR g.categorie LIKE ?)";
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        $count_params = $params;
        $total = $this->db->query("SELECT COUNT(*) as cnt FROM galerie_medias g WHERE $where", $count_params)->row()->cnt;

        $params[] = $limit;
        $params[] = $offset;
        $medias = $this->db->query("
            SELECT g.*,
                   (SELECT COUNT(*) FROM media_views WHERE id_media = g.id_media) as views_count
            FROM galerie_medias g
            WHERE $where
            ORDER BY g.created_at DESC
            LIMIT ? OFFSET ?
        ", $params)->result_array();

        $medias = $this->formatMedias($medias);

        foreach ($medias as &$m) {
            $m['card_html'] = $this->_renderMediaCard($m);
        }
        unset($m);

        $categories = $this->getCategoriesWithCount();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => true,
            'medias' => $medias,
            'categories' => $categories,
            'total' => (int)$total,
            'page' => $page,
            'has_more' => ($offset + $limit) < $total
        ]);
        exit;
    }

    private function _renderMediaCard($media)
    {
        $type = $media['type'] ?? 'link';
        $identifier = !empty($media['slug']) ? $media['slug'] : $media['id_media'];
        $title = htmlspecialchars($media['titre'] ?? 'Sans titre');
        $channel = htmlspecialchars($media['credits'] ?? $media['categorie'] ?? 'A.G.F');
        $views = number_format($media['views_count'] ?? 0);
        $duration = $media['duration_formatted'] ?? '0:00';
        $thumb = $media['thumbnail_url'] ?? '';

        $typeIcons = [
            'video' => 'bi-play-circle-fill',
            'audio' => 'bi-music-note-beamed',
            'image' => 'bi-image-fill',
            'document' => 'bi-file-earmark-text',
            'link' => 'bi-link-45deg',
        ];
        $icon = $typeIcons[$type] ?? 'bi-file';

        $badge = '';
        if ($type === 'video') $badge = '<span class="card-badge card-badge-video"><i class="bi bi-camera-video-fill"></i> Video</span>';
        elseif ($type === 'audio') $badge = '<span class="card-badge card-badge-audio"><i class="bi bi-music-note-beamed"></i> Audio</span>';
        elseif ($type === 'image') $badge = '<span class="card-badge card-badge-image"><i class="bi bi-image-fill"></i> Image</span>';
        elseif ($type === 'document') $badge = '<span class="card-badge card-badge-doc"><i class="bi bi-file-earmark-text"></i> Doc</span>';

        $escapeId = addslashes($identifier);

        return '
        <div class="media-card" onclick="openMedia(\'' . $escapeId . '\')">
            <div class="thumbnail-container" style="background-image: url(\'' . htmlspecialchars($thumb) . '\')">
                <div class="play-overlay">
                    <div class="play-icon-wrap">
                        <i class="bi bi-play-fill"></i>
                    </div>
                </div>
                ' . ($duration !== '0:00' ? '<span class="duration-badge">' . $duration . '</span>' : '') . '
                ' . $badge . '
            </div>
            <div class="card-info">
                <div class="card-avatar">
                    <i class="bi ' . $icon . '"></i>
                </div>
                <div class="card-details">
                    <div class="card-title">' . $title . '</div>
                    <div class="card-meta">
                        <span class="card-channel">' . $channel . '</span>
                        <span class="card-sep">&bull;</span>
                        <span class="card-views"><i class="bi bi-eye"></i> ' . $views . ' vues</span>
                    </div>
                </div>
            </div>
        </div>';
    }

    public function downloader($identifier = null)
    {
        if (empty($identifier)) {
            $identifier = $this->input->get('slug') ?? $this->input->get('id');
        }
        if (empty($identifier)) {
            show_404();
            return;
        }

        if (is_numeric($identifier)) {
            $media = $this->db->query("
                SELECT id_media, fichier, titre, type, taille, slug
                FROM galerie_medias 
                WHERE id_media = ? AND est_actif = 1
            ", [$identifier])->row_array();
        } else {
            $media = $this->db->query("
                SELECT id_media, fichier, titre, type, taille, slug
                FROM galerie_medias 
                WHERE slug = ? AND est_actif = 1
            ", [$identifier])->row_array();
        }

        if (!$media || empty($media['fichier'])) {
            show_404();
            return;
        }

        $base = FCPATH;
        $file_path = '';
        $found = false;

        switch($media['type']) {
            case 'video':
                $possible_paths = [
                    $base . 'attachments/Video/Originals/' . $media['fichier'],
                    $base . 'attachments/Video/Encoded/' . $media['fichier'],
                ];
                break;
            case 'audio':
                $possible_paths = [
                    $base . 'attachments/Audio/Originals/' . $media['fichier'],
                    $base . 'attachments/Audio/Converted/' . $media['fichier'],
                ];
                break;
            case 'image':
                $possible_paths = [
                    $base . 'attachments/Image/' . $media['fichier'],
                ];
                break;
            default:
                $possible_paths = [
                    $base . 'attachments/Documents/' . $media['fichier'],
                    $base . $media['fichier'],
                ];
                break;
        }

        foreach ($possible_paths as $path) {
            if (file_exists($path) && is_file($path)) {
                $file_path = $path;
                $found = true;
                break;
            }
        }

        if (!$found) {
            show_404();
            return;
        }

        $extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
        $mime_types = [
            'mp3' => 'audio/mpeg', 'mp4' => 'video/mp4', 'm4a' => 'audio/mp4',
            'wav' => 'audio/wav', 'ogg' => 'audio/ogg', 'webm' => 'audio/webm',
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
            'gif' => 'image/gif', 'webp' => 'image/webp', 'pdf' => 'application/pdf',
        ];
        $mime_type = $mime_types[$extension] ?? mime_content_type($file_path) ?? 'application/octet-stream';
        $filename = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $media['titre']) . '.' . $extension;

        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file_path));
        header('Cache-Control: no-cache, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        if (ob_get_level()) ob_end_clean();
        $handle = fopen($file_path, 'rb');
        if (!$handle) show_404();
        while (!feof($handle)) {
            echo fread($handle, 8192);
            flush();
        }
        fclose($handle);
        exit;
    }

    // ==================== PRIVATE ====================

    private function recordView($id_media)
    {
        $session_id = session_id();
        $user_id = $this->session->userdata('user_id') ?: null;
        $viewed = $this->db->query("
            SELECT id FROM media_views 
            WHERE id_media = ? AND session_id = ? 
            LIMIT 1
        ", [$id_media, $session_id])->num_rows();

        if (!$viewed) {
            $this->db->insert('media_views', [
                'id_media' => $id_media,
                'user_id' => $user_id,
                'session_id' => $session_id,
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent(),
                'viewed_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    private function formatMedias($medias)
    {
        return array_map([$this, 'formatMedia'], $medias);
    }

    private function formatMedia($media)
    {
        $media['duration_formatted'] = $this->formatDuration($media['duree'] ?? 0);
        $media['youtube_id'] = $this->extractYoutubeId($media['lien'] ?? '');
        $media['fichier_url'] = !empty($media['fichier']) ? base_url($media['fichier']) : '';
        $media['thumbnail_url'] = $this->getThumbnailUrl($media);
        $media['views_formatted'] = $this->formatNumber($media['views_count'] ?? 0);
        return $media;
    }

    private function getThumbnailUrl($media)
    {
        if (!empty($media['youtube_id'])) {
            return "https://img.youtube.com/vi/{$media['youtube_id']}/hqdefault.jpg";
        }
        if (!empty($media['miniature']) && filter_var($media['miniature'], FILTER_VALIDATE_URL) === false) {
            return base_url($media['miniature']);
        } elseif (!empty($media['miniature'])) {
            return $media['miniature'];
        }
        if ($media['type'] === 'image' && !empty($media['fichier'])) {
            return base_url($media['fichier']);
        }
        $defaults = [
            'audio' => 'assets/backend/images/defaut-mignature-audio.jpeg',
            'video' => 'assets/backend/images/defaut-mignature-video.jpeg',
            'image' => 'assets/backend/images/default-avatar.jpg',
            'document' => 'assets/backend/images/default-avatar-pdf-mignature.png',
            'link' => 'assets/backend/images/default-avatar.jpg',
        ];
        return base_url($defaults[$media['type']] ?? 'assets/backend/images/default-avatar.jpg');
    }

    private function getCategoriesWithCount()
    {
        return $this->db->query("
            SELECT g.categorie as categorie, COUNT(*) as count 
            FROM galerie_medias g
            WHERE g.est_actif = 1 AND g.categorie IS NOT NULL AND g.categorie != ''
            GROUP BY g.categorie
            ORDER BY count DESC
        ")->result_array();
    }

    private function extractYoutubeId($url)
    {
        if (empty($url)) return null;
        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    }

    private function formatDuration($seconds)
    {
        if (!$seconds || $seconds <= 0) return '0:00';
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = floor($seconds % 60);
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $secs);
        }
        return sprintf('%d:%02d', $minutes, $secs);
    }

    private function formatNumber($number)
    {
        if ($number >= 1000000) return round($number / 1000000, 1) . 'M';
        if ($number >= 1000) return round($number / 1000, 1) . 'k';
        return (string)$number;
    }
}
