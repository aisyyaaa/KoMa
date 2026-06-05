<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement('PRAGMA legacy_alter_table = ON'); // prevent FK refs from updating on rename

        DB::statement("ALTER TABLE orders RENAME TO orders_old");

        DB::statement("
            CREATE TABLE orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL,
                address TEXT NOT NULL,
                payment_method VARCHAR(255) NOT NULL DEFAULT 'COD',
                status VARCHAR(255) CHECK (status IN ('Menunggu','Diproses','Dikirim','Selesai')) NOT NULL DEFAULT 'Menunggu',
                total DECIMAL(12,2) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        DB::statement("
            INSERT INTO orders (id, user_id, name, phone, address, payment_method, status, total, created_at, updated_at)
            SELECT id, user_id, name, phone, address, payment_method,
                CASE
                    WHEN status IN ('Dikemas') THEN 'Menunggu'
                    WHEN status IN ('Dikirim') THEN 'Dikirim'
                    WHEN status IN ('Selesai') THEN 'Selesai'
                    ELSE 'Menunggu'
                END,
                COALESCE(total, 0), created_at, updated_at
            FROM orders_old
        ");

        DB::statement("DROP TABLE orders_old");
        DB::statement('PRAGMA legacy_alter_table = OFF');
        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');
        DB::statement('PRAGMA legacy_alter_table = ON');

        DB::statement("ALTER TABLE orders RENAME TO orders_old");

        DB::statement("
            CREATE TABLE orders (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                name VARCHAR(255) NOT NULL,
                phone VARCHAR(255) NOT NULL,
                address TEXT NOT NULL,
                payment_method VARCHAR(255) NOT NULL DEFAULT 'COD',
                status VARCHAR(255) CHECK (status IN ('Dikemas','Dikirim','Selesai')) NOT NULL DEFAULT 'Dikemas',
                total DECIMAL(12,2) NOT NULL DEFAULT 0,
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ");

        DB::statement("
            INSERT INTO orders (id, user_id, name, phone, address, payment_method, status, total, created_at, updated_at)
            SELECT id, user_id, name, phone, address, payment_method,
                CASE
                    WHEN status IN ('Menunggu','Diproses') THEN 'Dikemas'
                    ELSE status
                END,
                total, created_at, updated_at
            FROM orders_old
        ");

        DB::statement("DROP TABLE orders_old");
        DB::statement('PRAGMA legacy_alter_table = OFF');
        DB::statement('PRAGMA foreign_keys = ON');
    }
};
