<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('name', 'nama');
            $table->string('alamat')->nullable();
            $table->string('no_ktp')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('no_rm')->nullable();
            $table->enum('role', ['admin', 'dokter', 'pasien'])->default('pasien');
            $table->foreignId('id_poli')->nullable()->constrained('poli')->onDelete('set null');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['id_poli']);
            $table->dropColumn(['alamat', 'no_ktp', 'no_hp', 'no_rm', 'role', 'id_poli']);
            $table->renameColumn('nama', 'name');
        });
    }
};
