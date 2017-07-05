<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Model\User;
use App\Model\UserCustomer;
use App\Lib\JassEasemob;

class CustomerService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:customer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add Customer Service';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //13255646715
        $jaseeasemob = new JassEasemob();
        $serviceUser = User::find('296');
        $userTab = 'friend_user';//(new User())->getTable();
        $userCoustomerTab = 'friend_user_customer';//UserCustomer::getTableName();
        $sql = "
            select 
                id,phone 
            from 
                `{$userTab}`
            where 
                id not in (select user_id from `{$userCoustomerTab}`)
            limit 500";
        $data = DB::select($sql);
        foreach($data as $user) {
            if(mb_strlen($user->phone)!=11) {
                continue;
            }
            $userCustomer = new UserCustomer();
            $userCustomer->user_id=$user->id;
            $userCustomer->service_user_id=$serviceUser->id;
            $add = $jaseeasemob->addFriend($user->phone, '13255646715');
            if(isset($add['error'])) {
                echo "{$user->phone} add 13255646715 friend error\n";
                continue;
            }
            echo "{$user->phone} add 13255646715 friend ok\n";
            $userCustomer->save();
            sleep(1);
        }
        echo "===opt complete===\n";
    }
}
