<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use App\Model\User;
use App\Model\UserCustomer;

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
        $serviceUser = User::find('296');
        $userTab = 'friend_user';//(new User())->getTable();
        $userCoustomerTab = 'friend_user_customer';//UserCustomer::getTableName();
        $sql = "
            select 
                id 
            from 
                `{$userTab}`
            where 
                id not in (select user_id from `{$userCoustomerTab}`)
            limit 4";
        $data = DB::select($sql);
        foreach($data as $user) {
            $userCustomer = new UserCustomer();
            $userCustomer->user_id=$user->id;
            $userCustomer->service_user_id=$serviceUser->id;
            $userCustomer->saved();
        }
    }
}
