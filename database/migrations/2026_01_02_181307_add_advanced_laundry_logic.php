<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {

        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessPayment");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_LaundryStatus_Audit");

        // 1. THE AUDIT TRIGGER: Automates Phase 2's Audit Table
        DB::unprepared("
            CREATE TRIGGER trg_LaundryStatus_Audit
            AFTER UPDATE ON laundry_statuses
            FOR EACH ROW
            BEGIN
                IF OLD.current_status <> NEW.current_status THEN
                    INSERT INTO laundry_status_audits (order_id, old_status, new_status, changed_at)
                    VALUES (NEW.order_id, OLD.current_status, NEW.current_status, NOW());
                END IF;
            END
        ");

        // 2. THE STORED PROCEDURE: Atomic Payment & Completion
        DB::unprepared("
            CREATE PROCEDURE sp_ProcessPayment(IN target_order_id INT)
            BEGIN
                -- Update the Payment Table
                UPDATE payments 
                SET payment_status = 'PAID', payment_date = NOW() 
                WHERE order_id = target_order_id;

                -- Update the Order Status to Completed
                UPDATE orders 
                SET order_status = 'COMPLETED' 
                WHERE id = target_order_id;
            END
        ");

        DB::unprepared("
            CREATE OR REPLACE VIEW vw_order_summary AS
            SELECT 
                o.id AS order_id, 
                u.name AS customer_name, 
                o.total_price, 
                o.order_status, 
                ls.current_status AS laundry_stage
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN laundry_statuses ls ON o.id = ls.order_id
        ");

    }

    public function down(): void {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_LaundryStatus_Audit");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessPayment");
    }
};