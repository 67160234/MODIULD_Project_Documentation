<?php
namespace App\Config;

class Config
{
    // Database
    public static string $DB_HOST;
    public static string $DB_PORT;
    public static string $DB_NAME;
    public static string $DB_USER;
    public static string $DB_PASS;

    // JWT
    public static string $JWT_SECRET;
    public static int $JWT_EXPIRE;

    public static function load(): void
    {
        self::$DB_HOST   = $_ENV['DB_HOST']   ?? getenv('DB_HOST')   ?? 'mysql';
        self::$DB_PORT   = $_ENV['DB_PORT']   ?? getenv('DB_PORT')   ?? '3306';
        self::$DB_NAME   = $_ENV['DB_NAME']   ?? getenv('DB_NAME')   ?? 'modiuld_db';
        self::$DB_USER   = $_ENV['DB_USER']   ?? getenv('DB_USER')   ?? 'modiuld_user';
        self::$DB_PASS   = $_ENV['DB_PASS']   ?? getenv('DB_PASS')   ?? 'modiuld_pass';
        self::$JWT_SECRET = $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET') ?? 'modiuld_secret';
        self::$JWT_EXPIRE = (int)($_ENV['JWT_EXPIRE'] ?? getenv('JWT_EXPIRE') ?? 86400);
    }
}
