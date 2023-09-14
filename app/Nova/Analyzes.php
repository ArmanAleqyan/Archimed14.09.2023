<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;

use Laravel\Nova\Http\Requests\NovaRequest;

class Analyzes extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\analysis::class;
    public static function label() {
        return 'Анализы';
    }

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
      'title',  'code','price'
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
            Text::make('Название','title')->sortable()->rules('required', 'max:255'),
            BelongsTo::make('Категория', 'CategoryOfAnalysis', 'App\Nova\CategoryOfAnalysis')->sortable()
                ->rules('required', 'max:255'),
            Text::make('Код исследование','code')->rules('required', 'max:255'),
            Number::make('Срок готовности','duration')->hideFromIndex()->rules('required', 'max:255'),
            Number::make('Цена','price') ->sortable()->rules('required', 'max:255'),
            Textarea::make('Подготовка к анализам','preparation')->hideFromIndex()->rules('required'),
            Textarea::make('Динамика результатов','previous')->hideFromIndex()->rules('required'),
            Textarea::make('Биоматериал','bio')->hideFromIndex()->rules('required'),
            BelongsTo::make('Комплекс Анализов','ComplexOfAnalyzes','App\Nova\ComplexOfAnalyzes')->hideFromIndex()->nullable(),
            Text::make('Доступность анализов','ability')->hideFromIndex()->rules('required', 'max:255'),
            Text::make('Статус готовности','status')->hideFromIndex()->rules('required', 'max:255'),
            Text::make('Направление Анализов','direction')->hideFromIndex()->rules('required', 'max:255'),
            Text::make('Место сдачи анализов','place')->hideFromIndex()->rules('required', 'max:255'),
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
