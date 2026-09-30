<?php

namespace Database\Seeders;

use App\Models\Agent;
use App\Models\Call;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(['username' => 'admin'], [
            'name' => 'Хэлтсийн дарга (Админ)', 'email' => 'admin@call.test',
            'password' => 'password123', 'role' => 'admin', 'status' => 'active',
        ]);

        User::updateOrCreate(['username' => 'operator'], [
            'name' => 'Оператор Энхээ', 'email' => 'operator@call.test',
            'password' => 'password123', 'role' => 'operator', 'status' => 'active',
        ]);

        $engineer = User::updateOrCreate(['username' => 'engineer'], [
            'name' => 'Инженер Болд', 'email' => 'engineer@call.test',
            'password' => 'password123', 'role' => 'agent', 'status' => 'active',
        ]);

        Agent::updateOrCreate(['user_id' => $engineer->id], [
            'name' => $engineer->name, 'title' => 'Системийн инженер', 'phone' => '99112233',
        ]);

        foreach ([
            ['agent0', 'Системийн инженер', 'Системийн инженер', '90000003'],
            ['agent1', 'Б.Ганзориг', 'Программ зохиогч', '80801188'], ['agent2', 'Б.Тэмүүлэн', 'Сүлжээний инженер', '88879764'],
            ['agent3', 'Б.Төгсбат', 'Сүлжээний инженер', '96620809'], ['agent4', 'Д.Ууганбаяр', 'Программ зохиогч', '99310659'],
            ['agent5', 'Ж.Өсөх-Эрдэнэ', 'Сүлжээний инженер', '99610219'], ['agent6', 'Т.Баасансүрэн', 'Программ зохиогч', '8989934'],
            ['agent7', 'Т.Билгүүн', 'Сүлжээний инженер', '99689066'], ['agent8', 'Ч.Анхтуул', 'Сүлжээний инженер', '99832929'],
            ['agent9', 'Ч.Дэлгэрбаяр', 'Программ зохиогч', '99012015'], ['agent10', 'Э.Мөнхжин', 'Программ зохиогч', '90991008'],
        ] as [$username, $name, $title, $phone]) {
            $agentUser = User::updateOrCreate(['username' => $username], ['name' => $name, 'email' => $username.'@call.test', 'password' => 'password123', 'role' => 'agent', 'status' => 'active', 'phone' => $phone]);
            Agent::updateOrCreate(['user_id' => $agentUser->id], ['name' => $name, 'title' => $title, 'phone' => $phone]);
        }

        foreach ([['customer', 'Харилцагч хэрэглэгч', '90000004'], ['borjigin', 'Боржигин Дэлгэрбаяр', '99012015'], ['baturnukh', 'Бат-Өрнөх', '99130584']] as [$username, $name, $phone]) {
            User::updateOrCreate(['username' => $username], ['name' => $name, 'email' => $username.'@call.test', 'password' => 'password123', 'role' => 'customer', 'status' => 'active', 'phone' => $phone]);
        }

        User::updateOrCreate(['username' => 'sumiya'], [
            'name' => 'Сумъяа', 'email' => 'sumiya@call.test', 'password' => 'password123',
            'role' => 'customer', 'status' => 'active', 'phone' => '90000001',
        ]);

        $customer = User::where('username', 'customer')->first();
        $overdueAgent = Agent::whereHas('user', fn ($query) => $query->where('username', 'agent1'))->first();
        Call::firstOrCreate(['description' => 'Жишээ хугацаа хэтэрсэн дуудлага'], ['caller_name' => $customer->name, 'caller_phone' => $customer->phone, 'call_type' => 'network', 'call_from' => 'Мэдээллийн технологийн газар', 'status' => 'accepted', 'user_id' => $customer->id, 'api_username' => 'customer', 'agent_id' => $overdueAgent->id, 'requested_at' => now()->subDays(4), 'accepted_at' => now()->subDays(3)]);
    }
}
