<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambahkan index untuk query yang paling sering dijalankan.
     */
    public function up(): void
    {
        // Index pada billings: query user + bulan + tahun sangat sering dipakai
        Schema::table('billings', function (Blueprint $table) {
            $table->index(['user_id', 'tahun', 'bulan'], 'billings_user_tahun_bulan_idx');
            $table->index('status', 'billings_status_idx');
        });

        // Index pada koperasi_transactions: sering filter by user & status
        Schema::table('koperasi_transactions', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'koperasi_trx_user_status_idx');
        });

        // Index pada koperasi_loans: sering filter by user & status
        Schema::table('koperasi_loans', function (Blueprint $table) {
            $table->index(['user_id', 'status'], 'koperasi_loans_user_status_idx');
        });

        // Index pada koperasi_loan_installments: sering filter by loan & status
        Schema::table('koperasi_loan_installments', function (Blueprint $table) {
            $table->index(['koperasi_loan_id', 'status'], 'koperasi_inst_loan_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropIndex('billings_user_tahun_bulan_idx');
            $table->dropIndex('billings_status_idx');
        });

        Schema::table('koperasi_transactions', function (Blueprint $table) {
            $table->dropIndex('koperasi_trx_user_status_idx');
        });

        Schema::table('koperasi_loans', function (Blueprint $table) {
            $table->dropIndex('koperasi_loans_user_status_idx');
        });

        Schema::table('koperasi_loan_installments', function (Blueprint $table) {
            $table->dropIndex('koperasi_inst_loan_status_idx');
        });
    }
};
