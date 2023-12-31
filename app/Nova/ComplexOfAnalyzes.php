<?php
//
//namespace App\Nova;
//
//use Illuminate\Http\Request;
//use Laravel\Nova\Fields\ID;
//use Laravel\Nova\Fields\Text;
//use Laravel\Nova\Fields\BelongsTo;
//use Laravel\Nova\Fields\Number;
//use Laravel\Nova\Http\Requests\NovaRequest;
//
//class ComplexOfAnalyzes extends Resource
//{
//    /**
//     * The model the resource corresponds to.
//     *
//     * @var string
//     */
//    public static $model = \App\Models\ComplexOfAnalizes::class;
//    public static function label() {
//        return 'Комплекс Анализов';
//    }
//
//    /**
//     * The single value that should be used to represent the resource when being displayed.
//     *
//     * @var string
//     */
//    public static $title = 'title';
//
//    /**
//     * The columns that should be searched.
//     *
//     * @var array
//     */
//    public static $search = [
//        'title','code'
//    ];
//
//    /**
//     * Get the fields displayed by the resource.
//     *
//     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
//     * @return array
//     */
//    public function fields(NovaRequest $request)
//    {
//        return [
////            ID::make()->sortable(),
//
//            Text::make('Название комплекса','title')
//                ->sortable()
//                ->rules('required', 'max:255'),
//
//            BelongsTo::make('Категория','ComplexCategory','App\Nova\CategoryOfAnalysis')->nullable(),
//
//            Number::make('Уровень категории','code')
//                ->sortable()
//                ->rules('required', 'max:255'),
//        ];
//    }
//
//    /**
//     * Get the cards available for the request.
//     *
//     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
//     * @return array
//     */
//    public function cards(NovaRequest $request)
//    {
//        return [];
//    }
//
//    /**
//     * Get the filters available for the resource.
//     *
//     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
//     * @return array
//     */
//    public function filters(NovaRequest $request)
//    {
//        return [];
//    }
//
//    /**
//     * Get the lenses available for the resource.
//     *
//     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
//     * @return array
//     */
//    public function lenses(NovaRequest $request)
//    {
//        return [];
//    }
//
//    /**
//     * Get the actions available for the resource.
//     *
//     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
//     * @return array
//     */
//    public function actions(NovaRequest $request)
//    {
//        return [];
//    }
//}
