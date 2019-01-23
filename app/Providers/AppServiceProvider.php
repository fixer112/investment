<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\User;
use DB;
use App;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        
            $identitys = User::where('id_change','=', '2')->get(); 
            
            view()->share ('identitys', $identitys);
        
        
        Blade::directive('money', function($amount){
            return "<?php echo '₦'. number_format($amount,2);?>";
        });
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
