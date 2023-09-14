<?php

namespace App\Nova;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Hidden;

use App\Models\RegisterFeedbackChat as ChatModel;
use App\Models\Feedback as feedback;



use App\Mail\RegisterFeedback;

use Laravel\Nova\Http\Requests\NovaRequest;
use function Termwind\breakLine;

class RegisterFeedbackChat extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\RegisterFeedbackChat::class;

    public static function label() {

        return 'При регистрации';
    }


    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'message';


    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'SenderEmail' , 'ReceiverEmail', 'message'
    ];
//

    public static $perPageViaRelationship = 15;
    public function authorizedToUpdate(Request $request)
    {
        return false;
    }
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }
    public function authorizedToDelete(Request $request)
    {
        return false;
    }
    public static function createButtonLabel()
    {
        return 'Отправить Сообщение';
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */

    public function fieldsForCreate(NovaRequest $request) {
        $end = Carbon::now()->subMinutes(2);
        DB::table('Test')->where('created_at', '<', $end)->delete();
        $get = feedback::where('id', $request->viaResourceId)->get();
        if(!$get->isEMpty()) {
            $getFeedback = feedback::where('id', $get[0]->id)->get();
            $data = Carbon::now();
            Cookie::queue('date', $data, 360);
            $getCookie =  Cookie::get('date');
            if($request->message != null) {

                feedback::where('SenderEmail', $getFeedback[0]->SenderEmail)->update([
                    'status' => 0,
                ]);
                $details = [
                    'sender_message' => $getFeedback[0]->description,
                    'sender_name' => auth()->user()->firstName,
                    'message' => $request->message,
                    'sender_surname' =>  auth()->user()->lastName
                ];
               $count =  DB::table('Test')->where('sender', $getFeedback[0]->id)->where('created_at' ,'=', $getCookie)->get();
                   if($count->count() < 1){
                       DB::table('Test')->insert([
                           'sender' => $getFeedback[0]->id,
                           'created_at' => $getCookie
                       ]);

                         Mail::to($getFeedback[0]->SenderEmail)->send(new RegisterFeedback($details));
                   }
           }
            return [
                Hidden::make('SenderEmail')->default(auth()->user()->email),
                Hidden::make('feedback_id')->default($get[0]->id),
                Hidden::make('ReceiverEmail')->default($getFeedback[0]->SenderEmail),
                Text::make('От','SenderEmail')->hideWhenCreating(),
                Text::make('Кому','ReceiverEmail')->hideWhenCreating(),
                Text::make('Сообщение','message')->hideWhenCreating()->hideFromDetail(),
                Textarea::make('Сообщение','message')->alwaysShow()->rules('required')->hideFromIndex(),
            ];
        }
    }




    public function fields(NovaRequest $request)
    {
        $get = ChatModel::where('id', $request->viaResourceId)->get();
        if(!$get->isEMpty()){
            $getFeedback = feedback::where('id', $get[0]->feedback_id)->get();
            return [
                Hidden::make('SenderEmail')->default(auth()->user()->email),
                Hidden::make('feedback_id')->default($get[0]->feedback_id),
                Hidden::make('ReceiverEmail')->default($getFeedback[0]->SenderEmail),
                Text::make('От','SenderEmail')->hideWhenCreating(),
                Text::make('Кому','ReceiverEmail')->hideWhenCreating(),
                Text::make('Сообщение','message')->hideWhenCreating()->hideFromDetail(),
                Textarea::make('Сообщение','message')->alwaysShow()->rules('required')->hideFromIndex(),
            ];
        }else{
            return [
                Hidden::make('SenderEmail')->default(auth()->user()->email),
                Text::make('От','SenderEmail')->hideWhenCreating(),
                Text::make('Кому','ReceiverEmail')->hideWhenCreating(),
                Text::make('Сообщение','message')->hideWhenCreating()->hideFromDetail(),
                Textarea::make('Сообщение','message')->alwaysShow()->rules('required')->hideFromIndex(),
            ];
        }

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
