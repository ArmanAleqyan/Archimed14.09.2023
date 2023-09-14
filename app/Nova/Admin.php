<?php

namespace App\Nova;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Models\User;
use App\Mail\SendVerifyUrl;

use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Actions\ActionRequest;
use Illuminate\Validation\Rules;


class Admin extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = User::class;




    public static function label() {
        return 'Операторы';
    }

    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role_id', 2);
    }


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [

        'lastName',
        'firstName' ,
        'email',
        'phone',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {



        $a = [
            Text::make('Имя','firstName')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Фамилия','lastName')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('Эл.почта','email')
                ->sortable()
                ->rules('required', 'email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            Text::make('Ном.телефона','phone')
                ->sortable()
                ->rules('required', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

            BelongsTo::make('City')->nullable()->required(),

            Select::make('Ном.телефона','role_id')->options([
                '2' => 'Администратор',
            ])->required(),


            Password::make('Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),
        ];


        return $a;
    }





    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
//
//        $get_user  = User::where('id', $request->resourceId)->first();
//        $details = [];
//        if($get_user != null){
//            Mail::to($get_user->email)->send(new SendVerifyUrl($details));
//        }


        return [
            Actions\EmailAccountProfile::make(),
            Actions\deleteToken::make(),
        ];
    }
}
