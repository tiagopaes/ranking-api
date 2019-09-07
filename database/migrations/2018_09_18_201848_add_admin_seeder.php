<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;

class AddAdminSeeder extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('type')->default('default');
        });

        $adminUser = User::where(
            'email',
            env('ADMIN_EMAIL', 'admin@example.com')
        )->first();

        if ($adminUser) {
            $adminUser->type = User::ADMIN_TYPE;
            $adminUser->save();
        } else {
            $adminUser = User::create([            
                'name' => 'Admin',
                'email' => env('ADMIN_EMAIL', 'admin@example.com'),            
                'password' => bcrypt(env('ADMIN_PASSWORD', '123456')),
                'api_token' => str_random(60)
            ]);
            $adminUser->type = User::ADMIN_TYPE;
            $adminUser->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
