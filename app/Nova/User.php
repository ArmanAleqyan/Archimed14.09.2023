<?php

namespace App\Nova;


use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Laravel\Nova\Fields\Gravatar;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;

use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Select;

class User extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */


    public static $model = \App\Models\User::class;
    
    public static function label() {
        return 'Пользователи';
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
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->where('role_id', 3);
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
//            ID::make()->sortable(),

//            Gravatar::make()->maxWidth(50),

                 Text::make('Имя','firstName')
                ->sortable()
                ->rules('required', 'max:255'),

                 Text::make('Фамилия','lastName')
                ->sortable()
                ->rules('required', 'max:255'),

                 Text::make('Эл.почта','email')
                ->sortable()
                ->rules('email', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

                Text::make('Ном.телефона','phone')
                ->sortable()
                ->rules('required', 'max:255')
                 ->creationRules('unique:users')
                 ->updateRules('unique:users,email,{{resourceId}}'),

               BelongsTo::make('City')->nullable()->required(),
                    Select::make('Роль','role_id')->options([
                        '3' => 'Пользователь',
                        '2' => 'Оператор',
               ])->required(),

            Password::make('Пароль','Password')
                ->onlyOnForms()
                ->creationRules('required', Rules\Password::defaults())
                ->updateRules('nullable', Rules\Password::defaults()),
        ];
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
        return [];
    }
}
