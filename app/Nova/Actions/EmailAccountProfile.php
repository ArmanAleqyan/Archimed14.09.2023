<?php

namespace App\Nova\Actions;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Mail\SendVerifyUrl;

use App\Models\User;
use function Symfony\Component\HttpFoundation\expire;

class EmailAccountProfile extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */

    public function name()
    {
        return __('Отправить Доступ на эл.почту');
    }

    public function handle(ActionFields  $fields ,Collection  $user)
    {

        foreach ($user as $model) {
           $a = app('auth.password.broker')->createToken($model);
//            DB::table('password_resets')->insert([
//               'email' => $model->email,
//                'token' =>  $a,
//                'created_at' => Carbon::now(),
//            ]);
            $details =[
                'token' => 'https://archimed.justcode.am/password/reset/'.$a,
                'email' => $model->email
            ];
            Mail::to($model->email)->send(new SendVerifyUrl($details));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {

        return [];
    }
}
