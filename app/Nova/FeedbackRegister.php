<?php

namespace App\Nova;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Http\Requests\NovaRequest;

use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\Hidden;

use App\Models\RegisterFeedbackChat;

class FeedbackRegister extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Feedback::class;
    public static function label() {
        $count = \App\Models\Feedback::where('status', 1)->count();
        return 'Новые ' . $count;
    }

    public static $sort = [
        'updated_at' => 'desc'
    ];


    public static function indexQuery(NovaRequest $request, $query)
    {
        $query->getQuery()->orders = [];
        return $query->where('status',1)->orderBy(key(static::$sort), reset(static::$sort));
    }
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    public function authorizedToDelete(Request $request)
    {
        return false;
    }


    public function authorizedToUpdate(Request $request)
    {
        return false;
    }

    public static function authorizedToCreate(Request $request)
    {
        return false;
    }


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'SenderEmail';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'SenderEmail','description'
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
            Hidden::make('SenderEmail')->default(auth()->user()->email),
            Text::make('Имя','name')->sortable()->rules('required', 'max:255'),
            Text::make('Эл.почта','SenderEmail')->sortable()->rules('required', 'max:255'),
            Textarea::make('Сообщение','description')->alwaysShow()->rules('required'),
            HasMany::make('Переписки', 'RegisterFeedbackChat', 'App\Nova\RegisterFeedbackChat'),
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

    public function tools(NovaRequest $request){
        return [
           // \Binarcode\NovaChat\Tools\ChatTool::make(),
        ];
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
