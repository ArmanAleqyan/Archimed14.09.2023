<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;

class Survey extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Survey::class;
    public static function label() {
        return 'Обследование';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
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
//            ID::make()->sortable(),
            Text::make('Обследование','title')->rules('required', 'max:255'),
            BelongsTo::make('Категория', 'SurveysCategories', 'App\Nova\SurveysCategories')->sortable()
                ->rules('requ
                ired', 'max:255'),
            Text::make('Код исследования','code')->rules('required', 'max:255'),
            Text::make('Срок готовности','duration')->rules('required', 'max:255'),
            Number::make('Цена','price')->rules('required', 'max:255'),
            Text::make('Подготовка к анализам','preparation')->hideFromIndex()->rules('required', 'max:255'),
            Text::make('Альтернативное название, если есть','alliterative')->hideFromIndex()->rules('required', 'max:255'),

            BelongsTo::make('Комплекс', 'SurveysComplex', 'App\Nova\ComplexOfAnalyzes')->hideFromIndex()->sortable()
                ->rules('required', 'max:255'),
            Text::make('Доступность обследования','ability')->hideFromIndex()->rules('max:255'),
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
