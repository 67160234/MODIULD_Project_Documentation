<?php
require_once __DIR__ . '/../config/database.php';

class ModuleController {
    public static function index(): void {
        $db   = Database::getConnection();
        $stmt = $db->query('SELECT * FROM modules ORDER BY category, id');
        self::json(['modules' => $stmt->fetchAll()]);
    }

    public static function loadoutModules(int $loadoutId): void {
        $db   = Database::getConnection();
        $stmt = $db->prepare('SELECT m.*, lm.sort_order FROM modules m JOIN loadout_modules lm ON lm.module_id = m.id WHERE lm.loadout_id = ? ORDER BY lm.sort_order');
        $stmt->execute([$loadoutId]);
        self::json(['modules' => $stmt->fetchAll()]);
    }

    private static function json(array $data, int $status = 200): void {
        http_response_code($status);
        echo json_encode($data);
    }
}
