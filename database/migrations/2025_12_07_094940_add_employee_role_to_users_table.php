<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = \DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            // SQLite لا يدعم تعديل enum مباشرة، لذا سنستخدم DB::statement
            \DB::statement("PRAGMA foreign_keys=off;");
            \DB::statement("DROP TABLE IF EXISTS users_new;");
            \DB::statement("CREATE TABLE users_new AS SELECT * FROM users;");
            \DB::statement("DROP TABLE users;");
            \DB::statement("CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                phone VARCHAR(20),
                email_verified_at DATETIME,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(255) NOT NULL DEFAULT 'client' CHECK(role IN ('admin', 'client', 'employee')),
                remember_token VARCHAR(100),
                created_at DATETIME,
                updated_at DATETIME
            );");
            \DB::statement("INSERT INTO users SELECT * FROM users_new;");
            \DB::statement("DROP TABLE users_new;");
            \DB::statement("PRAGMA foreign_keys=on;");
        } else {
            // للقواعد الأخرى مثل MySQL
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client', 'employee') DEFAULT 'client'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = \DB::connection()->getDriverName();
        
        if ($driver === 'sqlite') {
            \DB::statement("PRAGMA foreign_keys=off;");
            \DB::statement("DROP TABLE IF EXISTS users_new;");
            \DB::statement("CREATE TABLE users_new AS SELECT * FROM users;");
            \DB::statement("DROP TABLE users;");
            \DB::statement("CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                phone VARCHAR(20),
                email_verified_at DATETIME,
                password VARCHAR(255) NOT NULL,
                role VARCHAR(255) NOT NULL DEFAULT 'client' CHECK(role IN ('admin', 'client')),
                remember_token VARCHAR(100),
                created_at DATETIME,
                updated_at DATETIME
            );");
            \DB::statement("INSERT INTO users SELECT * FROM users_new WHERE role != 'employee';");
            \DB::statement("DROP TABLE users_new;");
            \DB::statement("PRAGMA foreign_keys=on;");
        } else {
            \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'client') DEFAULT 'client'");
        }
    }
};
