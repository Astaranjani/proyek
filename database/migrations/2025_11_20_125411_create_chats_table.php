        <?php

        use Illuminate\Database\Migrations\Migration;
        use Illuminate\Database\Schema\Blueprint;
        use Illuminate\Support\Facades\Schema;

        return new class extends Migration
        {
            /**
             * Run the migrations.
             */
            public function up()
        {
            Schema::create('chats', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Nullable jika tamu bisa chat
                $table->text('message'); // Pesan dari user
                $table->text('reply')->nullable(); // Balasan dari bot
                $table->timestamps();
            });
        }

            /**
             * Reverse the migrations.
             */
            public function down(): void
            {
                Schema::dropIfExists('chats');
            }

        
        };

