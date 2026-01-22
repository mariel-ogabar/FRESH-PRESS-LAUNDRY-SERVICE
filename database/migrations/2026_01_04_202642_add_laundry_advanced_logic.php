<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void {
        
        // 1. Remove existings to avoid conflicts
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessPayment");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_LaundryStatus_Audit");

        // 2. Automated History Tracking
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

        // 3. Atomic Payment Processing
        DB::unprepared("
            CREATE PROCEDURE sp_ProcessPayment(IN target_order_id INT)
            BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION
                BEGIN
                    ROLLBACK;
                END;

                START TRANSACTION;
                    -- Update Payment Record
                    UPDATE payments 
                    SET payment_status = 'PAID', payment_date = NOW() 
                    WHERE order_id = target_order_id;

                    -- Update Main Order Status
                    UPDATE orders 
                    SET order_status = 'COMPLETED' 
                    WHERE id = target_order_id;
                COMMIT;
            END
        ");

        // Dashbard order summary.
        DB::unprepared("
            CREATE OR REPLACE VIEW vw_order_summary AS
            SELECT 
                o.id AS order_id, 
                u.name AS customer_name, 
                o.total_price, 
                o.order_status, 
                ls.current_status AS laundry_stage,
                p.payment_status,
                o.created_at -- Idagdag ito para sa sorting sa UI
            FROM orders o
            JOIN users u ON o.user_id = u.id
            JOIN laundry_statuses ls ON o.id = ls.order_id
            LEFT JOIN payments p ON o.id = p.order_id
        ");
    }

    public function down(): void {
        DB::unprepared("DROP TRIGGER IF EXISTS trg_LaundryStatus_Audit");
        DB::unprepared("DROP PROCEDURE IF EXISTS sp_ProcessPayment");
        DB::unprepared("DROP VIEW IF EXISTS vw_order_summary");
    }
};