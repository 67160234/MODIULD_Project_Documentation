<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../utils/jwt.php';

class LoadoutController {

    /** GET /api/loadouts */
    public static function index(): void {
        $db = Database::getConnection();
        $payload = JwtHelper::fromRequest();

        if ($payload) {
            // Authenticated: return user's own loadouts
            $stmt = $db->prepare('SELECT l.*, GROUP_CONCAT(m.name ORDER BY lm.sort_order SEPARATOR ",") AS module_names FROM loadouts l LEFT JOIN loadout_modules lm ON lm.loadout_id = l.id LEFT JOIN modules m ON m.id = lm.module_id WHERE l.user_id = ? AND l.is_guest = 0 GROUP BY l.id ORDER BY l.created_at DESC');
            $stmt->execute([$payload['sub']]);
        } else {
            // Guest: lookup by guest_token header
            $guestToken = $_SERVER['HTTP_X_GUEST_TOKEN'] ?? '';
            if (!$guestToken) { self::json(['loadouts' => []]); return; }
            $stmt = $db->prepare('SELECT l.*, GROUP_CONCAT(m.name ORDER BY lm.sort_order SEPARATOR ",") AS module_names FROM loadouts l LEFT JOIN loadout_modules lm ON lm.loadout_id = l.id LEFT JOIN modules m ON m.id = lm.module_id WHERE l.guest_token = ? AND l.is_guest = 1 GROUP BY l.id ORDER BY l.created_at DESC');
            $stmt->execute([$guestToken]);
        }
        $loadouts = $stmt->fetchAll();
        foreach ($loadouts as &$l) {
            $l['module_names'] = $l['module_names'] ? explode(',', $l['module_names']) : [];
        }
        self::json(['loadouts' => $loadouts]);
    }

    /** POST /api/loadouts */
    public static function store(): void {
        $data    = json_decode(file_get_contents('php://input'), true);
        $name    = trim($data['name'] ?? '');
        $desc    = trim($data['description'] ?? '');
        $modules = $data['module_ids'] ?? [];

        if (!$name) { self::json(['error' => 'Loadout name is required.'], 422); return; }

        $db      = Database::getConnection();
        $payload = JwtHelper::fromRequest();

        if ($payload) {
            // Authenticated user
            $ins = $db->prepare('INSERT INTO loadouts (user_id, name, description, is_guest) VALUES (?, ?, ?, 0)');
            $ins->execute([$payload['sub'], $name, $desc]);
        } else {
            // Guest: enforce 1-loadout limit
            $guestToken = $_SERVER['HTTP_X_GUEST_TOKEN'] ?? '';
            if (!$guestToken) { self::json(['error' => 'Guest token required.'], 400); return; }
            $check = $db->prepare('SELECT COUNT(*) FROM loadouts WHERE guest_token = ? AND is_guest = 1');
            $check->execute([$guestToken]);
            if ($check->fetchColumn() >= 1) {
                self::json(['error' => 'Guest accounts can create only 1 Loadout. Please register or sign in to create more.'], 403); return;
            }
            $ins = $db->prepare('INSERT INTO loadouts (name, description, is_guest, guest_token) VALUES (?, ?, 1, ?)');
            $ins->execute([$name, $desc, $guestToken]);
        }
        $loadoutId = $db->lastInsertId();

        // Associate modules
        if (!empty($modules)) {
            $insM = $db->prepare('INSERT IGNORE INTO loadout_modules (loadout_id, module_id, sort_order) VALUES (?, ?, ?)');
            foreach ($modules as $i => $mId) {
                $insM->execute([$loadoutId, (int)$mId, $i]);
            }
        }

        $stmt = $db->prepare('SELECT * FROM loadouts WHERE id = ?');
        $stmt->execute([$loadoutId]);
        self::json(['loadout' => $stmt->fetch()], 201);
    }

    /** GET /api/loadouts/{id} */
    public static function show(int $id): void {
        $db = Database::getConnection();
        [$loadout, $err, $code] = self::resolveOwnership($db, $id);
        if ($err) { self::json(['error' => $err], $code); return; }

        $stmt = $db->prepare('SELECT m.*, lm.sort_order FROM modules m JOIN loadout_modules lm ON lm.module_id = m.id WHERE lm.loadout_id = ? ORDER BY lm.sort_order');
        $stmt->execute([$id]);
        $loadout['modules'] = $stmt->fetchAll();
        self::json(['loadout' => $loadout]);
    }

    /** PUT /api/loadouts/{id} */
    public static function update(int $id): void {
        $db = Database::getConnection();
        [$loadout, $err, $code] = self::resolveOwnership($db, $id);
        if ($err) { self::json(['error' => $err], $code); return; }

        $data = json_decode(file_get_contents('php://input'), true);
        $name = trim($data['name'] ?? $loadout['name']);
        $desc = trim($data['description'] ?? $loadout['description']);
        $db->prepare('UPDATE loadouts SET name = ?, description = ?, updated_at = NOW() WHERE id = ?')->execute([$name, $desc, $id]);

        if (isset($data['module_ids'])) {
            $db->prepare('DELETE FROM loadout_modules WHERE loadout_id = ?')->execute([$id]);
            $insM = $db->prepare('INSERT IGNORE INTO loadout_modules (loadout_id, module_id, sort_order) VALUES (?, ?, ?)');
            foreach ($data['module_ids'] as $i => $mId) {
                $insM->execute([$id, (int)$mId, $i]);
            }
        }
        self::json(['message' => 'Loadout updated.']);
    }

    /** DELETE /api/loadouts/{id} */
    public static function destroy(int $id): void {
        $db = Database::getConnection();
        [, $err, $code] = self::resolveOwnership($db, $id);
        if ($err) { self::json(['error' => $err], $code); return; }
        $db->prepare('DELETE FROM loadouts WHERE id = ?')->execute([$id]);
        self::json(['message' => 'Loadout deleted.']);
    }

    private static function resolveOwnership(PDO $db, int $id): array {
        $payload    = JwtHelper::fromRequest();
        $guestToken = $_SERVER['HTTP_X_GUEST_TOKEN'] ?? '';
        $stmt       = $db->prepare('SELECT * FROM loadouts WHERE id = ?');
        $stmt->execute([$id]);
        $loadout    = $stmt->fetch();
        if (!$loadout) return [null, 'Loadout not found.', 404];
        if ($payload) {
            if ((int)$loadout['user_id'] !== (int)$payload['sub']) return [null, 'Forbidden.', 403];
        } else {
            if ($loadout['guest_token'] !== $guestToken) return [null, 'Forbidden.', 403];
        }
        return [$loadout, null, 200];
    }

    private static function json(array $data, int $status = 200): void {
        http_response_code($status);
        echo json_encode($data);
    }
}
