<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Place;
use Inspheric\Fields\Url;
use Laravel\Nova\Http\Requests\NovaRequest;

class NewsAndSales extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\NewsAndSales::class;

    public static function label() {
        return 'Новости и Акции';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'Title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'title' , 'description', 'url', 'type',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
         //   ID::make()->sortable(),
            Text::make('Заголовок' , 'title')->rules('required')->required(),
            Textarea::make('Описание' , 'description')->rules('required')->required(),
            Text::make('Ссылка' ,  'url')->hideFromIndex()->rules('required')->nullable(),
            Select::make('Тип' , 'type')->rules('required')->required()->options([
                'Новость' => 'Новость',
                'Акция' => 'Акция',
            ]),
            Image::make('Фотогафия', 'photo')->required(),


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
